@extends('layouts.app')

@section('content')

<!-- HERO SECTION -->
<section class="relative bg-primary text-white py-32 overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0">
        <img src="{{ asset('images/bg-hero.jpg') }}" alt="Background" class="w-full h-full object-cover opacity-30">
    </div>

    <!-- Content -->
    <div class="relative max-w-7xl mx-auto px-6 py-10">
        <div class="animate-fade-left">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-6 text-left leading-tight">
                Solutions Professionnelles d’Échangeurs Thermiques
            </h1>
            <p class="text-lg sm:text-xl md:text-2xl opacity-90 mb-8 text-left max-w-2xl">
                Qualité, précision et performance pour les industries modernes.
            </p>

            <!-- Buttons -->
            <div class="flex gap-4 mt-6 flex-wrap justify-start">
                <!-- Voir nos produits (not rounded) -->
                <a href="/produits" class="bg-secondary text-white px-6 py-3 text-lg font-bold hover:bg-secondary_2 rounded transition">
                    Voir nos produits
                </a>

                <!-- Contact Button -->
                <a href="/contact" class="bg-white text-primary px-6 py-3 text-lg font-bold  hover:bg-primary hover:text-white rounded transition">
                    Contact
                </a>
            </div>
        </div>
    </div>
</section>



<!-- Tailwind Animation (fade + slide from left) -->
<style>
    @keyframes fade-left {
        0% {
            opacity: 0;
            transform: translateX(-50px);
        }
        100% {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-fade-left {
        animation: fade-left 1.5s ease-out forwards;
    }
</style>



<!-- STATS SECTION -->
<section id="stats" class="bg-primary text-white py-20 mt-12">
  <div class="max-w-7xl mx-auto px-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
      <!-- Stat 1 -->
      <div class="stat-item flex items-center gap-6">
        <div class="relative w-40 h-40">
          <svg class="w-40 h-40" viewBox="0 0 120 120">
            <!-- Track -->
            <circle cx="60" cy="60" r="54" stroke="rgba(255,255,255,0.25)" stroke-width="5" fill="none"></circle>
            <!-- Progress -->
            <circle class="progress-circle text-secondary" cx="60" cy="60" r="54" stroke="currentColor" stroke-width="5" fill="none" stroke-linecap="round" style="stroke-dasharray: 339.292; stroke-dashoffset: 339.292; transition: stroke-dashoffset 1.5s ease;"></circle>
          </svg>
          <div class="absolute inset-0 flex items-center justify-center">
            <span class="count text-4xl font-bold">+0</span>
          </div>
        </div>
        <div>
          <p class="text-sm uppercase font-semibold mb-1">Années d'expérience</p>
        </div>
        <span class="sr-only" data-progress="90" data-count="25"></span>
      </div>

      <!-- Stat 2 -->
      <div class="stat-item flex items-center gap-6">
        <div class="relative w-40 h-40">
          <svg class="w-40 h-40" viewBox="0 0 120 120">
            <circle cx="60" cy="60" r="54" stroke="rgba(255,255,255,0.25)" stroke-width="5" fill="none"></circle>
            <circle class="progress-circle text-secondary" cx="60" cy="60" r="54" stroke="currentColor" stroke-width="5" fill="none" stroke-linecap="round" style="stroke-dasharray: 339.292; stroke-dashoffset: 339.292; transition: stroke-dashoffset 1.5s ease;"></circle>
          </svg>
          <div class="absolute inset-0 flex items-center justify-center">
            <span class="count text-4xl font-bold">+0</span>
          </div>
        </div>
        <div>
          <p class="text-sm uppercase font-semibold mb-1">Produits variés</p>
          
        </div>
        <span class="sr-only" data-progress="95" data-count="500"></span>
      </div>

      <!-- Stat 3 -->
      <div class="stat-item flex items-center gap-6">
        <div class="relative w-40 h-40">
          <svg class="w-40 h-40" viewBox="0 0 120 120">
            <circle cx="60" cy="60" r="54" stroke="rgba(255,255,255,0.25)" stroke-width="5" fill="none"></circle>
            <circle class="progress-circle text-secondary" cx="60" cy="60" r="54" stroke="currentColor" stroke-width="5" fill="none" stroke-linecap="round" style="stroke-dasharray: 339.292; stroke-dashoffset: 339.292; transition: stroke-dashoffset 1.5s ease;"></circle>
          </svg>
          <div class="absolute inset-0 flex items-center justify-center">
            <span class="count text-4xl font-bold">+0</span>
          </div>
        </div>
        <div>
          <p class="text-sm uppercase font-semibold mb-1">Clients satisfaits</p>
          
        </div>
        <span class="sr-only" data-progress="100" data-count="1000"></span>
      </div>
    </div>
  </div>
</section>

<script>
  (function() {
    const ITEMS = document.querySelectorAll('.stat-item');
    const CIRCUMFERENCE = 2 * Math.PI * 54; // r = 54

    function setProgress(el, percent) {
      const circle = el.querySelector('.progress-circle');
      if (!circle) return;
      const offset = CIRCUMFERENCE - (percent / 100) * CIRCUMFERENCE;
      circle.style.strokeDasharray = CIRCUMFERENCE;
      circle.style.strokeDashoffset = offset;
    }

    function animateCount(countEl, target) {
      const duration = 1200;
      const start = performance.now();
      function step(now) {
        const progress = Math.min((now - start) / duration, 1);
        const value = Math.floor(progress * target);
        countEl.textContent = '+' +value;
        if (progress < 1) requestAnimationFrame(step);
      }
      requestAnimationFrame(step);
    }

    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        const wrapper = entry.target;
        const meta = wrapper.querySelector('span.sr-only');
        if (!meta) return;
        const targetPercent = parseInt(meta.getAttribute('data-progress'), 10) || 100;
        const targetCount = parseInt(meta.getAttribute('data-count'), 10) || 0;
        const countEl = wrapper.querySelector('.count');

        if (entry.isIntersecting) {
          setProgress(wrapper, targetPercent);
          if (countEl) animateCount(countEl, targetCount);
        } else {
          // Reset when out of view to re-animate on next scroll
          setProgress(wrapper, 0);
          if (countEl) countEl.textContent = '0+';
        }
      });
    }, { threshold: 0.4 });

    ITEMS.forEach(item => io.observe(item));
  })();
