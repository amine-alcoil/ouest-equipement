<!-- NAVBAR MODERN -->
 
<nav class="bg-white text-black shadow-lg fixed w-full z-50 transition-all duration-300" id="navbar">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between" id="navbarContent">

        <!-- Logo -->
        <a href="/" class="flex items-center gap-3 logo group">
            <img src="{{ asset('images/Logo_ALCOIL_without_txt_org.png') }}" alt="logo" class="h-10 w-auto transition-transform duration-500 ease-out group-hover:scale-110 group-hover:rotate-3">
        </a>

        <!-- Menu -->
        <ul class="hidden md:flex items-center gap-8 text-sm font-semibold menu">
            <li><a href="/" class="hover:text-secondary transition">Accueil</a></li>
            <li><a href="/produits" class="hover:text-secondary transition">Produits</a></li>
            <li><a href="/a-propos" class="hover:text-secondary transition">À propos</a></li>
            <li><a href="/telechargement" class="hover:text-secondary transition">Téléchargements</a></li>
            <li><a href="/contact" class="hover:text-secondary transition">Contact</a></li>
        </ul>

        <!-- Icons -->
        <div class="flex items-center icons">

            <!-- Search Icon -->
            <div class="relative group">
                <button id="searchToggle" class="p-4 hover:bg-primary/10 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </div>

            <!-- Contact Icon -->
            <div class="relative group">
                <button id="contactBtn" class="p-4 hover:bg-primary/10 text-gray-700 hover:text-secondary transition-all duration-300 relative group" title="Contact">
                   <svg width='24' height='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'><rect width='24' height='24' stroke='none' fill='#000000' opacity='0'/>


<g transform="matrix(0.5 0 0 0.5 12 12)" >
<path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" translate(-24, -24)" d="M 24 4 C 12.972066 4 4 12.972074 4 24 C 4 35.027926 12.972066 44 24 44 C 35.027934 44 44 35.027926 44 24 C 44 12.972074 35.027934 4 24 4 z M 24 7 C 33.406615 7 41 14.593391 41 24 C 41 33.406609 33.406615 41 24 41 C 14.593385 41 7 33.406609 7 24 C 7 14.593391 14.593385 7 24 7 z M 24 13 C 20.704135 13 18 15.704135 18 19 L 18 19.5 C 17.992349565681767 20.040953874866357 18.276562944253993 20.544122046857417 18.743809274571493 20.816831582229785 C 19.211055604888994 21.089541117602153 19.78894439511101 21.089541117602153 20.256190725428507 20.816831582229785 C 20.723437055746007 20.544122046857417 21.007650434318233 20.040953874866357 21 19.5 L 21 19 C 21 17.325865 22.325865 16 24 16 C 25.674135 16 27 17.325865 27 19 C 27 21.340909 26.39136 21.634239 25.324219 22.472656 C 24.790648 22.891865 24.091764 23.375202 23.494141 24.189453 C 22.896517 25.003704 22.5 26.138742 22.5 27.5 C 22.492349565681767 28.040953874866357 22.776562944253993 28.544122046857417 23.243809274571493 28.816831582229785 C 23.711055604888994 29.089541117602153 24.28894439511101 29.089541117602153 24.756190725428507 28.816831582229785 C 25.223437055746007 28.544122046857417 25.507650434318233 28.040953874866357 25.5 27.5 C 25.5 26.646758 25.665983 26.300186 25.912109 25.964844 C 26.158236 25.629501 26.584352 25.296698 27.175781 24.832031 C 28.35864 23.902698 30 22.159091 30 19 C 30 15.704135 27.295865 13 24 13 z M 24 32 C 22.895430500338414 32 22 32.89543050033841 22 34 C 22 35.10456949966159 22.895430500338414 36 24 36 C 25.104569499661586 36 26 35.10456949966159 26 34 C 26 32.89543050033841 25.104569499661586 32 24 32 z" stroke-linecap="round" />
</g>
</svg>
                    <span class="absolute top-3 right-3 flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
                    </span>
                </button>
            </div>

            <!-- Mobile Menu Toggle -->
            <div class="md:hidden relative">
                <button id="mobileMenuBtn" class="p-4 hover:bg-primary/10 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Search Bar (hidden by default) -->
    <div id="searchBar" class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between hidden">
        <input type="text" id="searchInput" placeholder="Rechercher..." class="flex-1 border-none outline-none text-lg">
        <button id="searchCancel" class="ml-4 text-gray-500 hover:text-black transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div id="mobileMenu" class="md:hidden fixed inset-x-0 top-16 bg-white border-b border-gray-200 shadow-xl transform -translate-y-full opacity-0 transition-all duration-300 z-40 pointer-events-none">
        <ul class="flex flex-col p-6 gap-4 text-lg font-semibold">
            <li><a href="/" class="block hover:text-secondary transition py-2 border-b border-gray-50">Accueil</a></li>
            <li><a href="/produits" class="block hover:text-secondary transition py-2 border-b border-gray-50">Produits</a></li>
            <li><a href="/a-propos" class="block hover:text-secondary transition py-2 border-b border-gray-50">À propos</a></li>
            <li><a href="/telechargement" class="block hover:text-secondary transition py-2 border-b border-gray-50">Téléchargement</a></li>
            <li><a href="/contact" class="block hover:text-secondary transition py-2">Contact</a></li>
        </ul>
    </div>
