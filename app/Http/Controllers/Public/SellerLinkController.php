<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SellerLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SellerLinkController extends Controller
{
    /**
     * Proxy/Resolver: recebe checkout_url e tenta retornar o JSON do produto
     * (pra frontend conseguir preencher preview mesmo quando CORS/403 atrapalha).
     */
    public function resolveCheckout(Request $request)
    {
        $data = $request->validate([
            'checkout_url' => ['required', 'string', 'max:2048'],
        ]);

        $checkoutUrl = trim($data['checkout_url']);

        // tenta extrair o hash/uuid da URL
        preg_match('/([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})/i', $checkoutUrl, $m);
        $hash = $m[1] ?? null;

        // lista de tentativas (você pode ajustar depois com o endpoint real)
        $candidates = [];
        if ($hash) {
            $candidates[] = $checkoutUrl;
            $candidates[] = "https://pay.hest.com.br/{$hash}";
            $candidates[] = "https://pay.hest.com.br/api/{$hash}";
            $candidates[] = "https://pay.hest.com.br/api/affiliation_link/{$hash}";
            $candidates[] = "https://pay.hest.com.br/api/affiliation-link/{$hash}";
            $candidates[] = "https://pay.hest.com.br/api/link/{$hash}";
            $candidates[] = "https://pay.hest.com.br/api/checkout/{$hash}";
        } else {
            $candidates[] = $checkoutUrl;
        }

        $lastError = null;

        foreach (array_unique($candidates) as $url) {
            try {
                $resp = Http::timeout(10)
                    ->withHeaders([
                        'Accept' => 'application/json, text/plain, */*',
                        'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome Safari',
                        'Referer' => $checkoutUrl,
                        'Origin' => 'https://pay.hest.com.br',
                        'X-Requested-With' => 'XMLHttpRequest',
                    ])
                    ->get($url);

                if (!$resp->successful()) {
                    $lastError = "HTTP {$resp->status()} em {$url}";
                    continue;
                }

                // se veio JSON
                $json = $resp->json();
                if (is_array($json) && !empty($json['product'])) {
                    return response()->json([
                        'ok' => true,
                        'source' => $url,
                        'payload' => $json,
                    ]);
                }

                // se veio string (HTML), não serve
                $lastError = "Resposta não-JSON em {$url}";
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
            }
        }

        return response()->json([
            'ok' => false,
            'error' => $lastError ?: 'Não foi possível resolver o checkout.',
        ], 422);
    }

    /**
     * Salva o link + snapshot e campos principais.
     * Espera que o frontend mande "checkout_payload" (o JSON que você mostrou).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'checkout_url' => ['required', 'string', 'max:2048'],
            'checkout_payload' => ['nullable', 'string'],
        ]);

        $payload = $data['checkout_payload'] ?? [];

        $link = SellerLink::create([
            'user_id' => $request->user()->id,
            'token' => (string) Str::uuid(),
            'checkout_url' => trim($data['checkout_url']),
            'public_slug' => Str::lower(Str::random(10)),

            // campos “derivados” do payload
            'product_title' => $payload['product'] ?? $payload['product_title'] ?? null,
            'price_cents' => isset($payload['price']) ? (int) $payload['price'] : null,
            'installments' => isset($payload['installments']) ? (int) $payload['installments'] : null,
            'merchant_name' => $payload['seller'] ?? $payload['merchant_name'] ?? null,

            // imagem (vamos montar URL completa a partir do nome do arquivo)
            'product_image_url' => $this->makeHestImageUrl($payload['image_default'] ?? null),

            // snapshot
            'checkout_snapshot' => !empty($payload) ? $payload : null,
            'last_fetched_at' => !empty($payload) ? now() : null,
            'status' => 'active',
        ]);

        return redirect()
            ->route('dashboard') // ajuste para sua tela
            ->with('success', 'Link criado!')
            ->with('public_link', route('public.link', ['slug' => $link->public_slug]));
    }

    private function makeHestImageUrl(?string $imageDefault): ?string
    {
        if (!$imageDefault) return null;

        // ⚠️ Ajuste aqui quando você tiver o path real do CDN da Hest
        // exemplo hipotético:
        return "https://pay.hest.com.br/products/{$imageDefault}";
    }
}