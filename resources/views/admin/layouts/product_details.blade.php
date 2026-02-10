@extends('layouts.app')

@section('content')
@php
// Les données du produit sont fournies par la route (base de données)
@endphp

@if(!$product)
<section class="container mx-auto px-6 py-20">
    <div class="rounded-2xl border border-red-200 bg-red-50 text-red-700 p-6">
        Produit introuvable.
    </div>
</section>
@else
<section class="relative overflow-hidden bg-primary">
    <div class="absolute inset-0">
        <img src="{{ $product['image'] }}" alt="" class="w-full h-full object-cover opacity-20">
        <div class="absolute inset-0 bg-primary/60"></div>
    </div>
    <div class="relative container mx-auto px-4 sm:px-6 pt-28 pb-12 sm:pb-16">
        <div class="flex items-center gap-2 text-white/80 text-[10px] sm:text-xs uppercase tracking-wider">
            <a href="/" class="hover:text-white transition">Accueil</a>
            <span class="opacity-40">/</span>
            <a href="/produits" class="hover:text-white transition">Produits</a>
            <span class="opacity-40">/</span>
            <span class="text-white font-medium truncate max-w-[150px] sm:max-w-none">{{ $product['name'] }}</span>
        </div>
        <h1 class="mt-4 text-3xl sm:text-4xl md:text-5xl font-extrabold text-white tracking-tight">{{ $product['name'] }}</h1>
        <div class="mt-4 flex flex-wrap items-center gap-3 sm:gap-4 text-white/90">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs sm:text-sm backdrop-blur-sm">{{ $product['category'] }}</span>
            <div class="flex items-center gap-1 text-secondary">
                @php $r = round($product['rating']); @endphp
                @for($i=1; $i<=5; $i++)
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $i <= $r ? 'fill-current' : 'text-white/20' }}" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                @endfor
                <span class="text-white/70 text-sm ml-1 font-medium">{{ number_format($product['rating'],1) }}</span>
            </div>
            <span class="inline-flex items-center gap-2 text-xs sm:text-sm">
                <span class="w-2 h-2 rounded-full {{ $product['available'] ? 'bg-emerald-400 animate-pulse' : 'bg-red-400' }}"></span>
                {{ $product['available'] ? 'En stock' : 'Indisponible' }}
            </span>
        </div>
    </div>
</section>

