<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SellerLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        $links = $request->user()
            ->links()
            ->latest()
            ->get();

        return view('dashboard.links.index', [
            'links' => $links,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'checkout_url' => ['required', 'url', 'max:2048'],
        ]);

        $token = $this->extractToken($data['checkout_url']);
        if (!$token) {
            return back()
                ->withInput()
                ->withErrors(['checkout_url' => 'URL inválida. Esperado formato: https://pay.hest.com.br/{uuid}']);
        }

        // MVP: ainda não vamos buscar metadata aqui, só salvar.
        // Depois: job para fazer GET no endpoint e preencher product_title, etc.

        $slug = $this->generateUniqueSlug();

        $request->user()->links()->create([
            'token' => $token,
            'checkout_url' => $data['checkout_url'],
            'public_slug' => $slug,
            'status' => 'active',
        ]);

        return redirect()
            ->route('dashboard.links.index')
            ->with('status', 'Link criado com sucesso.');
    }

    public function destroy(Request $request, SellerLink $link)
    {
        // Garantir que o link pertence ao usuário logado
        if ($link->user_id !== $request->user()->id) {
            abort(403);
        }

        $link->delete();

        return redirect()
            ->route('dashboard.links.index')
            ->with('status', 'Link removido.');
    }

    private function extractToken(string $url): ?string
    {
        // Aceita qualquer URL cujo path tenha um UUID (padrão 8-4-4-4-12)
        $pattern = '/([0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12})/';
        if (preg_match($pattern, $url, $m)) {
            return strtolower($m[1]);
        }
        return null;
    }

    private function generateUniqueSlug(): string
    {
        do {
            $slug = Str::lower(Str::random(10));
        } while (SellerLink::where('public_slug', $slug)->exists());

        return $slug;
    }
}
