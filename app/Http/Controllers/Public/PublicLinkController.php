<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SellerLink;

class PublicLinkController extends Controller
{
    public function show(string $slug)
    {
        $link = SellerLink::query()
            ->with(['user']) // se você tiver SellerProfile, faça ->with(['user', 'user.sellerProfile'])
            ->where('public_slug', $slug)
            ->firstOrFail();

        $user = $link->user;

        // Dados do produto (preferir colunas; fallback snapshot)
        $snap = $link->checkout_snapshot ?? [];

        $productTitle = $link->product_title ?? ($snap['product'] ?? 'Produto');
        $priceCents   = $link->price_cents ?? (isset($snap['price']) ? (int)$snap['price'] : null);
        $installments = $link->installments ?? (isset($snap['installments']) ? (int)$snap['installments'] : null);
        $merchant     = $link->merchant_name ?? ($snap['seller'] ?? null);

        // imagem
        $productImage = $link->product_image_url;

        // metas OG
        $metaTitle = $productTitle;
        $metaDescription = $merchant
            ? "{$merchant} • " . ($priceCents ? $this->formatBRL($priceCents) : '')
            : ($priceCents ? $this->formatBRL($priceCents) : '');

        $metaImage = $productImage ?? asset('images/placeholder-product.png'); // crie um placeholder
        $metaUrl = route('public.link', ['slug' => $link->public_slug]);

        return view('public.link', [
            'link' => $link,

            'product' => [
                'title' => $productTitle,
                'image' => $productImage,
                'price_cents' => $priceCents,
                'installments' => $installments,
                'merchant' => $merchant,
                'checkout_url' => $link->checkout_url,
            ],

            'seller' => [
                'name' => $user?->name,
                'phone' => $user?->phone ?? null,
                'photo_url' => $user?->photo_url ?? null, // ajuste para seu campo real
            ],

            'metaTitle' => $metaTitle,
            'metaDescription' => trim($metaDescription),
            'metaImage' => $metaImage,
            'metaUrl' => $metaUrl,
        ]);
    }

    private function formatBRL(int $cents): string
    {
        return 'R$ ' . number_format($cents / 100, 2, ',', '.');
    }
}