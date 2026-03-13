<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user()->load([
            'media' => fn ($query) => $query
                ->where('collection', UserMedia::COLLECTION_PUBLIC_PROFILE)
                ->where('media_type', UserMedia::TYPE_IMAGE)
                ->orderByDesc('is_primary')
                ->orderBy('position'),
        ]);

        return view('dashboard.profile', [
            'user' => $user,
            'primaryImage' => $user->media->firstWhere('is_primary', true) ?? $user->media->first(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user()->load([
            'activeSubscription',
            'media' => fn ($query) => $query
                ->where('collection', UserMedia::COLLECTION_PUBLIC_PROFILE)
                ->where('media_type', UserMedia::TYPE_IMAGE)
                ->orderByDesc('is_primary')
                ->orderBy('position'),
        ]);

        $hasImage = $user->media->isNotEmpty();

        $rules = [
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'regex:/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/'],
            'img' => [$hasImage ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        $messages = [
            'name.required' => 'Informe o nome que será exibido no card.',
            'name.max' => 'O nome deve ter no máximo 120 caracteres.',
            'phone.required' => 'Informe seu telefone com DDD.',
            'phone.regex' => 'Informe um telefone válido com DDD. Ex: (11) 99999-0000',
            'img.required' => 'Selecione a imagem do card.',
            'img.image' => 'O arquivo enviado precisa ser uma imagem válida.',
            'img.mimes' => 'A imagem deve ser JPG, JPEG, PNG ou WEBP.',
            'img.max' => 'A imagem deve ter no máximo 2 MB.',
        ];

        $data = $request->validate($rules, $messages);

        $newFile = $request->file('img');
        $oldFileToDelete = null;

        try {
            DB::beginTransaction();

            $user->name = trim($data['name']);
            $user->phone = preg_replace('/\D+/', '', $data['phone']);

            if (! $user->slug || $user->isDirty('name')) {
                $user->slug = User::generateUniquePublicSlug($user->name, $user->id);
            }

            if ($newFile) {
                $directory = public_path('images/users');

                if (! File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                if (! is_writable($directory)) {
                    throw ValidationException::withMessages([
                        'img' => 'A pasta public/images/users está sem permissão de escrita.',
                    ]);
                }

                if (! $newFile->isValid()) {
                    throw ValidationException::withMessages([
                        'img' => 'O upload da imagem falhou. Tente novamente com outro arquivo.',
                    ]);
                }

                $originalName = $newFile->getClientOriginalName();
                $mimeType = $newFile->getMimeType();
                $sizeBytes = $newFile->getSize();
                $extension = strtolower($newFile->getClientOriginalExtension() ?: 'jpg');

                $filename = now()->format('YmdHis') . '_' . Str::uuid() . '.' . $extension;
                $relativePath = 'images/users/' . $filename;

                $newFile->move($directory, $filename);

                $currentPrimary = $user->media->firstWhere('is_primary', true) ?? $user->media->first();

                if ($currentPrimary) {
                    if ($currentPrimary->disk === 'public_root' && filled($currentPrimary->path)) {
                        $oldFileToDelete = public_path($currentPrimary->path);
                    }

                    $currentPrimary->update([
                        'disk' => 'public_root',
                        'path' => $relativePath,
                        'original_name' => $originalName,
                        'mime_type' => $mimeType,
                        'extension' => $extension,
                        'size_bytes' => $sizeBytes,
                        'width' => null,
                        'height' => null,
                        'duration_seconds' => null,
                        'position' => 1,
                        'is_primary' => true,
                        'visibility' => UserMedia::VISIBILITY_PUBLIC,
                        'status' => UserMedia::STATUS_APPROVED,
                        'processed_at' => now(),
                        'metadata' => null,
                    ]);
                } else {
                    UserMedia::query()->create([
                        'user_id' => $user->id,
                        'collection' => UserMedia::COLLECTION_PUBLIC_PROFILE,
                        'media_type' => UserMedia::TYPE_IMAGE,
                        'disk' => 'public_root',
                        'path' => $relativePath,
                        'original_name' => $originalName,
                        'mime_type' => $mimeType,
                        'extension' => $extension,
                        'size_bytes' => $sizeBytes,
                        'width' => null,
                        'height' => null,
                        'duration_seconds' => null,
                        'position' => 1,
                        'is_primary' => true,
                        'visibility' => UserMedia::VISIBILITY_PUBLIC,
                        'status' => UserMedia::STATUS_APPROVED,
                        'processed_at' => now(),
                        'metadata' => null,
                    ]);
                }
            } elseif (! $hasImage) {
                throw ValidationException::withMessages([
                    'img' => 'Selecione a imagem do card.',
                ]);
            }

            $user->save();

            DB::commit();

            if ($oldFileToDelete && File::exists($oldFileToDelete)) {
                File::delete($oldFileToDelete);
            }

            return redirect()
                ->route('dashboard.profile.edit')
                ->with('status', 'Perfil público atualizado com sucesso.');
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Erro ao atualizar perfil público', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'general' => 'Não foi possível atualizar o perfil público.',
                ]);
        }
    }
}