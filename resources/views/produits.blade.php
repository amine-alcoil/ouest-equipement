@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white text-gray-800">

    <!-- Hero -->
    <section class="relative overflow-hidden bg-primary">
        <!-- Background image with overlay -->
        <div class="absolute inset-0">
            <img src="/images/3.jpg" alt="" class="w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-primary/60"></div>
        </div>

        <!-- Decorative gradients -->
        <div class="absolute inset-0 pointer-events-none"
             style="background:
                    radial-gradient(1000px 400px at -10% 0%, rgba(255,153,0,0.05), transparent 60%),
                    radial-gradient(800px 500px at 110% 100%, rgba(255,153,0,0.06), transparent 65%),
                    conic-gradient(from 210deg at 50% 50%, rgba(255,153,0,0.03), transparent 45%, rgba(255,153,0,0.03), transparent 70%),
                    linear-gradient(135deg, rgba(255,153,0,0.02), transparent 60%)">
        </div>

        <div class="relative container mx-auto px-4 pt-28 pb-16">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-white">
                    Produits
                </h1>
                <p class="mt-3 text-white/80">
                    Découvrez nos derniers produits. Recherchez, filtrez et triez pour
                    trouver rapidement ce qui vous convient.
                </p>
                <div class="mt-6 flex items-center gap-3">
                    <a href="#results"
                       class="inline-flex items-center rounded-full bg-secondary hover:bg-secondary_2 text-white px-5 py-2.5 transition">
                        Explorer
                    </a>
                    <span class="text-white/70">ou utilisez la recherche et les filtres ci-dessous</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Controls -->
    <section class="sticky top-16 z-40 bg-white/95 backdrop-blur-md border-y border-gray-200 shadow-sm">
        <div class="container mx-auto px-4">
            <!-- Mobile Toggle Header -->
            <div class="lg:hidden flex items-center justify-between py-3">
                <button id="mobileFilterToggle" class="flex items-center gap-2 text-gray-700 font-semibold focus:outline-none">
                    <svg id='Filter_2_24' width='24' height='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'><rect width='24' height='24' stroke='none'  opacity='0'/>