</nav>
<!-- CONTACT INFO SLIDE -->
<div id="contactOverlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-500 z-40"></div>
<aside id="contactBox" class="fixed right-0 top-0 h-full w-[360px] max-w-full bg-primary text-white shadow-2xl border-l border-white/10 transform translate-x-full opacity-0 transition-all duration-500 z-50">
  <div class="relative h-full flex flex-col">
    <div class="p-6 bg-gradient-to-r from-secondary to-primary/80">
      <div class="flex items-center justify-between">
        <h3 class="text-xl font-bold">Informations de contact</h3>
        <button id="contactClose" class="p-2 rounded hover:bg-white/10 transition" aria-label="Fermer">&times;</button>
      </div>
      <p class="text-white/80 text-sm mt-1">Besoin d’aide ? Nous sommes à votre écoute.</p>
    </div>
    <div class="p-6 space-y-4 flex-1 overflow-y-auto">
      <div class="flex items-start gap-3">
        <div class="p-2 rounded bg-white/10">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10.5a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21c-4.5-5.25-9-8.25-9-12a9 9 0 1 1 18 0c0 3.75-4.5 6.75-9 12Z"/></svg>
        </div>
        <div>
          <div class="text-sm text-white/70">Adresse</div>
          <div class="font-medium">Zone Industrielle, Sidi Bel Abbes</div>
        </div>
      </div>
      <div class="flex items-start gap-3">
        <div class="p-2 rounded bg-white/10">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75c0-1.243 1.007-2.25 2.25-2.25h3.75c.994 0 1.876.582 2.277 1.5l1.223 2.748a2.25 2.25 0 0 1-.314 2.37l-1.397 1.864a15.961 15.961 0 0 0 6.091 6.091l1.864-1.397a2.25 2.25 0 0 1 2.37-.314l2.748 1.223c.918.401 1.5 1.283 1.5 2.277v3.75c0 1.243-1.007 2.25-2.25 2.25h-.75C9.648 21.75 2.25 14.352 2.25 5.25v-.75Z"/></svg>
        </div>
        <div>
          <div class="text-sm text-white/70">Téléphone</div>
          <div class="font-medium">0555 02 60 38</div>
        </div>
      </div>
      <div class="flex items-start gap-3">
        <div class="p-2 rounded bg-white/10">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 4.5v15a2.25 2.25 0 0 1-2.25 2.25h-15A2.25 2.25 0 0 1 2.25 19.5v-15A2.25 2.25 0 0 1 4.5 2.25h15A2.25 2.25 0 0 1 21.75 4.5ZM6 8.25l6 4.5 6-4.5"/></svg>
        </div>
        <div>
          <div class="text-sm text-white/70">Email</div>
          <div class="font-medium">commercial@ouest-equipement.com</div>
        </div>
      </div>
      <div class="flex items-start gap-3">
        <div class="p-2 rounded bg-white/10">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h6"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3a9 9 0 1 0 9 9h-9V3Z"/></svg>
        </div>
        <div>
          <div class="text-sm text-white/70">Horaires</div>
          <div class="font-medium">Dim–Jeu 8:00–17:00</div>
        </div>
      </div>
    </div>
    <div class="p-6 border-t border-white/10">
      <div class="flex gap-3">
        <a href="tel:0555026038" class="px-4 py-2 rounded bg-secondary text-clean hover:opacity-90 transition">Appeler</a>
        <a href="mailto:commercial@ouest-equipement.com" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20 transition">Envoyer un email</a>
      </div>
    </div>
  </div>