</script>

<!-- SERVICES (Modern Grid Like Reference) -->
<section id="services-modern" class="py-16">
    <style>
        /* Blueprint-like pattern tile (no external image needed) */
        .service-pattern {
            background-color: #143a66;
            background-image:
                radial-gradient(1100px 500px at 0% 0%, rgba(255,255,255,0.06) 0%, rgba(255,255,255,0) 60%),
                radial-gradient(800px 600px at 100% 100%, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0) 65%),
                repeating-linear-gradient(135deg, rgba(255,255,255,0.10) 0 1px, transparent 1px 14px),
                repeating-linear-gradient(45deg, rgba(255,255,255,0.09) 0 1px, transparent 1px 18px);
        }
        /* thin orange accent bar for cards */
        .card-accent {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 4px; background: #34682b;
        }
    </style>

    <div class="max-w-7xl mx-auto px-4">
        <!-- Title -->
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900">Services</h2>
            <div class="mt-3 mx-auto h-1 w-24 bg-secondary rounded"></div>
        </div>

        <!-- Grid: 3 columns (2 rows) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">

            <!-- Pattern Tile (same height) -->
            <div class="relative rounded-md overflow-hidden shadow-sm border border-slate-200 h-[240px] sm:h-[280px] md:h-[320px]">
                <img src="{{ asset('images/1.png') }}" alt="Service image 1" class="absolute inset-0 h-full w-full object-cover select-none" loading="lazy" decoding="async" width="800" height="600" />
            </div>

            <!-- Card: Échangeurs Industriels (same height, gentle hover) -->
            <article class="group relative bg-white rounded-md border border-slate-200 p-4 sm:p-6 shadow-sm h-[240px] sm:h-[280px] md:h-[320px] flex flex-col items-center justify-center transition-transform duration-300 ease-out motion-safe:hover:-translate-y-1">
                <div class="mx-auto mb-4 flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center text-secondary">
                    <!-- paint roller icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <rect x="5" y="5" width="14" height="14" rx="2"/>
                        <path d="M8 7v10"/>
                        <path d="M12 7v10"/>
                        <path d="M16 7v10"/>
                        <path d="M3 12h2"/>
                        <path d="M21 12h-2"/>
                    </svg>
                </div>
                <div class="text-center px-2">
                    <h3 class="mt-2 text-lg sm:text-xl font-semibold text-slate-900">Échangeurs Industriels</h3>
                    <p class="mt-2 sm:mt-3 text-sm sm:text-base text-slate-600">Performance optimale pour applications industrielles lourdes.</p>
                </div>
                <div class="card-accent"></div>
            </article>

            <!-- Image Tile (same height, no hover animation) -->
            <div class="relative rounded-md overflow-hidden border border-slate-200 shadow-sm h-[240px] sm:h-[280px] md:h-[320px]">
                <img src="{{ asset('images/2.png') }}" alt="Service image 1" class="absolute inset-0 h-full w-full object-cover select-none" loading="lazy" decoding="async" width="800" height="600" />
            </div>

            <!-- Card: Solutions de Refroidissement (same height) -->
            <article class="group relative bg-white rounded-md border border-slate-200 p-4 sm:p-6 shadow-sm h-[240px] sm:h-[280px] md:h-[320px] flex flex-col items-center justify-center transition-transform duration-300 ease-out motion-safe:hover:-translate-y-1">
                <div class="mx-auto mb-4 flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center text-secondary">
                    <!-- compass icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M12 2v20"/>
                        <path d="M2 12h20"/>
                        <path d="M4.5 4.5l15 15"/>
                        <path d="M19.5 4.5l-15 15"/>
                    </svg>
                </div>
                <div class="text-center px-2">
                    <h3 class="mt-2 text-lg sm:text-xl font-semibold text-slate-900">Solutions de Refroidissement</h3>
                    <p class="mt-2 sm:mt-3 text-sm sm:text-base text-slate-600">Fiabilité élevée pour un refroidissement constant.</p>
                </div>
                <div class="card-accent"></div>
            </article>

            <!-- Image Tile (same height, no hover animation) -->
            <div class="relative rounded-md overflow-hidden border border-slate-200 shadow-sm h-[240px] sm:h-[280px] md:h-[320px]">
                <img src="{{ asset('images/3.jpg') }}" alt="Service image 2" class="absolute inset-0 h-full w-full object-cover select-none" loading="lazy" decoding="async" width="800" height="600" />
            </div>

            <!-- Card: Fabrication sur Mesure (same height) -->
            <article class="group relative bg-white rounded-md border border-slate-200 p-6 shadow-sm h-[280px] md:h-[320px] flex flex-col items-center justify-center transition-transform duration-300 ease-out motion-safe:hover:-translate-y-1">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center text-secondary">
                    <!-- toolbox icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <rect x="3" y="11" width="18" height="9" rx="1"/>
                        <path d="M3 11l5-3v3l5-3v3l5-3v3"/>
                        <rect x="18" y="6" width="3" height="5"/>
                        <rect x="6" y="14" width="2" height="2"/>
                        <rect x="10" y="14" width="2" height="2"/>
                        <rect x="14" y="14" width="2" height="2"/>
                    </svg>
                </div>
                <div class="text-center">
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">Fabrication sur Mesure</h3>
                    <p class="mt-3 text-slate-600">Produits adaptés à vos besoins techniques spécifiques.</p>
                </div>
                <div class="card-accent"></div>
            </article>

        </div>
    </div>