<g transform="matrix(0.89 0 0 0.89 12 12)" >
<path style="stroke: var(--color-secondary); stroke-width: 1.5; stroke-dasharray: none; stroke-linecap: round; stroke-dashoffset: 0; stroke-linejoin: round; stroke-miterlimit: 4; fill: none; fill-rule: nonzero; opacity: 1;" transform=" translate(-12, -12.05)" d="M 9.7 11.8 L 9.7 22.5 C 9.7 22.9 10 23.2 10.399999999999999 23.3 C 10.399999999999999 23.3 10.399999999999999 23.3 10.399999999999999 23.3 L 13.399999999999999 23.3 C 13.799999999999999 23.3 14.2 23 14.2 22.5 L 14.2 11.8 C 19.1 10.8 22.799999999999997 6.6000000000000005 23.2 1.6000000000000014 C 23.2 1.2000000000000015 22.9 0.8000000000000014 22.5 0.8000000000000014 C 22.5 0.8000000000000014 22.5 0.8000000000000014 22.5 0.8000000000000014 L 1.5 0.8000000000000014 C 1.1 0.8000000000000014 0.8 1.1000000000000014 0.8 1.6000000000000014 C 0.8 1.6000000000000014 0.8 1.6000000000000014 0.8 1.6000000000000014 C 1.1 6.6 4.8 10.8 9.7 11.8 z" stroke-linecap="round" />
</g>
</svg>
                    <span class="text-sm">Filtres et Recherche</span>
                </button>
                <div id="mobileFilterSummary" class="text-[10px] text-gray-500 truncate max-w-[150px]"></div>
            </div>

            <!-- Filter Content -->
            <div id="filterContent" class="hidden lg:grid py-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 items-end border-t lg:border-t-0 border-gray-100">
                <!-- Search -->
                <div class="lg:col-span-4">
                    <label class="block text-xs lg:text-sm text-gray-600 mb-1">Rechercher un produit</label>
                    <div class="relative group">
                        <input id="productSearchInput" type="text" placeholder="Nom, description..."
                               class="w-full rounded-xl bg-gray-100 border border-gray-200 focus:border-secondary_2
                                      placeholder-gray-400 text-gray-800 px-4 py-2 outline-none transition text-sm sm:text-base">
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-secondary transition"
                             viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"></circle>
                            <path d="M20 20L17 17" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                        </svg>
                    </div>
                </div>

                <!-- Category -->
                <div class="lg:col-span-3">
                    <label class="block text-xs lg:text-sm text-gray-600 mb-1">Catégorie</label>
                    <select id="categorySelect"
                            class="w-full rounded-xl bg-gray-100 border border-gray-200 focus:border-secondary_2 text-gray-800 px-4 py-2 outline-none transition text-sm sm:text-base">
                        <option value="">Toutes</option>
                        @if(!empty($categories))
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Price range -->
                <div class="lg:col-span-3">
                    <label class="block text-xs lg:text-sm text-gray-600 mb-1">Prix (DA)</label>
                    <div class="grid grid-cols-2 gap-2 sm:gap-3">
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 font-bold uppercase">Min</span>
                            <input id="priceMin" type="number" min="0" step="1000" value="0"
                                   class="w-full rounded-xl bg-gray-100 border border-gray-200 focus:border-secondary_2 text-gray-800 pl-10 pr-3 py-2 outline-none transition text-sm">
                        </div>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 font-bold uppercase">Max</span>
                            <input id="priceMax" type="number" min="0" step="1000" value="500000"
                                   class="w-full rounded-xl bg-gray-100 border border-gray-200 focus:border-secondary_2 text-gray-800 pl-10 pr-3 py-2 outline-none transition text-sm">
                        </div>
                    </div>
                </div>

                <!-- Availability -->
                <div class="lg:col-span-2">
                    <div class="flex items-center h-10 lg:h-11">
                        <label class="inline-flex items-center gap-2 cursor-pointer group">
                            <input id="availabilityToggle" type="checkbox"
                                   class="rounded bg-gray-100 border-gray-300 text-secondary focus:ring-secondary_2 w-4 h-4 lg:w-5 lg:h-5">
                            <span class="text-gray-700 text-xs lg:text-sm group-hover:text-secondary transition">En stock</span>
                        </label>
                    </div>
                </div>

                <!-- Sort + Reset -->
                <div class="sm:col-span-2 lg:col-span-12 flex flex-col sm:flex-row gap-3 justify-between items-start sm:items-center pt-2 border-t border-gray-100 mt-2">
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full sm:w-auto">
                        <label class="text-xs text-gray-500 whitespace-nowrap">Trier par</label>
                        <select id="sortSelect"
                                class="flex-1 sm:flex-none rounded-lg bg-gray-100 border border-gray-200 focus:border-secondary_2 text-gray-800 px-3 py-1.5 outline-none transition text-sm">
                            <option value="recent">Récent</option>
                            <option value="ratingDesc">Meilleure note</option>
                            <option value="priceAsc">Prix: croissant</option>
                            <option value="priceDesc">Prix: décroissant</option>
                            <option value="nameAsc">Nom A–Z</option>
                        </select>
                        <button id="resetBtn"
                                class="rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 px-3 py-1.5 transition text-sm flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Réinitialiser</span>
                        </button>
                    </div>

                    <!-- Active filters -->
                    <div id="activeFilters" class="flex flex-wrap gap-1.5"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Results -->
    <section id="results" class="container mx-auto px-4 py-10">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Résultats</h2>
            <div id="resultsCount" class="text-gray-600"></div>
        </div>

        <div id="productsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Cards injected by JS -->
        </div>

        <!-- Loading state -->
        <div id="loadingState" class="hidden py-20 flex flex-col justify-center items-center gap-3">
            <div class="relative">
                <div class="w-12 h-12 rounded-full border-4 border-gray-200"></div>
                <div class="absolute top-0 left-0 w-12 h-12 rounded-full border-4 border-secondary border-t-transparent animate-spin"></div>
            </div>
            <span class="text-gray-600 text-sm">Chargement...</span>
        </div>

        <!-- Empty state -->
        <div id="emptyState" class="hidden mt-10 text-center">
            <div class="inline-flex items-center gap-3 px-5 py-3 rounded-xl bg-gray-50 border border-gray-200">
                <svg class="w-5 h-5 text-secondary" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                    <path d="M8 12h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="text-gray-700">Aucun produit ne correspond aux critères sélectionnés.</span>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="container mx-auto px-4 pb-16">
        <div class="rounded-2xl bg-gradient-to-r from-secondary to-secondary_2 px-6 py-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-2xl font-semibold text-white">Besoin d’un produit sur mesure ?</h3>
                <p class="text-white/90 mt-1">Demandez un devis gratuit et adapté à vos exigences.</p>
            </div>
            <a href="{{ url('/devis') }}" class="inline-flex items-center bg-white text-secondary_2 font-semibold rounded-xl px-5 py-2.5 hover:bg-gray-50 transition">
                Demander un devis
            </a>
        </div>
    </section>
