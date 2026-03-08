@extends('layouts.app')

@section('title', 'À propos - ALCOIL')

@section('content')
    <!-- Hero -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-[#0f2c4f] via-[#0f2c4f] to-[#0f2c4f]"></div>
        <img src="@webp('images/BG_usine.jpg')" alt="ALCOIL hero" class="absolute inset-0 w-full h-full object-cover opacity-20 select-none">
        <div class="relative max-w-7xl mx-auto px-4 py-20 text-white">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight">À propos d’ALCOIL</h1>
            <p class="mt-5 max-w-2xl text-sm sm:text-base md:text-lg text-slate-200">
                Nous concevons et fabriquons des solutions thermiques fiables pour l’industrie,
                avec une expertise reconnue et un accompagnement de bout en bout.
            </p>
            <div class="mt-8">
                <a href="{{ url('/contact') }}" class="inline-flex items-center gap-2 bg-secondary hover:bg-secondary_2 text-white font-semibold px-5 py-3 rounded transition-colors">
                    Nous contacter
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
        <div class="relative mx-auto h-1 w-24 bg-secondary rounded"></div>
    </section>

    <!-- Mission / Valeurs / Pourquoi -->
    <section class="py-14 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <style>
            .no-scrollbar{ -ms-overflow-style: none; scrollbar-width: none; }
            .no-scrollbar::-webkit-scrollbar{ display: none; }
            </style>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
                <!-- Mission -->
                <article class="group bg-white border border-slate-200 rounded-md p-6 shadow-sm transition-transform duration-300 ease-out motion-safe:hover:-translate-y-1 flex flex-col">
                    <div class="mb-4 text-secondary">
                        <!-- target icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="8"/>
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M12 2v4M12 18v4M2 12h4M18 12h4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-slate-900">Notre mission</h3>
                    <p class="mt-3 text-sm sm:text-base text-slate-600 flex-1">
                        Offrir des produits et services de haute performance pour le transfert thermique,
                        en garantissant fiabilité, sécurité et optimisation opérationnelle.
                    </p>
                    <div class="mt-6 h-1 bg-secondary rounded"></div>
                </article>

                <!-- Valeurs -->
                <article class="group bg-white border border-slate-200 rounded-md p-6 shadow-sm transition-transform duration-300 ease-out motion-safe:hover:-translate-y-1 flex flex-col">
                    <div class="mb-4 text-secondary">
                        <!-- shield-check icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l7 3v6c0 5.25-3.5 9.92-7 11-3.5-1.08-7-5.75-7-11V5l7-3z"/>
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-slate-900">Nos valeurs</h3>
                    <p class="mt-3 text-sm sm:text-base text-slate-600 flex-1">
                        Qualité, intégrité, respect des engagements et innovation continue au service de nos clients.
                    </p>
                    <div class="mt-6 h-1 bg-secondary rounded"></div>
                </article>

                <!-- Pourquoi nous choisir -->
                <article class="group bg-white border border-slate-200 rounded-md p-6 shadow-sm transition-transform duration-300 ease-out motion-safe:hover:-translate-y-1 flex flex-col sm:col-span-2 lg:col-span-1">
                    <div class="mb-4 text-secondary">           
                        <!-- spark icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2v4M12 18v4M2 12h4M18 12h4"/>
                            <path d="M5 5l3 3M16 16l3 3M5 19l3-3M16 8l3-3"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-slate-900">Pourquoi nous choisir</h3>
                    <p class="mt-3 text-sm sm:text-base text-slate-600 flex-1">
                        Expertise sectorielle, accompagnement personnalisé, délais maîtrisés et support après-vente réactif.
                    </p>
                    <div class="mt-6 h-1 bg-secondary rounded"></div>
                </article>
            </div>
        </div>
    </section>

    <!-- Historique -->
    <section class="py-14 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <!-- Texte historique -->
                <div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Historique</h2>
                    <div class="mt-4 h-1 w-20 bg-secondary rounded"></div>

                    <div class="mt-8 space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="h-2 w-2 rounded-full bg-secondary mt-2"></div>
                            <div>
                                <h3 class="font-semibold text-slate-900">Fondation</h3>
                                <p class="text-slate-600">Lancement de l’activité avec une vision claire du transfert de chaleur industriel.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="h-2 w-2 rounded-full bg-secondary mt-2"></div>
                            <div>
                                <h3 class="font-semibold text-slate-900">Croissance</h3>
                                <p class="text-slate-600">Développement de gammes produits, renforcement des équipes et process qualité.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="h-2 w-2 rounded-full bg-secondary mt-2"></div>
                            <div>
                                <h3 class="font-semibold text-slate-900">Présent</h3>
                                <p class="text-slate-600">Partenaire de confiance pour de nombreuses industries en Algérie et ailleurs.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Animation image -->
                <div class="relative w-full h-full flex items-center justify-center">
                    
                    <style>
                        @keyframes float {
                            0%   { transform: translateY(0px); }
                            50%  { transform: translateY(-8px); }
                            100% { transform: translateY(0px); }
                        }
                        .animate-float {
                            animation: float 4s ease-in-out infinite;
                        }
                    </style>
                    <div class="w-full h-auto animate-float">
                    <div class="relative w-full max-w-full h-auto rounded-lg shadow-lg overflow-hidden" >
                        <img src="@webp('images/BG_usine.jpg')" alt="Animation historique ALCOIL"
                             >
                        
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Notre équipe -->
    <section id="team" class="py-14 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-6">Notre équipe</h2>

            <div id="teamCarousel" class="relative">
                @php $teamMembers = [
                   
                    ['name'=>'BOUCHAKOUR ALI AMAR Nasreddine','role'=>'Gérant','img'=>'nacer.jpg'],
                    ['name'=>'BOUCHAKOUR ALI AMAR Miloud','role'=>'CO-Gérant','img'=>'miloud.jpg'],
                    ['name'=>'ELMAHI Fadia','role'=>'Responsable des Ressources Humaines et Moyens Généraux','img'=>'fadia.jpg','sex'=>'female'],
                    ['name'=>'NACER Mohammed El Amine','role'=>'IT & Marketing Manager','img'=>'amine.jpg'],
                     ['name'=>'GHALMI Youcef','role'=>'Responsable de Contrôle Qualité','img'=>'youcef.jpg'],
                    ['name'=>'ADNANE Mohammed Reda','role'=>'Responsable Maintenance','img'=>'reda.jpg'],
                    ['name'=>'SAHRAOUI Mouna','role'=>'Responsable Conception et Développement','img'=>'mouna.jpg','sex'=>'female'],
                    ['name'=>'BENKHELIFA Alaa Eddine','role'=>'Ingénieur de Méthode','img'=>'alaa.jpg'],
                    ['name'=>'BOUCHAKOUR ALI AMAR Ramzi Achraf','role'=>'Responsable Commercial','img'=>'ramzi.jpg'],
                    ['name'=>'BEMMOUSSAT Abdelilah','role'=>'Responsable de Production','img'=>'abdou.jpg'],
                    ['name'=>'BENDIDA DOUKARA Soumaya','role'=>'Responsable d\'approvisionnement','img'=>'soumaya.jpg','sex'=>'female'],
                    ['name'=>'LAMARI Abdelhakim','role'=>'Responsable Point de Vente','img'=>'hakim.jpg'],
                    ['name'=>'TAIBI Sid Ahmed','role'=>'Chef de Projet','img'=>'sid_ahmed.jpg'],
                    ['name'=>'CHAKIB Khelil','role'=>'Chef des Projets','img'=>'chakib.jpg'],
                    ['name'=>'BOULARAF Elyes','role'=>'Chargé Commercial','img'=>'ilyes.jpg'],
                ]; @endphp
                <div class="overflow-x-auto no-scrollbar snap-x snap-mandatory scroll-smooth">
                    @php $slides = array_chunk($teamMembers, 8); @endphp
                    <div id="teamTrack" class="flex gap-6">
                        @foreach($slides as $sIdx => $slide)
                        <div class="slide snap-start shrink-0 w-full">
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                                @foreach($slide as $idx => $member)
                                <article class="team-card group relative overflow-hidden rounded-2xl ring-1 ring-slate-200 bg-white shadow-lg hover:shadow-xl transition-all duration-400 will-change-transform motion-safe:hover:-translate-y-1 opacity-0 translate-y-2" data-index="{{ ($sIdx*8) + $idx + 1 }}">
                                    <div class="aspect-[4/5] relative">
                                        <img src="{{ (($member['sex'] ?? 'male') === 'female') ? asset('images/F_empty2.png') : asset('images/teams/'.($member['img'] ?? '')) }}" alt="{{ $member['name'] }}" class="w-full h-full object-cover object-top transition-transform duration-400 group-hover:scale-[1.04]" loading="lazy" decoding="async">
                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-slate-900/20 to-transparent pointer-events-none"></div>
                                    </div>
                                    <div class="absolute inset-x-0 bottom-0 p-4 pointer-events-none">
                                        <div class="rounded-xl px-3 py-2 flex items-center justify-between shadow-md transition-colors duration-300 backdrop-blur-md ring-0 group-hover:bg-white/90 group-hover:ring-1 group-hover:ring-white/60">
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold truncate text-white group-hover:text-slate-900">{{ $member['name'] }}</p>
                                                <p class="text-[11px] font-medium uppercase tracking-wide truncate text-white/80 group-hover:text-slate-600">{{ $member['role'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-center gap-4">
                    <button id="teamPrev" type="button" class="px-3 py-2 rounded-full bg-white shadow hover:bg-slate-100 border border-slate-200" aria-label="Précédent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button id="teamNext" type="button" class="px-3 py-2 rounded-full bg-white shadow hover:bg-slate-100 border border-slate-200" aria-label="Suivant">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <script>
    (function(){
        const SECTION = document.getElementById('team');
        const KEY = 'apropos_show_team';
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        function applyVisibility(flag){
            const visible = flag !== 'false';
            SECTION.classList.toggle('hidden', !visible);
            if(!visible) stopAuto();
        }
        function readPref(){
            let v = null;
            try { v = localStorage.getItem(KEY); } catch(e) {}
            if(v === null){
                const m = document.cookie.split('; ').find(s => s.startsWith(KEY+'='));
                if(m) v = m.split('=')[1];
            }
            return v ?? 'true';
        }
        applyVisibility(readPref());
        window.addEventListener('storage', (e)=>{
            if(e.key === KEY){ applyVisibility(e.newValue ?? 'true'); }
        });

        const carousel = document.getElementById('teamCarousel');
        const viewport = carousel.querySelector('.overflow-x-auto');
        const track = document.getElementById('teamTrack');
        const prev = document.getElementById('teamPrev');
        const next = document.getElementById('teamNext');
        const slides = track.querySelectorAll('.slide');

        const cards = track.querySelectorAll('.team-card');
        const io = new IntersectionObserver((entries)=>{
            entries.forEach(({isIntersecting, target})=>{
                if(!isIntersecting) return;
                const idx = parseInt(target.dataset.index||'0',10);
                target.classList.remove('opacity-0','translate-y-2');
                target.classList.add('opacity-100','translate-y-0');
                target.style.transitionDelay = (80 + (idx % 8) * 50) + 'ms';
                io.unobserve(target);
            });
        }, { threshold: 0.15 });
        cards.forEach(c => io.observe(c));

        let currentSlide = 0;
        function goTo(i){
            const behavior = reduceMotion ? 'auto' : 'smooth';
            currentSlide = Math.max(0, Math.min(i, slides.length - 1));
            slides[currentSlide].scrollIntoView({ behavior, inline: 'start', block: 'nearest' });
        }

        if(prev) prev.addEventListener('click', ()=> goTo(currentSlide - 1));
        if(next) next.addEventListener('click', ()=> goTo(currentSlide + 1));

        viewport.addEventListener('scroll', ()=>{
            let nearest = 0, min = Infinity;
            slides.forEach((el, idx)=>{
                const d = Math.abs(el.offsetLeft - viewport.scrollLeft);
                if(d < min){ min = d; nearest = idx; }
            });
            currentSlide = nearest;
        });

        let timer = null;
        function startAuto(){
            if(reduceMotion) return;
            stopAuto();
            timer = setInterval(()=>{
                currentSlide = (currentSlide + 1) % slides.length;
                goTo(currentSlide);
            }, 3000);
        }
        function stopAuto(){ if(timer){ clearInterval(timer); timer = null; } }

        let inView = false;
        if(carousel){
            carousel.addEventListener('mouseenter', stopAuto);
            carousel.addEventListener('mouseleave', ()=>{ if(inView) startAuto(); });
            carousel.addEventListener('focusin', stopAuto);
            carousel.addEventListener('focusout', ()=>{ if(inView) startAuto(); });
        }

        const sectionObserver = new IntersectionObserver((entries)=>{
            entries.forEach(({isIntersecting})=>{
                inView = isIntersecting;
                if(inView) startAuto(); else stopAuto();
            });
        }, { threshold: 0.25 });
        sectionObserver.observe(SECTION);
    })();
    </script>

    <!-- Notre présence / Map -->
    <section class="py-14 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Notre présence</h2>
                    <p class="mt-3 text-slate-600">
                        Basés à Sidi Bel Abbes, nous rayonnons sur toute l’Algérie et l’Afrique du Nord. Notre équipe commerciale et technique assure une présence terrain rapide pour études, mise en service et maintenance.
                    </p>
                    
                </div>
                <div class="rounded-lg overflow-hidden border border-slate-200 shadow-sm min-h-[240px] bg-slate-50 flex items-center justify-center">
                    <iframe
                    title="ALCOIL Map"
                    src="https://www.google.com/maps?q=35.1923527,-0.5910919(SARL%20OUEST%20%C3%89QUIPEMENTS)&z=17&output=embed"
                    width="100%" height="240" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                </div>
            </div>
        </div>
    </section>
@endsection