</section>
<!-- /SERVICES (Modern Grid) -->

<!-- PRODUITS (Derniers 3) -->
<section id="products" class="py-16 products-section" style="position:relative; overflow:hidden;">
    <style>
        /* Dark blue background with subtle curves and orange accents */
        .products-section {
            background-color: #0f2b50;
            background-image:
                radial-gradient(900px 480px at -10% 30%, rgba(255,255,255,0.06) 0%, rgba(255,255,255,0) 60%),
                radial-gradient(800px 500px at 110% 70%, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0) 65%),
                conic-gradient(from 210deg at 18% 72%, rgba(255,255,255,0.11), rgba(255,255,255,0) 28%, rgba(255,255,255,0) 54%, rgba(255,255,255,0.11) 76%, rgba(255,255,255,0)),
                repeating-linear-gradient(140deg, rgba(255,255,255,0.07) 0 1px, transparent 1px 18px);
            background-blend-mode: soft-light, normal, overlay, normal;
        }
        .product-accent { height: 3px; background:#34682b; }
    </style>



    <div class="max-w-7xl mx-auto px-4">
        <!-- Header with CTA -->
        <div class="flex items-end justify-between mb-10">
            <div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white">Produits</h2>
                <div class="mt-3 h-1 w-24 bg-secondary rounded"></div>
            </div>
            <a href="/produits" class="inline-flex items-center gap-2 text-white bg-secondary hover:bg-secondary_2 px-4 py-2 rounded-md shadow transition-colors duration-200" aria-label="Voir tous les produits">
                <span class="hidden sm:inline">Plus de produits</span>
                <!-- arrow icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="M13 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <!-- Grid cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($products as $p)
                <article class="group relative rounded-md overflow-hidden bg-white shadow-sm border border-slate-200 transition-transform duration-300 ease-out motion-safe:hover:-translate-y-1">
                    <a href="{{ $p['url'] }}" class="block h-full flex flex-col">
                        <!-- image -->
                        <div class="relative h-44 md:h-52 overflow-hidden flex items-center justify-center">
                            <img src="{{ $p['img'] ? $p['img'] : asset('images/no_image.png') }}" alt="{{ $p['name'] }}" class="w-full h-full object-cover select-none {{ $p['img'] ? '' : 'max-h-24 max-w-24 object-contain' }}" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>

                        <!-- content -->
                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-lg font-semibold text-slate-900">{{ $p['name'] }}</h3>
                            <p class="mt-2 text-slate-600 flex-grow">{{ $p['desc'] }}</p>
                            <div class="mt-4 flex items-center gap-3 justify-start">
                                <span class="text-secondary inline-flex items-center gap-1">
                                    Lire plus
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 5v14" />
                                        <path d="M5 12h14" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                    <div class="card-accent"></div>
                </article>
            @endforeach
        </div>
    </div>
</section>
<!-- /PRODUITS -->



<!-- Partners / Clients Logos -->
<section id="partners" class="partners-section" style="position:relative; overflow:hidden; padding:40px 0; margin-bottom: 60px;">
   
    <!-- Title -->
        <div class="text-center mb-10">
            <h2 class="text-dark text-3xl md:text-4xl font-extrabold text-slate-900">Nos partenaires et clients</h2>
            <div class="mt-3 mx-auto h-1 w-24 bg-secondary rounded"></div>
        </div>
    

        @if(!empty($partners))
        <div class="partners-grid" aria-label="Partner & Client Logos">
            @foreach ($partners as $idx => $item)
                @php
                    $logoPath = is_array($item) ? ($item['logo'] ?? '') : $item;
                    $website  = is_array($item) ? ($item['siteweb'] ?? null) : null;
                    $src = is_string($logoPath) && \Illuminate\Support\Str::startsWith($logoPath, ['http://','https://']) ? $logoPath : asset($logoPath);
                @endphp
                <div class="logo-tile">
                    @if($website)
                        <a href="{{ $website }}" target="_blank" rel="noopener noreferrer" class="block w-full h-full flex items-center justify-center">
                            <img class="logo-img" src="{{ $src }}" alt="Logo client {{ $idx + 1 }}">
                        </a>
                    @else
                        <img class="logo-img" src="{{ $src }}" alt="Logo client {{ $idx + 1 }}">
                    @endif
                </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
<!-- /Partners / Clients Logos -->




@endsection
