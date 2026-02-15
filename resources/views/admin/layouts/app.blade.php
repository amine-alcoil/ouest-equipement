<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin | Tableau de bord</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <!-- Heroicons CDN for inline SVG icons -->
     <link rel="icon" type="image/svg+xml" href="/images/Favicon_alcoil.svg">
    <script src="https://unpkg.com/heroicons@2.0.18/24/outline/index.js"></script>
    <style>
        /* Drawer items slide-in */
        .drawer-item {
            opacity: 0;
            transform: translateX(-12px);
            animation: drawerIn 500ms ease forwards;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .drawer-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: #24da00ff; /* Green accent */
            transform: scaleY(0);
            transform-origin: bottom;
            transition: transform 0.3s ease;
        }

        .drawer-item:hover::before,
        .drawer-item.active::before {
            transform: scaleY(1);
            transform-origin: top;
        }

        .drawer-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
            padding-left: 1.25rem; /* slightly shift content right on hover */
        }

        .drawer-item.active {
            background-color: rgba(36, 218, 0, 0.1); /* Slight green tint for active */
            border-right: 1px solid rgba(36, 218, 0, 0.2);
        }

        .drawer-item:nth-child(1) { animation-delay: 60ms; }
        .drawer-item:nth-child(2) { animation-delay: 120ms; }
        .drawer-item:nth-child(3) { animation-delay: 180ms; }
        .drawer-item:nth-child(4) { animation-delay: 240ms; }
        .drawer-item:nth-child(5) { animation-delay: 300ms; }
        @keyframes drawerIn {
            to { opacity: 1; transform: translateX(0); }
        }

        /* Drawer behavior: height, non-scroll, slide */
        #adminDrawer {
            height: 100vh;            /* responsive height 100% of viewport */
            overflow: hidden;         /* no scrollbars in drawer */
            transition: transform 300ms ease-out;
            will-change: transform;
        }
        #adminDrawer.drawer--collapsed {
            transform: translateX(-100%); /* slide out on small screens */
        }
        @media (min-width: 768px) {
            /* On desktop, drawer is static and always shown */
            #adminDrawer.drawer--collapsed {
                transform: none;
            }
        }
        /* Layout fix: fixed drawer + header; only content scrolls */
        html, body { height: 100%; }
        body { overflow: hidden; }

        #adminDrawer {
            position: fixed;
            top: 0;
            left: 0;
            width: 18rem;          /* 72 = 18rem */
            height: 100vh;
            overflow: hidden;      /* no scrollbars in drawer */
            z-index: 40;
        }

        #adminHeader {
            position: fixed;
            top: 0;
            left: 18rem;           /* offset equal to drawer width */
            right: 0;
            height: 4rem;          /* h-16 = 64px */
            z-index: 30;
        }

        #contentScroll {
            position: fixed;
            top: 4rem;             /* below header */
            left: 18rem;           /* beside drawer */
            right: 0;
            bottom: 0;
            overflow: auto;        /* only content area scrolls */
            padding: 1rem;
        }

        /* Optional: adjust for small screens if needed */
        @media (max-width: 767px) {
            #adminDrawer { width: 16rem; }
            #adminHeader { left: 16rem; }
            #contentScroll { left: 16rem; }
        }

        /* Active drawer item highlight */
        .drawer-item.active {
            /* background-color: rgba(255, 255, 255, 0.1); replaced by above styles */
            /* border-color: rgba(255, 255, 255, 0.25); replaced by above styles */
        }
    </style>
</head>
<body class="min-h-screen bg-[#0f1e34] text-white">

@include('components.alert-notifications')