</div>

<!-- Animations + Filtering Script -->
<script>

    
    const PRODUCTS = @json($products);
    const TAGS = @json($tags ?? []);

    // State
    const state = {
        search: '',
        category: '',
        priceMin: 0,
        priceMax: 500000,
        availableOnly: false,
        sort: 'recent',
        activeTags: new Set(),
    };

    // Elements
    const el = {
        grid: null,
        empty: null,
        loading: null,
        count: null,
        activeFilters: null,
        searchInput: null,
        categorySelect: null,
        priceMin: null,
        priceMax: null,
        availabilityToggle: null,
        sortSelect: null,
        resetBtn: null,
    };

    document.addEventListener('DOMContentLoaded', () => {
        // Get elements
        el.grid = document.getElementById('productsGrid');
        el.empty = document.getElementById('emptyState');
        el.loading = document.getElementById('loadingState');
        el.count = document.getElementById('resultsCount');
        el.activeFilters = document.getElementById('activeFilters');

        el.searchInput = document.getElementById('productSearchInput');
        el.categorySelect = document.getElementById('categorySelect');
        el.priceMin = document.getElementById('priceMin');
        el.priceMax = document.getElementById('priceMax');
        el.availabilityToggle = document.getElementById('availabilityToggle');
        el.sortSelect = document.getElementById('sortSelect');
        el.resetBtn = document.getElementById('resetBtn');

        // Mobile filter toggle
        const filterToggle = document.getElementById('mobileFilterToggle');
        const filterContent = document.getElementById('filterContent');
        if (filterToggle && filterContent) {
            filterToggle.addEventListener('click', () => {
                const isHidden = filterContent.classList.contains('hidden');
                if (isHidden) {
                    filterContent.classList.remove('hidden');
                    filterContent.classList.add('grid');
                    filterToggle.querySelector('span').textContent = 'Fermer';
                } else {
                    filterContent.classList.add('hidden');
                    filterContent.classList.remove('grid');
                    filterToggle.querySelector('span').textContent = 'Filtres et Recherche';
                }
            });
        }

        // Bind events
        el.searchInput.addEventListener('input', e => { state.search = e.target.value.trim().toLowerCase(); render(); });
        el.categorySelect.addEventListener('change', e => { state.category = e.target.value; render(); });
        el.priceMin.addEventListener('change', e => { state.priceMin = Number(e.target.value) || 0; render(); });
        el.priceMax.addEventListener('change', e => { state.priceMax = Number(e.target.value) || 9999999; render(); });
        el.availabilityToggle.addEventListener('change', e => { state.availableOnly = e.target.checked; render(); });
        el.sortSelect.addEventListener('change', e => { state.sort = e.target.value; render(); });
        el.resetBtn.addEventListener('click', resetFilters);

        // First render
        render();
    });

    function resetFilters() {
        state.search = '';
        state.category = '';
        state.priceMin = 0;
        state.priceMax = 500000;
        state.availableOnly = false;
        state.sort = 'recent';
        state.activeTags.clear();

        el.searchInput.value = '';
        el.categorySelect.value = '';
        el.priceMin.value = 0;
        el.priceMax.value = 500000;
        el.availabilityToggle.checked = false;
        el.sortSelect.value = 'recent';

        render();
    }

    function applyFilters(items) {
        return items.filter(p => {
            const matchesStatus = (p.status || 'actif').toLowerCase() !== 'inactif';
            const matchesSearch = state.search === '' ||
                p.name.toLowerCase().includes(state.search) ||
                p.description.toLowerCase().includes(state.search) ||
                (p.tags || []).some(t => t.toLowerCase().includes(state.search));
            const matchesCategory = state.category === '' || p.category === state.category;
            const matchesPrice = p.price >= state.priceMin && p.price <= state.priceMax;
            const matchesAvailability = !state.availableOnly || p.available;

            // Active tag chips (intersection)
            const tagFilterOk = state.activeTags.size === 0 ||
                (p.tags || []).some(t => state.activeTags.has(t));

            return matchesStatus && matchesSearch && matchesCategory && matchesPrice && matchesAvailability && tagFilterOk;
        });
    }

    function applySort(items) {
        const arr = [...items];
        switch (state.sort) {
            case 'ratingDesc': arr.sort((a,b) => b.rating - a.rating); break;
            case 'priceAsc': arr.sort((a,b) => a.price - b.price); break;
            case 'priceDesc': arr.sort((a,b) => b.price - a.price); break;
            case 'nameAsc': arr.sort((a,b) => a.name.localeCompare(b.name)); break;
            case 'ratingDesc': arr.sort((a,b) => b.rating - a.rating); break;
            case 'recent':
            default:
                arr.sort((a,b) => new Date(b.createdAt) - new Date(a.createdAt));
        }
        return arr;
    }

    function renderActiveFilters(filtered) {
        const chips = [];

        if (state.search) chips.push(makeChip('Recherche', state.search, () => { state.search = ''; el.searchInput.value=''; render(); }));
        if (state.category) chips.push(makeChip('Catégorie', state.category, () => { state.category = ''; el.categorySelect.value=''; render(); }));
        if (state.availableOnly) chips.push(makeChip('Disponibilité', 'En stock', () => { state.availableOnly = false; el.availabilityToggle.checked=false; render(); }));

        // Price chip shown only if non-default
        if (state.priceMin > 0 || state.priceMax < 500000) {
            chips.push(makeChip('Prix', `${state.priceMin}–${state.priceMax} DA`, () => {
                state.priceMin = 0; state.priceMax = 500000;
                el.priceMin.value = 0; el.priceMax.value = 500000; render();
            }));
        }

        // Tag chips
        state.activeTags.forEach(tag => {
            chips.push(makeChip('Tag', tag, () => { state.activeTags.delete(tag); render(); }));
        });

        el.activeFilters.innerHTML = '';
        chips.forEach(c => el.activeFilters.appendChild(c));
    }

    function makeChip(label, value, onRemove) {
        const chip = document.createElement('button');
        chip.className = 'group inline-flex items-center gap-2 bg-gray-100 border border-gray-200 text-gray-700 px-3 py-1.5 rounded-full hover:bg-gray-200 hover:border-gray-300 transition';
        chip.innerHTML = `
            <span class="text-xs">${label}:</span>
            <span class="text-sm font-medium">${value}</span>
            <svg class="w-4 h-4 text-gray-500 group-hover:text-gray-700 transition" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 6L18 18M6 18L18 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        `;
        chip.addEventListener('click', onRemove);
        return chip;
    }

    function render() {
        if (el.loading) el.loading.classList.remove('hidden');
        if (el.grid) el.grid.classList.add('opacity-50');

        // Small timeout to ensure loading visibility
        setTimeout(() => {
            const filtered = applyFilters(PRODUCTS);
            const sorted = applySort(filtered);

            el.count.textContent = `${sorted.length} produit(s)`;
            renderActiveFilters(sorted);

            // Update mobile summary
            const summaryEl = document.getElementById('mobileFilterSummary');
            if (summaryEl) {
                const active = [];
                if (state.search) active.push(state.search);
                if (state.category) active.push(state.category);
                if (state.availableOnly) active.push('En stock');
                if (state.priceMin > 0 || state.priceMax < 500000) active.push(`${state.priceMin}-${state.priceMax}`);
                summaryEl.textContent = active.length > 0 ? active.join(', ') : 'Aucun filtre';
            }

            el.grid.innerHTML = '';
            el.empty.classList.toggle('hidden', sorted.length !== 0);
            
            if (el.loading) el.loading.classList.add('hidden');
            if (el.grid) el.grid.classList.remove('opacity-50');

        sorted.forEach((p, idx) => {
            const card = document.createElement('article');
            card.className = 'group rounded-2xl overflow-hidden bg-white border border-gray-200 hover:border-secondary_2 transition shadow-md hover:shadow-lg flex flex-col h-full';
            card.style.animation = `fadeSlideUp 500ms ease ${idx * 60}ms both`;

            card.innerHTML = `
                <div class="relative aspect-[4/3] sm:aspect-video lg:aspect-[4/3] overflow-hidden bg-gray-50">
                    <img src="${p.image ? p.image : '/images/no_image.png'}" alt="${escapeHtml(p.name)}" class="w-full h-full object-cover opacity-95 group-hover:scale-105 group-hover:opacity-100 transition duration-500 ${p.image ? '' : 'max-h-20 max-w-20 object-contain'}">
                    <div class="absolute top-2 left-2 sm:top-3 sm:left-3 inline-flex items-center gap-1.5 sm:gap-2 px-2 py-1 sm:px-3 sm:py-1.5 rounded-full bg-white/80 backdrop-blur text-gray-700 text-[10px] sm:text-xs">
                        <span class="truncate max-w-[80px] sm:max-w-none">${escapeHtml(p.category)}</span>
                        ${p.available ? '<span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-emerald-500"></span>' : '<span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-red-500"></span>'}
                    </div>
                </div>
                <div class="p-3 sm:p-4 flex flex-col flex-1">
                    <div class="flex items-start justify-between gap-2 sm:gap-3">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 line-clamp-1">${escapeHtml(p.name)}</h3>
                        <div class="inline-flex items-center gap-0.5 text-secondary shrink-0">
                            <div class="flex items-center">
                                ${Array.from({length: 5}, (_, i) => `
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 ${i < Math.round(p.rating) ? 'text-secondary fill-current' : 'text-gray-200 fill-current'}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                `).join('')}
                            </div>
                            <span class="text-gray-500 text-[10px] sm:text-xs ml-1 font-medium">${p.rating.toFixed(1)}</span>
                        </div>
                    </div>
                    <p class="mt-1 sm:mt-2 text-gray-600 text-xs sm:text-sm line-clamp-2">${escapeHtml(p.description)}</p>

                    <div class="mt-2 sm:mt-3 flex flex-wrap gap-1.5 sm:gap-2">
                        ${(p.tags || []).slice(0, 3).map(t => `
                            <button class="text-[10px] sm:text-xs px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full bg-gray-100 border border-gray-200 text-gray-700 hover:bg-gray-200 transition"
                                    onclick="toggleTag('${t}')">${t}</button>
                        `).join('')}
                    </div>

                    <div class="mt-auto pt-3 sm:pt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="text-lg sm:text-xl font-bold text-secondary_2">${p.price.toLocaleString('fr-DZ')} DA</div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <a href="/produits/${p.id}" class="flex-1 sm:flex-none text-center px-3 py-1.5 rounded-lg bg-gray-100 border border-gray-200 hover:bg-gray-200 transition text-xs sm:text-sm text-gray-700">
                                Détails
                            </a>
                            <button type="button" 
                                    data-commander
                                    data-product-name="${escapeHtml(p.name)}"
                                    class="flex-1 sm:flex-none px-3 py-1.5 rounded-lg bg-secondary hover:bg-secondary_2 text-white transition text-xs sm:text-sm">
                                Commander
                            </button>
                        </div>
                    </div>
                </div>
            `;

            el.grid.appendChild(card);
        });

        injectStyles();
        setupRevealOnScroll();
        }, 300);
    }

    function toggleTag(tag) {
        if (state.activeTags.has(tag)) state.activeTags.delete(tag);
        else state.activeTags.add(tag);
        render();
    }

    function escapeHtml(str) {
        return String(str).replace(/[&<>"']/g, s => ({
            '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'
        })[s]);
    }

    function injectStyles() {
        if (document.getElementById('fadeSlideKeyframes')) return;
        const style = document.createElement('style');
        style.id = 'fadeSlideKeyframes';
        style.textContent = `
            @keyframes fadeSlideUp {
                from { opacity: 0; transform: translateY(16px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);
    }

    function setupRevealOnScroll() {
        // Reveal hero content as user scrolls (if not already visible)
        const articles = document.querySelectorAll('#productsGrid article');
        const observer = new IntersectionObserver((entries) => {
            for (const e of entries) {
                if (e.isIntersecting) {
                    e.target.classList.add('opacity-100', 'translate-y-0');
                    observer.unobserve(e.target);
                }
            }
        }, { threshold: 0.15 });

        articles.forEach(a => observer.observe(a));
    }
</script>
@include('layouts.coming_soon')
@endsection
