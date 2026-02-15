<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin | Connexion</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <!-- Heroicons CDN for inline SVG icons -->
     <link rel="icon" type="image/svg+xml" href="/images/Favicon_alcoil.svg">
</head>
<body class="min-h-screen bg-[#0f1e34] text-white flex items-center justify-center">
    <div class="relative w-full overflow-hidden">
        <div class="absolute inset-0 pointer-events-none"
             style="background:
                radial-gradient(800px 400px at 10% -10%, rgba(255,153,0,0.12), transparent 60%),
                radial-gradient(800px 400px at 90% 110%, rgba(255,153,0,0.12), transparent 60%),
                conic-gradient(from 210deg at 50% 50%, rgba(255,153,0,0.06), transparent 45%, rgba(255,153,0,0.06), transparent 70%)">
        </div>

        @php
            $isResetPage = in_array(Route::currentRouteName(), ['admin.password.request', 'admin.password.reset']);
        @endphp

        @if ($isResetPage)
            {{-- For reset password pages, just yield the content directly --}}
            @yield('content')
        @else
            {{-- For other auth pages (like login), keep the full container and footer --}}
            <div class="relative container mx-auto px-4 py-20">
                <div class="mx-auto max-w-md rounded-2xl bg-[#122241]/90 backdrop-blur border border-white/10 shadow-xl">
                    <div class="px-6 pt-6 text-center">
                        <div class="inline-flex items-center gap-2">
                            <img src="/images/Logo_ALCOIL_without_txt_white@3x.png" alt="Company" class="h-8 md:h-10">
                        </div>
                        {{-- <p class="mt-2 text-white/70 text-sm">Connectez-vous pour accéder au tableau de bord.</p> --}}
                    </div>

                    <div class="p-6">
                        @yield('content')
                    </div>

                    <div class="px-6 pb-6 text-center text-xs text-white/50">
                        © {{ date('Y') }} ALCOIL - SARL Ouest Equipement
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>