<div class="min-h-screen flex">
    <!-- Drawer -->
    <aside id="adminDrawer" class="z-40 top-0 left-0 h-full w-72 bg-[#122241] border-r border-white/10 overflow-hidden">
        <div class="h-16 flex items-center justify-center px-4 border-b border-white/10">
            <div class="inline-flex items-center gap-2">
                <img src="/images/Logo_ALCOIL_without_txt_white@3x.png" alt="Company" class="h-8 md:h-10">
            </div>
        </div>

        <nav class="p-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="drawer-item flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 border border-transparent {{ request()->routeIs('admin.dashboard') ? 'active bg-secondary/5 border-secondary_2' : '' }}">
                <svg class="h-5 w-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Tableau de bord</span>
            </a>
            <div class="flex flex-col gap-2">
                <a href="{{ route('admin.products') }}" class="drawer-item flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 border border-transparent {{ request()->routeIs('admin.products') ? 'active bg-secondary/5 border-secondary_2' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="1.5" d="M3 7l9-4 9 4-9 4-9-4zM3 10l9 4 9-4M3 13l9 4 9-4"/>
                    </svg>
                    <span>Produits</span>
                </a>
                <a href="{{ route('admin.categories') }}" class="drawer-item flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 border border-transparent {{ request()->routeIs('admin.categories') ? 'active bg-secondary/5 border-secondary_2' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <span>Catégories</span>
                </a>
                <a href="{{ route('admin.clients') }}" class="drawer-item flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 border border-transparent {{ request()->routeIs('admin.clients') ? 'active bg-secondary/5 border-secondary_2' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="1.5" d="M16 11c1.657 0 3-1.567 3-3.5S17.657 4 16 4s-3 1.567-3 3.5 1.343 3.5 3 3.5zM8 11c1.657 0 3-1.567 3-3.5S9.657 4 8 4 5 5.567 5 7.5 6.343 11 8 11zm8 2c-2.21 0-4.2 1.343-5 3.25C10.2 14.343 8.21 13 6 13c-2.761 0-5 2.239-5 5v2h22v-2c0-2.761-2.239-5-5-5z"/>
                    </svg>
                    <span>Clients</span>
                </a>
                <!-- Devis -->
                <a href="{{ route('admin.devis') }}" class="drawer-item flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 border border-transparent {{ request()->routeIs('admin.devis') ? 'active bg-secondary/5 border-secondary_2' : '' }}">
                    <!-- Quotes icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="1.5" d="M3 7h18M6 12h12M9 17h6"/>
                    </svg>
                    <span>Devis</span>
                </a>
            </div>
            <a href="{{ route('admin.contact-messages') }}" class="drawer-item flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 border border-transparent {{ request()->routeIs('admin.contact-messages*') ? 'active bg-secondary/5 border-secondary_2' : '' }}">
                <svg class="h-5 w-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>Messages de contact</span>
            </a>
            @if((session('admin_user')['role'] ?? null) === 'admin')
            <a href="{{ route('admin.users') }}" class="drawer-item flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 border border-transparent {{ request()->routeIs('admin.users') ? 'active bg-secondary/5 border-secondary_2' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-width="1.5" d="M16 11c1.657 0 3-1.567 3-3.5S17.657 4 16 4s-3 1.567-3 3.5 1.343 3.5 3 3.5zM8 11c1.657 0 3-1.567 3-3.5S9.657 4 8 4 5 5.567 5 7.5 6.343 11 8 11zm8 2c-2.21 0-4.2 1.343-5 3.25C10.2 14.343 8.21 13 6 13c-2.761 0-5 2.239-5 5v2h22v-2c0-2.761-2.239-5-5-5z"/>
                </svg>
                <span>Utilisateurs</span>
            </a>
            @endif
            <a href="{{ route('admin.settings') }}" class="drawer-item flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/10 border border-transparent {{ request()->routeIs('admin.settings') ? 'active bg-secondary/5 border-secondary_2' : '' }}">
                <svg class="h-5 w-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Paramètres</span>
            </a>
        </nav>
    </aside>

    <!-- Main -->
    <div class="flex-1 min-h-screen">
        <!-- Header -->
        <header id="adminHeader" class="z-30 h-16 bg-[#122241]/90 backdrop-blur border-b border-white/10">
            <div class="h-full px-4 flex items-center">
                <div class="inline-flex items-center gap-2">
                    <span class="font-semibold text-3xl">Dashboard</span>
                </div>
                <div class="ml-auto flex items-center gap-3">
                    <img src="{{ (isset($companyInfo) && $companyInfo->logo_path) ? Storage::url($companyInfo->logo_path) : (session('admin_user')['avatar'] ?? '/images/Logo_ALCOIL_without_txt_white@3x.png') }}" alt="Avatar" class="h-10 w-10 rounded-full border border-white/10 object-cover bg-white">
                    <div class="hidden md:flex flex-col leading-tight">
                        <span class="text-sm font-semibold text-white">{{ (session('admin_user')['name'] ?? 'Admin') }}</span>
                        <span class="text-xs text-white/70">{{ (session('admin_user')['email'] ?? '') }}</span>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg bg-white/10 border border-white/15 p-2 hover:bg-white/15" title="Déconnexion">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main id="contentScroll" class="p-4">
            @yield('content')
        </main>
        @yield('scripts')
    </div>
</div>
</body>
</html>
