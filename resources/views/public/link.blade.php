@php
    $mockProfile = $profile ?? (object) [
        'display_name' => 'Ana Carolina Ribeiro',
        'photo_url' => 'https://humano.biologicbeing.com/images/profile-card.png',
        'phone' => '+55 11 99999-0000',
        'is_verified' => true,
        'bio' => 'Perfil público para validação de identidade humana com foco em confiança, autenticidade e presença digital.',
        'public_code' => 'HUM-ACR-2026',
        'cta_url' => 'https://linkdomeuproduto.com',
    ];

    $profile = $mockProfile;

    $profileUrl = url()->current();
    $productUrl = $profile->cta_url ?? 'https://linkdomeuproduto.com';

    $metaTitle = $metaTitle ?? ($profile->display_name . ' • Perfil humano verificado');
    $metaDescription = $metaDescription ?? 'Confirme a identidade, veja informações públicas e valide que este perfil pertence a uma pessoa real.';
    $ogImageUrl = $ogImageUrl ?? 'https://humano.biologicbeing.com/images/profile-card.png';

    $shareText = "Clique no link acima para conhecer os detalhes da pessoa que te enviou esse link\n\n"
        . $profileUrl
        . "\n\n"
        . "Clique abaixo para ver qual produto ela está oferecendo pra você:\n\n"
        . $productUrl;

    $whatsAppShareUrl = 'https://wa.me/?text=' . rawurlencode($shareText);
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $profileUrl }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $ogImageUrl }}">
    <meta property="og:image:secure_url" content="{{ $ogImageUrl }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="HUMANO">
    <meta property="og:locale" content="pt_BR">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $ogImageUrl }}">

    @vite(['resources/css/app.css'])
</head>
<body class="bg-[#ededed] text-[#111827] antialiased">
    <main class="mx-auto min-h-[100svh] max-w-md px-3 py-3">
        <section class="overflow-hidden rounded-[24px] border border-[#dfe3e8] bg-white shadow-[0_8px_28px_rgba(0,0,0,0.06)]">
            <div class="bg-[#f7f7f7] px-5 pb-5 pt-5">
                <div class="flex items-start gap-4">
                    <img
                        src="{{ $profile->photo_url }}"
                        alt="Foto de {{ $profile->display_name }}"
                        class="h-20 w-20 shrink-0 rounded-2xl object-cover shadow-sm ring-1 ring-black/5"
                    >

                    <div class="min-w-0 flex-1">
                        <h1 class="truncate text-[24px] font-semibold text-[#111827]">
                            {{ $profile->display_name }}
                        </h1>

                        <p class="mt-1 text-[15px] text-[#6b7280]">
                            {{ $profile->phone ?? '+55 11 99999-0000' }}
                        </p>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <div>
                        @if($profile->is_verified)
                            <span class="inline-flex items-center gap-1 rounded-full bg-[#e8f7ee] px-3 py-1.5 text-[13px] font-medium text-[#07c160] ring-1 ring-[#bfe8cf]">
                                <span class="h-2 w-2 rounded-full bg-[#07c160]"></span>
                                Verificado
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            id="shareProfileButton"
                            class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-[#07c160] text-white shadow-sm transition hover:brightness-95"
                            aria-label="Compartilhar perfil"
                            data-share-title="{{ $metaTitle }}"
                            data-share-text="{{ $shareText }}"
                            data-share-url="{{ $profileUrl }}"
                            data-whatsapp-url="{{ $whatsAppShareUrl }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="18" cy="5" r="3"></circle>
                                <circle cx="6" cy="12" r="3"></circle>
                                <circle cx="18" cy="19" r="3"></circle>
                                <path d="M8.59 13.51 15.42 17.49"></path>
                                <path d="M15.41 6.51 8.59 10.49"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white px-5 py-4">
                <div class="rounded-2xl bg-[#f7f7f7] px-4 py-4">
                    <p class="text-[16px] leading-7 text-[#374151]">
                        {{ $profile->bio }}
                    </p>
                </div>

                <div class="mt-4 divide-y divide-[#eef1f4] overflow-hidden rounded-2xl border border-[#eef1f4] bg-white">
                    <div class="flex items-center justify-between px-4 py-4">
                        <span class="text-[14px] text-[#6b7280]">Status</span>
                        <span class="text-[15px] font-medium text-[#111827]">
                            {{ $profile->is_verified ? 'Perfil validado' : 'Perfil em análise' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between px-4 py-4">
                        <span class="text-[14px] text-[#6b7280]">Código</span>
                        <span class="text-[15px] font-medium text-[#111827]">
                            {{ $profile->public_code }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between px-4 py-4">
                        <span class="text-[14px] text-[#6b7280]">Telefone</span>
                        <span class="text-[15px] font-medium text-[#111827]">
                            {{ $profile->phone ?? '+55 11 99999-0000' }}
                        </span>
                    </div>
                </div>

                <div class="mt-4 rounded-2xl border border-[#d7f1e1] bg-[#f1fbf5] px-4 py-4">
                    <p class="text-[14px] leading-6 text-[#3f4a5a]">
                        Este perfil foi publicado para facilitar a confirmação pública de identidade e reforçar confiança em interações online.
                    </p>
                </div>

                <div class="mt-4">
                    <a
                        href="{{ $productUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex w-full items-center justify-center rounded-2xl bg-[#111827] px-4 py-3 text-[14px] font-medium text-white transition hover:opacity-95"
                    >
                        Ver produto oferecido
                    </a>
                </div>
            </div>

            <div class="border-t border-[#eef1f4] bg-[#fafafa] px-5 py-4">
                <div class="flex items-center justify-between text-[13px] text-[#94a3b8]">
                    <span class="truncate">humano.biologicbeing.com</span>
                    <span class="truncate">{{ $profile->public_code }}</span>
                </div>
            </div>
        </section>
    </main>

    <script>
        (function () {
            const shareButton = document.getElementById('shareProfileButton');

            if (!shareButton) {
                return;
            }

            shareButton.addEventListener('click', async function () {
                const title = shareButton.dataset.shareTitle || document.title;
                const text = shareButton.dataset.shareText || '';
                const url = shareButton.dataset.shareUrl || window.location.href;
                const whatsappUrl = shareButton.dataset.whatsappUrl || '';

                try {
                    if (navigator.share) {
                        await navigator.share({
                            title: title,
                            text: text,
                            url: url
                        });
                        return;
                    }
                } catch (error) {
                    if (error && error.name === 'AbortError') {
                        return;
                    }
                }

                if (whatsappUrl) {
                    window.location.href = whatsappUrl;
                    return;
                }

                try {
                    const fallbackText = text ? text : url;
                    await navigator.clipboard.writeText(fallbackText);
                    alert('Texto de compartilhamento copiado com sucesso.');
                } catch (error) {
                    alert('Não foi possível compartilhar automaticamente.');
                }
            });
        })();
    </script>
</body>
</html>