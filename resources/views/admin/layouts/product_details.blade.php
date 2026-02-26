@extends('layouts.app')

@section('content')
@php
// Les données du produit sont fournies par la route (base de données)
@endphp

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeInUp {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>

@if(!$product)
<section class="container mx-auto px-6 py-20 animate-fadeInUp">
    <div class="rounded-3xl border border-red-100 bg-red-50/50 text-red-700 p-8 text-center backdrop-blur-sm">
        <svg class="w-16 h-16 mx-auto mb-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <h2 class="text-2xl font-bold mb-2">Produit introuvable</h2>
        <p class="text-red-600/70">Le produit que vous recherchez n'existe plus ou a été déplacé.</p>
        <a href="/produits" class="mt-6 inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-2xl font-bold hover:bg-red-700 transition-all shadow-lg shadow-red-600/20">Retour aux produits</a>
    </div>
</section>
@else
<!-- Hero Section -->
<section class="relative min-h-[45vh] flex items-end overflow-hidden bg-primary">
    <div class="absolute inset-0">
        <img src="{{ $product['image'] }}" alt="" class="w-full h-full object-cover opacity-30 scale-105 transition-transform duration-1000">
        <div class="absolute inset-0 bg-gradient-to-t from-primary via-primary/80 to-transparent"></div>
    </div>
    
    <div class="relative container mx-auto px-4 sm:px-6 pb-12 sm:pb-20">
        <div class="animate-fadeInUp">
            <nav class="flex items-center gap-2 text-white/60 text-[10px] sm:text-xs uppercase tracking-[0.2em] font-bold mb-6">
                <a href="/" class="hover:text-secondary transition-colors">Accueil</a>
                <span class="opacity-30">/</span>
                <a href="/produits" class="hover:text-secondary transition-colors">Produits</a>
                <span class="opacity-30">/</span>
                <span class="text-white/90 truncate max-w-[150px] sm:max-w-none">{{ $product['name'] }}</span>
            </nav>
            
            <h1 class="text-4xl sm:text-5xl md:text-7xl font-black text-white tracking-tighter leading-none">
                {{ $product['name'] }}
            </h1>
            
            <div class="mt-8 flex flex-wrap items-center gap-4">
                <span class="px-4 py-1.5 rounded-full bg-secondary/20 border border-secondary/30 text-secondary text-xs sm:text-sm font-bold backdrop-blur-md">
                    {{ $product['category'] }}
                </span>
                
                <div class="flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-md">
                    <div class="flex items-center gap-0.5 text-secondary">
                        @php $r = round($product['rating']); @endphp
                        @for($i=1; $i<=5; $i++)
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 {{ $i <= $r ? 'fill-current' : 'text-white/20' }}" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <span class="text-white font-bold text-xs sm:text-sm">{{ number_format($product['rating'],1) }}</span>
                    <span class="text-white/40 text-[10px] font-medium ml-1">({{ $product['ratingCount'] }} avis)</span>
                </div>

                <span class="flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-md text-xs sm:text-sm">
                    <span class="w-2 h-2 rounded-full {{ $product['available'] ? 'bg-emerald-400 animate-pulse shadow-[0_0_8px_rgba(52,211,153,0.6)]' : 'bg-red-400' }}"></span>
                    <span class="text-white/90 font-bold uppercase tracking-wider">{{ $product['available'] ? 'En stock' : 'Indisponible' }}</span>
                </span>
            </div>
        </div>
    </div>
</section>

<section class="container mx-auto px-4 sm:px-6 py-12 sm:py-20 -mt-10 relative z-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-16 items-start">
        
        <!-- Gallery Column -->
        <div class="lg:col-span-7 space-y-6 animate-fadeInUp delay-100">
            <div class="relative aspect-square sm:aspect-video lg:aspect-square rounded-[2rem] overflow-hidden bg-white shadow-2xl group border border-gray-100">
                <div class="absolute inset-0 bg-gradient-to-tr from-gray-50 to-white -z-10"></div>
                <img id="mainImage" src="{{ $product['image'] }}" alt="{{ $product['name'] }}" 
                     class="w-full h-full object-contain p-8 sm:p-12 transition-all duration-700 group-hover:scale-105">
                
                <!-- Overlay details -->
               
            </div>

            @if(!empty($product['images']))
            <div id="thumbs" class="grid grid-cols-4 sm:grid-cols-5 gap-4">
                @foreach($product['images'] as $i => $src)
                    <button type="button" 
                            class="group relative aspect-square rounded-2xl bg-white border-2 {{ $i === 0 ? 'border-secondary' : 'border-gray-50' }} shadow-sm overflow-hidden transition-all duration-300 hover:shadow-xl hover:scale-[1.05] active:scale-95 flex items-center justify-center p-2" 
                            data-src="{{ $src }}" 
                            data-idx="{{ $i }}">
                        <img src="{{ $src }}" alt="" class="max-w-full max-h-full object-contain transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-secondary/0 group-hover:bg-secondary/5 transition-colors"></div>
                    </button>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Details Column -->
        <div class="lg:col-span-5 space-y-8 animate-fadeInUp delay-200">
            <div class="glass-card rounded-[2.5rem] p-8 sm:p-10 shadow-2xl shadow-primary/5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-10">
                    <div class="space-y-1">
                        <div class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Prix unitaire</div>
                        <div class="text-4xl sm:text-5xl font-black text-primary tracking-tight">
                            {{ number_format($product['price'], 0, ',', ' ') }}
                            <span class="text-xl font-bold text-secondary ml-1">DA</span>
                        </div>
                    </div>
                    
                    <button type="button" data-commander data-product-name="{{ $product['name'] }}" 
                            class="relative group w-full sm:w-auto inline-flex items-center justify-center gap-3 bg-secondary hover:bg-secondary_2 text-white font-black rounded-2xl px-10 py-5 transition-all shadow-xl shadow-secondary/25 hover:shadow-secondary/40 active:scale-95 overflow-hidden">
                        <span class="relative z-10 uppercase tracking-widest text-sm">Commander</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em]">Description</h3>
                    <p class="text-gray-600 leading-relaxed text-lg font-medium">
                        {{ $product['description'] }}
                    </p>
                </div>

                <div class="mt-12 grid grid-cols-2 gap-4">
                    <div class="p-5 rounded-3xl bg-gray-50/50 border border-gray-100 hover:border-secondary/20 transition-colors group">
                        <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-secondary shadow-sm mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" stroke-width="2.5" stroke-linecap="round"/></svg>
                        </div>
                        <div class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Catégorie</div>
                        <div class="text-sm font-black text-primary">{{ $product['category'] }}</div>
                    </div>
                    <div class="p-5 rounded-3xl bg-gray-50/50 border border-gray-100 hover:border-secondary/20 transition-colors group">
                        <div class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-emerald-500 shadow-sm mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2.5" stroke-linecap="round"/></svg>
                        </div>
                        <div class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Stock</div>
                        <div class="text-sm font-black text-primary">{{ $product['available'] ? 'Disponible' : 'Indisponible' }}</div>
                    </div>
                </div>
            </div>

            <!-- Tabs Section -->
            <div class="glass-card rounded-[2.5rem] shadow-2xl shadow-primary/5 overflow-hidden flex flex-col max-h-[600px] animate-fadeInUp delay-300">
                <div class="flex bg-gray-50/50 border-b border-gray-100 p-2 gap-2">
                    <button class="flex-1 px-6 py-4 text-xs font-black uppercase tracking-widest rounded-2xl transition-all duration-500 bg-secondary text-white shadow-lg shadow-secondary/20" data-tab="features">Détails</button>
                    <button class="flex-1 px-6 py-4 text-xs font-black uppercase tracking-widest rounded-2xl transition-all duration-500 text-gray-400 hover:bg-white hover:text-primary" data-tab="documents">Fiches</button>
                    <button class="flex-1 px-6 py-4 text-xs font-black uppercase tracking-widest rounded-2xl transition-all duration-500 text-gray-400 hover:bg-white hover:text-primary" data-tab="reviews">Avis</button>
                </div>
                
                <div class="p-8 overflow-y-auto custom-scrollbar">
                    <div id="tab-features" class="tab-content transition-all duration-500">
                        <div class="prose prose-sm prose-slate max-w-none">
                            <p class="text-gray-600 leading-relaxed font-medium">{{ $product['description'] }}</p>
                        </div>
                        <div class="mt-8 flex flex-wrap gap-2">
                            @foreach($product['tags'] as $tag)
                                <span class="px-4 py-1.5 rounded-full bg-primary/5 text-primary text-[10px] font-black uppercase tracking-widest border border-primary/10 hover:bg-primary/10 transition-colors">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div id="tab-documents" class="tab-content hidden transition-all duration-500 opacity-0 transform translate-y-4">
                        @if(!empty($product['pdf']))
                            <div class="flex flex-col items-center py-8 text-center">
                                <div class="w-20 h-20 rounded-3xl bg-red-50 text-red-500 flex items-center justify-center mb-6 shadow-sm border border-red-100">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                <h4 class="text-xl font-black text-primary tracking-tight">Fiche Technique</h4>
                                <p class="text-sm text-gray-500 mt-2 mb-8 font-medium">Consultez les spécifications techniques complètes en PDF.</p>
                                <a href="{{ $product['pdf'] }}" target="_blank" 
                                   class="inline-flex items-center gap-3 bg-red-500 hover:bg-red-600 text-white font-black rounded-2xl px-8 py-4 transition-all shadow-xl shadow-red-500/25 active:scale-95">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Télécharger le PDF
                                </a>
                            </div>
                        @else
                            <div class="py-16 text-center">
                                <div class="w-16 h-16 mx-auto mb-6 bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Aucun document technique</p>
                            </div>
                        @endif
                    </div>

                    <div id="tab-reviews" class="tab-content hidden transition-all duration-500 opacity-0 transform translate-y-4">
                        <div class="p-6 rounded-3xl bg-gray-50/50 border border-gray-100 flex items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Note moyenne</div>
                                <div class="flex items-center gap-3">
                                    <div id="avg-stars" class="flex items-center text-secondary">
                                        @php $r = round($product['rating']); @endphp
                                        @for($i=1; $i<=5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $r ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                        @endfor
                                    </div>
                                    <div class="text-xl font-black text-primary">{{ number_format($product['rating'], 1) }}<span class="text-gray-300 font-bold text-sm">/5</span></div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total</div>
                                <div class="text-sm font-black text-primary">{{ $product['ratingCount'] }} avis</div>
                            </div>
                        </div>

                        <div class="mt-10 text-center">
                            <h4 class="text-lg font-black text-primary tracking-tight">Partagez votre expérience</h4>
                            <p class="text-sm text-gray-500 mt-2 mb-6 font-medium">Votre avis aide les autres clients dans leur choix.</p>
                            <div class="flex items-center justify-center gap-2" id="rate-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" class="group focus:outline-none" data-stars="{{ $i }}">
                                        <svg class="w-10 h-10 text-gray-200 transition-all group-hover:text-secondary group-hover:scale-110 pointer-events-none" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    </button>
                                @endfor
                            </div>
                            <div id="rate-msg" class="mt-6 text-sm font-black text-secondary tracking-wide uppercase h-6"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
(function(){
    // Image Gallery Logic
    var m = document.getElementById('mainImage');
    var t = document.getElementById('thumbs');
    if(m && t) {
        var activeThumb = t.querySelector('.border-secondary');
        t.querySelectorAll('[data-src]').forEach(function(btn){
            btn.addEventListener('click', function(){
                var src = btn.getAttribute('data-src');
                if(!src || btn === activeThumb) return;
                
                // Animate transition
                m.style.opacity = '0';
                m.style.transform = 'scale(0.95)';
                
                setTimeout(function(){
                    m.src = src;
                    m.style.opacity = '1';
                    m.style.transform = 'scale(1)';
                }, 200);

                if(activeThumb) {
                    activeThumb.classList.remove('border-secondary', 'shadow-xl', 'scale-[1.05]');
                    activeThumb.classList.add('border-gray-50');
                }
                btn.classList.remove('border-gray-50');
                btn.classList.add('border-secondary', 'shadow-xl', 'scale-[1.05]');
                activeThumb = btn;
            });
        });
    }

    // Tabs Logic
    var buttons = document.querySelectorAll('[data-tab]');
    var panels = {
        features: document.getElementById('tab-features'),
        documents: document.getElementById('tab-documents'),
        reviews: document.getElementById('tab-reviews')
    };

    buttons.forEach(function(btn){
        btn.addEventListener('click', function(){
            var active = btn.getAttribute('data-tab');
            
            buttons.forEach(function(b){
                var isActive = b === btn;
                if(isActive) {
                    b.classList.add('bg-secondary', 'text-white', 'shadow-lg', 'shadow-secondary/20');
                    b.classList.remove('text-gray-400', 'hover:bg-white', 'hover:text-primary');
                } else {
                    b.classList.remove('bg-secondary', 'text-white', 'shadow-lg', 'shadow-secondary/20');
                    b.classList.add('text-gray-400', 'hover:bg-white', 'hover:text-primary');
                }
            });

            Object.keys(panels).forEach(function(k){
                var p = panels[k];
                if (!p) return;
                if(k === active) {
                    p.classList.remove('hidden');
                    setTimeout(function(){
                        p.classList.remove('opacity-0', 'translate-y-4');
                    }, 50);
                } else {
                    p.classList.add('hidden', 'opacity-0', 'translate-y-4');
                }
            });
        });
    });

    // Voting Logic
    var rateEl = document.getElementById('rate-stars');
    if (rateEl) {
        var msg = document.getElementById('rate-msg');
        var avgStarsEl = document.getElementById('avg-stars');
        var url = "{{ route('produit.rate', ['id' => $product['id']]) }}";
        var CSRF = "{{ csrf_token() }}";
        var voted = false;

        rateEl.querySelectorAll('[data-stars]').forEach(function(btn){
            btn.addEventListener('mouseenter', function(){
                if(voted) return;
                var val = parseInt(btn.getAttribute('data-stars'));
                rateEl.querySelectorAll('[data-stars]').forEach(function(s, i){
                    s.querySelector('svg').style.color = (i < val) ? '#56b447' : '#e2e8f0';
                });
            });
            
            btn.addEventListener('mouseleave', function(){
                if(voted) return;
                rateEl.querySelectorAll('[data-stars] svg').forEach(function(svg){
                    svg.style.color = '#e2e8f0';
                });
            });

            btn.addEventListener('click', function(){
                if (voted) return;
                var stars = parseInt(btn.getAttribute('data-stars'), 10);
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ stars: stars })
                })
                .then(function(r){ return r.json(); })
                .then(function(data){
                    voted = true;
                    if (data && data.ok) {
                        msg.textContent = 'MERCI POUR VOTRE VOTE !';
                        msg.classList.add('text-secondary');
                        if (avgStarsEl) {
                            var r = Math.round(data.rating);
                            avgStarsEl.innerHTML = Array.from({length:5}, function(_,i){
                                var active = (i+1) <= r;
                                return '<svg class="w-5 h-5 ' + (active ? 'fill-current' : 'text-gray-200') + '" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>';
                            }).join('');
                        }
                    } else {
                        msg.textContent = (data && data.message) || 'DÉJÀ VOTÉ !';
                        msg.classList.add('text-amber-500');
                    }
                })
                .catch(function(){
                    msg.textContent = 'ERREUR LORS DU VOTE';
                    msg.classList.add('text-red-500');
                });
            });
        });
    }
})();
</script>
            </div>
        </div>
    </div>
</section>
@endif

@include('layouts.coming_soon')
@endsection