</aside>

<!-- SCROLL UP BUTTON -->
<button id="scrollUpBtn" class="fixed bottom-6 right-6 bg-secondary text-white p-3 rounded-full shadow-lg opacity-0 translate-y-2 scale-90 pointer-events-none hover:bg-secondary/80 transition z-50">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
    </svg>
</button>

<!-- JS -->
<script>
    // Contact Box Toggle
    const contactBtn = document.getElementById('contactBtn');
    const contactBox = document.getElementById('contactBox');
    const contactClose = document.getElementById('contactClose');
    const contactOverlay = document.getElementById('contactOverlay');

    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    function openContact(){
        closeMobileMenu();
        contactBox.classList.remove('translate-x-full','opacity-0');
        contactBox.classList.add('translate-x-0','opacity-100');
        contactOverlay.classList.remove('opacity-0','pointer-events-none');
        contactOverlay.classList.add('opacity-100');
    }
    function closeContact(){
        contactBox.classList.add('translate-x-full','opacity-0');
        contactBox.classList.remove('translate-x-0','opacity-100');
        contactOverlay.classList.add('opacity-0','pointer-events-none');
        contactOverlay.classList.remove('opacity-100');
    }

    function toggleMobileMenu(){
        const isOpen = !mobileMenu.classList.contains('-translate-y-full');
        if(isOpen) {
            closeMobileMenu();
        } else {
            openMobileMenu();
        }
    }

    function openMobileMenu(){
        closeContact();
        mobileMenu.classList.remove('-translate-y-full', 'opacity-0', 'pointer-events-none');
        mobileMenu.classList.add('translate-y-0', 'opacity-100');
    }

    function closeMobileMenu(){
        mobileMenu.classList.add('-translate-y-full', 'opacity-0', 'pointer-events-none');
        mobileMenu.classList.remove('translate-y-0', 'opacity-100');
    }

    contactBtn.addEventListener('click', openContact);
    contactClose.addEventListener('click', closeContact);
    contactOverlay.addEventListener('click', closeContact);
    mobileMenuBtn.addEventListener('click', toggleMobileMenu);

    const scrollUpBtn = document.getElementById('scrollUpBtn');
    let btnTick = false;
    window.addEventListener('scroll', () => {
        if (!btnTick) {
            requestAnimationFrame(() => {
                const show = window.scrollY > 300;
                scrollUpBtn.classList.toggle('opacity-0', !show);
                scrollUpBtn.classList.toggle('translate-y-2', !show);
                scrollUpBtn.classList.toggle('scale-90', !show);
                scrollUpBtn.classList.toggle('opacity-100', show);
                scrollUpBtn.classList.toggle('translate-y-0', show);
                scrollUpBtn.classList.toggle('scale-100', show);
                scrollUpBtn.classList.toggle('pointer-events-none', !show);
                btnTick = false;
            });
            btnTick = true;
        }
    }, { passive: true });

    scrollUpBtn.addEventListener('click', () => {
        const start = window.scrollY;
        const duration = 600;
        const ease = t => t < 0.5 ? 4*t*t*t : 1 - Math.pow(-2*t+2, 3) / 2;
        const t0 = performance.now();
        function step(now) {
            const p = Math.min((now - t0) / duration, 1);
            const y = Math.round(start * (1 - ease(p)));
            window.scrollTo(0, y);
            if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    });

    // Navbar Animation on Scroll
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        if(window.scrollY > 50){
            navbar.classList.add('shadow-xl', 'bg-white');
        } else {
            navbar.classList.remove('shadow-xl');
        }
    });

    // Search Bar Toggle
    const searchToggle = document.getElementById('searchToggle');
    const searchBar = document.getElementById('searchBar');
    const navbarContent = document.getElementById('navbarContent');
    const searchCancel = document.getElementById('searchCancel');
    const searchInput = document.getElementById('searchInput');

    searchToggle.addEventListener('click', () => {
        navbarContent.classList.add('hidden');
        searchBar.classList.remove('hidden');
        searchInput.focus();
    });

    searchCancel.addEventListener('click', () => {
        searchBar.classList.add('hidden');
        navbarContent.classList.remove('hidden');
        searchInput.value = '';
    });

    searchInput.addEventListener('keypress', (event) => {
        if (event.key === 'Enter') {
            const query = searchInput.value.trim();
            if (query) {
                window.location.href = `/search?query=${encodeURIComponent(query)}`;
            }
        }
    });
</script>

