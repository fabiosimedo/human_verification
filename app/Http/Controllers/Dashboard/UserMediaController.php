<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserMediaController extends Controller
{
    public function index(Request $request)
    {
        $media = $request->user()
            ->media()
            ->where('collection', UserMedia::COLLECTION_PUBLIC_PROFILE)
            ->orderByDesc('is_primary')
            ->orderBy('position')
            ->get();

        return view('dashboard.media.index', [
            'media' => $media,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,mp4', 'max:4096'],
        ], [
            'file.required' => 'Selecione um arquivo.',
            'file.file' => 'O envio precisa ser um arquivo válido.',
            'file.mimes' => 'Envie JPG, JPEG, PNG, WEBP ou MP4.',
            'file.max' => 'O arquivo deve ter no máximo 4 MB.',
        ]);

        $file = $request->file('file');
        $user = $request->user();

        try {
            DB::beginTransaction();

            $extension = strtolower($file->getClientOriginalExtension() ?: 'bin');
            $filename = Str::uuid() . '.' . $extension;

            $path = $file->storeAs(
                'users/' . $user->id,
                $filename,
                'public'
            );

            $mimeType = $file->getMimeType() ?: 'application/octet-stream';
            $type = str_contains($mimeType, 'video')
                ? UserMedia::TYPE_VIDEO
                : UserMedia::TYPE_IMAGE;

            $nextPosition = ((int) $user->media()
                ->where('collection', UserMedia::COLLECTION_PUBLIC_PROFILE)
                ->max('position')) + 1;

            $shouldBePrimary = ! $user->media()
                ->where('collection', UserMedia::COLLECTION_PUBLIC_PROFILE)
                ->where('media_type', UserMedia::TYPE_IMAGE)
                ->where('is_primary', true)
                ->exists();

            $user->media()->create([
                'collection' => UserMedia::COLLECTION_PUBLIC_PROFILE,
                'media_type' => $type,
                'disk' => 'public',
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $mimeType,
                'extension' => $extension,
                'size_bytes' => $file->getSize(),
                'width' => null,
                'height' => null,
                'duration_seconds' => null,
                'position' => max(1, $nextPosition),
                'is_primary' => $type === UserMedia::TYPE_IMAGE ? $shouldBePrimary : false,
                'visibility' => UserMedia::VISIBILITY_PUBLIC,
                'status' => UserMedia::STATUS_APPROVED,
                'metadata' => null,
                'processed_at' => now(),
            ]);

            DB::commit();

            return back()->with('status', 'Mídia enviada com sucesso.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Erro ao enviar mídia', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'general' => 'Não foi possível enviar a mídia.',
            ]);
        }
    }

    public function setPrimary(Request $request, UserMedia $media): RedirectResponse
    {
        abort_unless($media->user_id === $request->user()->id, 403);

        if ($media->media_type !== UserMedia::TYPE_IMAGE) {
            return back()->withErrors([
                'general' => 'Apenas imagens podem ser definidas como principal.',
            ]);
        }

        try {
            DB::beginTransaction();

            $request->user()
                ->media()
                ->where('collection', UserMedia::COLLECTION_PUBLIC_PROFILE)
                ->where('media_type', UserMedia::TYPE_IMAGE)
                ->update(['is_primary' => false]);

            $media->update([
                'is_primary' => true,
                'visibility' => UserMedia::VISIBILITY_PUBLIC,
                'status' => UserMedia::STATUS_APPROVED,
            ]);

            DB::commit();

            return back()->with('status', 'Foto principal atualizada.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Erro ao definir mídia principal', [
                'user_id' => $request->user()->id,
                'media_id' => $media->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'general' => 'Não foi possível definir a foto principal.',
            ]);
        }
    }

    public function destroy(Request $request, UserMedia $media): RedirectResponse
    {
        abort_unless($media->user_id === $request->user()->id, 403);

        $disk = $media->disk;
        $path = $media->path;
        $wasPrimary = (bool) $media->is_primary;
        $type = $media->media_type;

        try {
            DB::beginTransaction();

            $media->delete();

            if ($wasPrimary && $type === UserMedia::TYPE_IMAGE) {
                $replacement = $request->user()
                    ->media()
                    ->where('collection', UserMedia::COLLECTION_PUBLIC_PROFILE)
                    ->where('media_type', UserMedia::TYPE_IMAGE)
                    ->orderBy('position')
                    ->first();

                if ($replacement) {
                    $replacement->update(['is_primary' => true]);
                }
            }

            DB::commit();

            if ($disk === 'public' && filled($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return back()->with('status', 'Mídia removida.');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Erro ao remover mídia', [
                'user_id' => $request->user()->id,
                'media_id' => $media->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'general' => 'Não foi possível remover a mídia.',
            ]);
        }
    }
}