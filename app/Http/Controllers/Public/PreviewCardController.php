<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PreviewCardController extends Controller
{
    public function show(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->slug) {
            $user->slug = User::generateUniquePublicSlug($user->name ?: 'usuario', $user->id);
            $user->save();
        }

        return redirect()->route('public.profile.show', [
            'slug' => $user->slug,
        ]);
    }

    public function publicShow(string $slug): View
    {
        try {
            $user = User::query()
                ->with([
                    'media' => fn ($query) => $query
                        ->where('collection', UserMedia::COLLECTION_PUBLIC_PROFILE)
                        ->where('media_type', UserMedia::TYPE_IMAGE)
                        ->where('status', UserMedia::STATUS_APPROVED)
                        ->orderByDesc('is_primary')
                        ->orderBy('position'),
                    'links' => fn ($query) => $query
                        ->where('is_active', true)
                        ->orderBy('position'),
                ])
                ->where('slug', $slug)
                ->firstOrFail();

            $primaryImage = $user->media->first();

            $photoUrl = $primaryImage
                ? $primaryImage->url()
                : asset('images/users/default-profile.png');

            return view('public.profile', [
                'profile' => (object) [
                    'display_name' => $user->name ?: 'Usuário',
                    'photo_url' => $photoUrl,
                    'phone' => $user->phone,
                    'public_code' => $user->slug,
                    'is_verified' => (bool) $user->email_verified,
                    'links' => $user->links,
                ],
                'profileUrl' => route('public.profile.show', ['slug' => $user->slug]),
                'metaTitle' => ($user->name ?: 'Usuário') . ' • Verificação humana',
                'metaDescription' => 'Perfil verificado de humano real',
                'ogImageUrl' => $photoUrl,
                'links' => $user->links,
            ]);
        } catch (\Throwable $e) {
            Log::error('Erro ao renderizar card público', [
                'slug' => $slug,
                'message' => $e->getMessage(),
            ]);

            abort(404);
        }
    }
}