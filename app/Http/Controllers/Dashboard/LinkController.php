<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->load([
            'links' => fn ($query) => $query->orderBy('position'),
            'activeSubscription',
        ]);

        return view('dashboard.links.index', [
            'user' => $user,
            'links' => $user->links,
            'linksLimit' => $user->salesLinksLimit(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user()->load([
            'links' => fn ($query) => $query->orderBy('position'),
            'activeSubscription',
        ]);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'url' => ['required', 'url', 'max:2048'],
        ], [
            'title.required' => 'Informe o título do link.',
            'title.max' => 'O título deve ter no máximo 150 caracteres.',
            'url.required' => 'Informe a URL do link.',
            'url.url' => 'Informe uma URL válida.',
            'url.max' => 'A URL deve ter no máximo 2048 caracteres.',
        ]);

        $limit = max(1, $user->salesLinksLimit());
        $currentCount = $user->links()->count();

        if ($currentCount >= $limit) {
            throw ValidationException::withMessages([
                'url' => 'Seu plano atingiu o limite de links permitidos.',
            ]);
        }

        $nextPosition = ((int) $user->links()->max('position')) + 1;

        try {
            $user->links()->create([
                'title' => trim($data['title']),
                'url' => trim($data['url']),
                'position' => $nextPosition,
                'is_active' => true,
            ]);

            return redirect()
                ->route('dashboard.links.index')
                ->with('status', 'Link salvo com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Erro ao criar link', [
                'user_id' => $user->id,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['general' => 'Não foi possível salvar o link.']);
        }
    }

    public function update(Request $request, UserLink $link): RedirectResponse
    {
        abort_unless($link->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'url' => ['required', 'url', 'max:2048'],
        ], [
            'title.required' => 'Informe o título do link.',
            'title.max' => 'O título deve ter no máximo 150 caracteres.',
            'url.required' => 'Informe a URL do link.',
            'url.url' => 'Informe uma URL válida.',
            'url.max' => 'A URL deve ter no máximo 2048 caracteres.',
        ]);

        try {
            $link->update([
                'title' => trim($data['title']),
                'url' => trim($data['url']),
            ]);

            return redirect()
                ->route('dashboard.links.index')
                ->with('status', 'Link atualizado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Erro ao atualizar link', [
                'user_id' => $request->user()->id,
                'link_id' => $link->id,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['general' => 'Não foi possível atualizar o link.']);
        }
    }

    public function toggle(Request $request, UserLink $link): RedirectResponse
    {
        abort_unless($link->user_id === $request->user()->id, 403);

        try {
            $link->update([
                'is_active' => ! $link->is_active,
            ]);

            return redirect()
                ->route('dashboard.links.index')
                ->with('status', 'Status do link atualizado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Erro ao alternar link', [
                'user_id' => $request->user()->id,
                'link_id' => $link->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'general' => 'Não foi possível alterar o status do link.',
            ]);
        }
    }

    public function destroy(Request $request, UserLink $link): RedirectResponse
    {
        abort_unless($link->user_id === $request->user()->id, 403);

        try {
            $link->delete();

            return redirect()
                ->route('dashboard.links.index')
                ->with('status', 'Link removido com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Erro ao remover link', [
                'user_id' => $request->user()->id,
                'link_id' => $link->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'general' => 'Não foi possível remover o link.',
            ]);
        }
    }
}