<section class="container mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
        <!-- Media Column -->
        <div class="lg:col-span-7 lg:sticky lg:top-28">
            <div class="rounded-3xl overflow-hidden border border-gray-100 bg-white shadow-xl group flex items-center justify-center h-[350px] sm:h-[500px] md:h-[600px]">
                <img id="mainImage" src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="max-w-full max-h-full object-contain transition-transform duration-700 group-hover:scale-105 p-4">
            </div>
            @if(!empty($product['images']))
            <div id="thumbs" class="mt-4 sm:mt-6 grid grid-cols-4 sm:grid-cols-5 gap-3 sm:gap-4">
                @foreach($product['images'] as $i => $src)
                    <button type="button" class="aspect-square rounded-2xl border-2 border-transparent bg-white shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md hover:scale-105 flex items-center justify-center p-1" data-src="{{ $src }}" data-idx="{{ $i }}">
                        <img src="{{ $src }}" alt="" class="max-w-full max-h-full object-contain">
                    </button>
                @endforeach
            </div>
            <script>
            (function(){
                var m=document.getElementById('mainImage'); var t=document.getElementById('thumbs'); if(!m||!t) return; var a=null;
                t.querySelectorAll('[data-src]').forEach(function(b){ b.addEventListener('click', function(){ var s=b.getAttribute('data-src'); if(s){ m.src=s; if(a) a.classList.remove('border-secondary'); a=b; b.classList.add('border-secondary'); } }); });
                var f=t.querySelector('[data-src]'); if(f) f.click();
            })();
            </script>
            @endif
        </div>

        <!-- Info Column -->
        <div class="lg:col-span-5 space-y-6 sm:space-y-8">
            <div class="rounded-3xl border border-gray-100 bg-white p-6 sm:p-8 shadow-xl">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="text-3xl sm:text-4xl font-extrabold text-secondary_2 tracking-tight">{{ number_format($product['price'], 0, ',', ' ') }} <span class="text-lg sm:text-xl font-bold">DA</span></div>
                    <button type="button" data-commander data-product-name="{{ $product['name'] }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-secondary hover:bg-secondary_2 text-white font-bold rounded-2xl px-8 py-3.5 transition-all shadow-lg shadow-secondary/20 active:scale-95">
                        
                        Commander
                    </button>
                </div>
                
                <div class="mt-8">
                    <h3 class="text-lg font-bold text-gray-900 border-l-4 border-secondary pl-3">Description</h3>
                    <p class="mt-4 text-gray-600 leading-relaxed">{{ $product['description'] }}</p>
                </div>

                <div class="mt-8">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Spécifications rapides</h3>
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-gray-50 border border-gray-100">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-secondary shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" stroke-width="2" stroke-linecap="round"/></svg>
                            </div>
                            <div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase">Catégorie</div>
                                <div class="text-sm font-semibold text-gray-800">{{ $product['category'] }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-gray-50 border border-gray-100">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-emerald-500 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round"/></svg>
                            </div>
                            <div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase">Disponibilité</div>
                                <div class="text-sm font-semibold text-gray-800">{{ $product['available'] ? 'En stock' : 'Indisponible' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Tabs Card -->
            <div class="rounded-3xl border border-gray-100 bg-white shadow-xl overflow-hidden flex flex-col max-h-[500px]">
                <div class="flex bg-gray-50/80 border-b border-gray-100 p-1.5 gap-1">
                    <button class="flex-1 px-4 py-2.5 text-sm font-bold rounded-2xl transition-all duration-300 bg-secondary_2 text-white shadow-md shadow-secondary_2/20" data-tab="features">Détails</button>
                    <button class="flex-1 px-4 py-2.5 text-sm font-bold rounded-2xl transition-all duration-300 text-gray-500 hover:bg-gray-100" data-tab="documents">Documents</button>
                    <button class="flex-1 px-4 py-2.5 text-sm font-bold rounded-2xl transition-all duration-300 text-gray-500 hover:bg-gray-100" data-tab="reviews">Avis</button>
                </div>
                
                <div class="p-6 overflow-y-auto custom-scrollbar">
                    <div id="tab-features" class="tab-content transition-all duration-300">
                        <p>{{ $product['description'] }}</p>
                        <div class="mt-6 flex flex-wrap gap-2">
                            @foreach($product['tags'] as $tag)
                                <span class="px-3 py-1 rounded-full bg-secondary/5 text-secondary text-xs font-bold border border-secondary/10">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div id="tab-documents" class="tab-content hidden transition-all duration-300">
                        @if(!empty($product['pdf']))
                            <div class="flex flex-col items-center py-6 text-center">
                                <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                <h4 class="font-bold text-gray-900">Fiche technique disponible</h4>
                                <p class="text-sm text-gray-500 mt-1 mb-6">Consultez les détails techniques complets.</p>
                                <a href="{{ $product['pdf'] }}" target="_blank" class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-bold rounded-2xl px-6 py-3 transition shadow-lg shadow-red-500/20">
                                    Télécharger PDF
                                </a>
                            </div>
                        @else
                            <div class="py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p>Aucun document technique disponible.</p>
                            </div>
                        @endif
                    </div>

                    <div id="tab-reviews" class="tab-content hidden transition-all duration-300">
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 border border-gray-100">
                            <div class="flex items-center gap-3">
                                <div id="avg-stars" class="flex items-center text-secondary">
                                    @php $r = round($product['rating']); @endphp
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $r ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    @endfor
                                </div>
                                <div class="text-sm font-bold text-gray-800">{{ number_format($product['rating'], 1) }}/5</div>
                            </div>
                            <div class="text-xs font-bold text-gray-400">{{ $product['ratingCount'] }} avis</div>
                        </div>

                        <div class="mt-8 text-center">
                            <h4 class="font-bold text-gray-900">Donnez votre avis</h4>
                            <p class="text-sm text-gray-500 mt-1">Votre opinion compte pour nous.</p>
                            <div class="mt-4 flex items-center justify-center gap-2" id="rate-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button type="button" class="group focus:outline-none" data-stars="{{ $i }}">
                                        <svg class="w-8 h-8 text-gray-200 transition-colors group-hover:text-secondary pointer-events-none" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    </button>
                                @endfor
                            </div>
                            <div id="rate-msg" class="mt-4 text-sm font-semibold text-secondary"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
                <script>
                (function(){
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
                                    b.classList.add('bg-secondary_2', 'text-white', 'shadow-md', 'shadow-secondary_2/20');
                                    b.classList.remove('text-gray-500', 'hover:bg-gray-100');
                                } else {
                                    b.classList.remove('bg-secondary_2', 'text-white', 'shadow-md', 'shadow-secondary_2/20');
                                    b.classList.add('text-gray-500', 'hover:bg-gray-100');
                                }
                            });
                            Object.keys(panels).forEach(function(k){
                                if (panels[k]) {
                                    panels[k].classList.toggle('hidden', k !== active);
                                }
                            });
                        });
                    });

                    // Voting logic
                    var el = document.getElementById('rate-stars');
                    if (!el) return;
                    var msg = document.getElementById('rate-msg');
                    var avgStarsEl = document.getElementById('avg-stars');
                    var summaryEl = document.querySelector('#tab-reviews .text-gray-600.text-sm');
                    var url = "{{ route('produit.rate', ['id' => $product['id']]) }}";
                    var CSRF = "{{ csrf_token() }}";
                    var voted = false;
                    el.querySelectorAll('[data-stars]').forEach(function(btn){
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
                            }).then(function(r){ return r.json(); }).then(function(data){
                                voted = true;
                                if (data && data.ok) {
                                    msg.textContent = 'Merci pour votre vote.';
                                    if (summaryEl) summaryEl.textContent = data.count + ' avis';
                                    if (avgStarsEl) {
                                        var r = Math.round(data.rating);
                                        avgStarsEl.innerHTML = Array.from({length:5}, function(_,i){
                                            var active = (i+1) <= r;
                                            return '<svg class="w-5 h-5 ' + (active ? 'fill-current' : 'text-gray-300') + '" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>';
                                        }).join('');
                                    }
                                    var ratingValueEl = document.querySelector('#tab-reviews .text-sm.font-bold.text-gray-800');
                                    if (ratingValueEl) ratingValueEl.textContent = Number(data.rating).toFixed(1) + '/5';
                                } else {
                                    msg.textContent = (data && data.message) || 'Vous avez déjà voté pour ce produit.';
                                }
                            }).catch(function(){
                                msg.textContent = 'Erreur lors de l\'envoi du vote.';
                            });
                        });
                    });
                })();
                </script>
            </div>
        </div>
    </div>
</section>
@endif

@include('layouts.coming_soon')
@endsection