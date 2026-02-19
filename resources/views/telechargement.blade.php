@extends('layouts.app')

@section('title', 'Téléchargement - ALCOIL')

@section('content')
    <!-- Hero -->
    <div class="relative bg-primary py-20">
        <div class="absolute inset-0 overflow-hidden">
             <img src="{{ asset('images/BG_usine.jpg') }}" alt="Background" class="w-full h-full object-cover opacity-10">
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                Téléchargements
            </h1>
            <p class="mt-4 text-xl text-gray-300 max-w-3xl mx-auto">
                Retrouvez ici toutes nos brochures, catalogues et fiches techniques.
            </p>
        </div>
    </div>

    <!-- Content -->
    <section class="py-24 bg-gray-50 overflow-hidden relative">
        <!-- Decorative Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-secondary/5 rounded-full blur-3xl"></div>
            <div class="absolute top-[20%] -right-[10%] w-[40%] h-[40%] bg-primary/5 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20">
                <span class="text-secondary font-semibold tracking-wider uppercase text-sm">Documentation</span>
                <h2 class="mt-2 text-4xl font-extrabold text-gray-900 sm:text-5xl tracking-tight">
                    Brochures & Catalogues
                </h2>
                <div class="w-24 h-1 bg-secondary mx-auto mt-6 rounded-full"></div>
                <p class="mt-6 text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    Découvrez nos produits en détail grâce à nos brochures interactives. <br class="hidden md:block">
                    Feuilletez-les comme un vrai livre ou téléchargez-les en PDF.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-10 gap-y-20">
                @forelse($downloads as $download)
                <div class="group relative flex flex-col items-center perspective-1000">
                    
                    <!-- 3D Book Wrapper -->
                    <div class="relative w-[220px] h-[320px] mb-12 transition-transform duration-500 ease-out group-hover:scale-105 group-hover:-translate-y-4 cursor-pointer"
                         onclick="openFlipbook('{{ Storage::url($download->file_path) }}', '{{ addslashes($download->title) }}')">
                        
                        <!-- The Book Itself -->
                        <div class="book-3d w-full h-full relative transform-style-3d transition-transform duration-700 ease-out group-hover:rotate-y-[-25deg] group-hover:rotate-x-[10deg]">
                            
                            <!-- Front Cover -->
                            <div class="absolute inset-0 bg-white rounded-r-md shadow-2xl backface-hidden z-20 overflow-hidden">
                                @if($download->image_path)
                                    <img src="{{ Storage::url($download->image_path) }}" alt="{{ $download->title }}" class="w-full h-full object-cover">
                                    <!-- Glossy Reflection -->
                                    <div class="absolute inset-0 bg-gradient-to-tr from-white/20 to-transparent pointer-events-none"></div>
                                    <!-- Spine Shadow -->
                                    <div class="absolute left-0 top-0 bottom-0 w-4 bg-gradient-to-r from-black/20 to-transparent pointer-events-none"></div>
                                @else
                                    <div class="w-full h-full bg-slate-800 flex flex-col items-center justify-center p-6 text-center border-l-4 border-slate-700">
                                        <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <h3 class="text-white font-serif text-xl tracking-wide">{{ $download->title }}</h3>
                                    </div>
                                @endif
                            </div>

                            <!-- Spine (Left Side) -->
                            <div class="absolute top-0 bottom-0 left-0 w-[40px] bg-slate-900 transform -translate-x-full rotate-y-[-90deg] origin-right z-10 flex items-center justify-center overflow-hidden">
                                <span class="text-white/60 text-xs font-mono tracking-widest rotate-90 whitespace-nowrap uppercase">ALCOIL CATALOG</span>
                            </div>

                            <!-- Back Cover -->
                            <div class="absolute inset-0 bg-slate-100 rounded-l-md transform translate-z-[-40px] shadow-lg"></div>
                            
                            <!-- Pages (Right Side effect) -->
                            <div class="absolute top-2 bottom-2 right-0 w-[38px] bg-white transform rotate-y-[-90deg] translate-x-[19px] translate-z-[-20px] bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjEiPjxyZWN0IHdpZHRoPSI0IiBoZWlnaHQ9IjEiIGZpbGw9IiNjY2MiLz48L3N2Zz4=')]"></div>

                            <!-- Shadow under the book -->
                            <div class="absolute -bottom-8 left-4 right-4 h-4 bg-black/40 blur-xl rounded-[100%] transform rotate-x-[80deg] translate-z-[-50px] transition-all duration-500 group-hover:bg-black/20 group-hover:scale-110 group-hover:translate-y-4"></div>
                        </div>

                        <!-- Hover Overlay Button -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-30 pointer-events-none">
                            <div class="bg-secondary/90 backdrop-blur-sm text-white rounded-full p-4 shadow-xl transform scale-50 group-hover:scale-100 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Content Info -->
                    <div class="text-center w-full px-4 relative z-20">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-secondary transition-colors duration-300">{{ $download->title }}</h3>
                        <p class="text-gray-500 text-sm mb-6 line-clamp-2 h-10 max-w-xs mx-auto">{{ $download->description }}</p>
                        
                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 w-full">
                            @if(\Illuminate\Support\Str::endsWith(strtolower($download->file_path), '.pdf'))
                            <button onclick="openFlipbook('{{ Storage::url($download->file_path) }}', '{{ addslashes($download->title) }}')" 
                                    class="w-full sm:w-auto flex-1 inline-flex items-center justify-center px-6 py-2.5 border-2 border-secondary text-sm font-bold rounded-lg text-secondary bg-transparent hover:bg-secondary hover:text-white transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Feuilleter
                            </button>
                            @endif
                            
                            <a href="{{ Storage::url($download->file_path) }}" target="_blank" 
                               class="w-full sm:w-auto flex-1 inline-flex items-center justify-center px-6 py-2.5 border-2 border-gray-200 text-sm font-bold rounded-lg text-gray-700 bg-white hover:border-gray-400 hover:text-gray-900 transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                PDF
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full min-h-[400px] flex flex-col items-center justify-center py-16 text-center relative rounded-3xl bg-gradient-to-b from-white to-gray-50 border border-gray-100 shadow-inner overflow-hidden">
                    
                    <!-- Animated Background -->
                    <div class="absolute inset-0 overflow-hidden pointer-events-none">
                        <div class="absolute top-0 left-1/4 w-96 h-96 bg-secondary/5 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
                        <div class="absolute top-0 right-1/4 w-96 h-96 bg-primary/5 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
                        <div class="absolute -bottom-32 left-1/2 w-96 h-96 bg-purple-50 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000"></div>
                    </div>

                    <!-- Icon / Illustration -->
                    <div class="relative z-10 mb-8">
                        <div class="relative flex items-center justify-center w-32 h-32 mx-auto">
                            <!-- Outer Ring -->
                            <div class="absolute inset-0 border-4 border-dashed border-gray-200 rounded-full animate-[spin_10s_linear_infinite]"></div>
                            
                            <!-- Inner Ring -->
                            <div class="absolute inset-4 border-4 border-gray-100 rounded-full animate-[spin_8s_linear_infinite_reverse]"></div>
                            
                            <!-- Center Icon -->
                            <div class="relative bg-white p-4 rounded-full shadow-lg transform transition-transform hover:scale-110 duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                
                                <!-- Floating Badge -->
                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-secondary rounded-full border-2 border-white animate-bounce"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Text Content -->
                    <div class="relative z-10 max-w-lg px-4">
                        <h3 class="text-3xl font-extrabold text-gray-900 mb-3 tracking-tight">
                            Bientôt Disponible
                        </h3>
                        <p class="text-lg text-gray-500 mb-8 leading-relaxed">
                            Nous préparons actuellement de nouvelles brochures pour vous. <br>
                            Revenez très vite pour découvrir nos nouveautés !
                        </p>
                        
                        <div class="inline-flex items-center justify-center px-6 py-2 border border-transparent text-sm font-medium rounded-full text-secondary bg-secondary/10 hover:bg-secondary/20 transition-colors cursor-default">
                            <span class="relative flex h-3 w-3 mr-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-secondary"></span>
                            </span>
                            Mise à jour en cours
                        </div>
                    </div>
                </div>
                <style>
                    @keyframes blob {
                        0% { transform: translate(0px, 0px) scale(1); }
                        33% { transform: translate(30px, -50px) scale(1.1); }
                        66% { transform: translate(-20px, 20px) scale(0.9); }
                        100% { transform: translate(0px, 0px) scale(1); }
                    }
                    .animate-blob {
                        animation: blob 7s infinite;
                    }
                    .animation-delay-2000 {
                        animation-delay: 2s;
                    }
                    .animation-delay-4000 {
                        animation-delay: 4s;
                    }
                </style>
                @endforelse
            </div>
        </div>
    </section>

    <style>
        .perspective-1000 {
            perspective: 1000px;
        }
        .transform-style-3d {
            transform-style: preserve-3d;
        }
        .backface-hidden {
            backface-visibility: hidden;
        }
        .rotate-y-12 {
            transform: rotateY(12deg);
        }
        .-rotate-x-6 {
            transform: rotateX(-6deg);
        }
    </style>

    <!-- 3D Flipbook Modal -->
    <div id="flipbook-modal" class="fixed inset-0 z-[100] hidden bg-slate-900 flex flex-col">
        <!-- Toolbar -->
        <div class="flex items-center justify-between px-4 py-3 bg-slate-800 shadow-md z-20 border-b border-slate-700">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="p-2 bg-secondary/20 rounded-lg shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="text-white font-medium text-sm sm:text-lg truncate" id="book-title">Document</h3>
            </div>
            
            <div class="flex items-center gap-2">
                <button onclick="closeFlipbook()" class="text-white/60 hover:text-white hover:bg-white/10 transition-all p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Book Container -->
        <div class="flex-1 flex items-center justify-center p-2 sm:p-8 overflow-hidden relative w-full h-full bg-slate-900 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-slate-800 via-slate-900 to-black">
            <!-- Loader -->
            <div id="book-loader" class="absolute inset-0 flex flex-col items-center justify-center z-10 text-white bg-slate-900/90 backdrop-blur-sm transition-opacity duration-300">
                <div class="w-12 h-12 border-4 border-secondary/30 border-t-secondary rounded-full animate-spin mb-4"></div>
                <span class="text-lg font-light tracking-wide text-gray-300">Préparation du livre...</span>
                <span class="text-sm text-gray-500 mt-2" id="loading-progress"></span>
            </div>
            
            <!-- The Flipbook -->
            <div class="relative w-full h-full flex items-center justify-center transition-transform duration-200" id="zoom-container">
                 <div id="book" class="shadow-2xl shadow-black/80 opacity-0 transition-opacity duration-500"></div>
            </div>
        </div>

        <!-- Controls -->
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 bg-slate-800/90 backdrop-blur-md rounded-full px-4 py-2 shadow-xl border border-slate-600 flex items-center gap-2 sm:gap-4 z-20">
            <button onclick="bookFlipPrev()" class="p-2 rounded-full hover:bg-white/10 text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            
            <span class="text-white font-mono text-sm sm:text-base whitespace-nowrap min-w-[80px] text-center select-none">
                <span id="page-current" class="text-secondary font-bold">1</span> / <span id="page-total" class="text-gray-400">--</span>
            </span>

            <button onclick="bookFlipNext()" class="p-2 rounded-full hover:bg-white/10 text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            
            <div class="w-px h-5 bg-slate-600 mx-1 sm:mx-2"></div>

            <button onclick="zoomBook(-0.1)" class="p-2 rounded-full hover:bg-white/10 text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                </svg>
            </button>
            <button onclick="zoomBook(0.1)" class="p-2 rounded-full hover:bg-white/10 text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>
    
    <script>
        // PDF.js worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        
        let pageFlip = null;
        let bookEl = document.getElementById('book');
        const modal = document.getElementById('flipbook-modal');
        const loader = document.getElementById('book-loader');
        const titleSpan = document.getElementById('book-title');
        let currentZoom = 1;

        function closeFlipbook() {
            modal.classList.add('hidden');
            if (pageFlip) {
                pageFlip.destroy();
                pageFlip = null;
            }
            bookEl.innerHTML = ''; // Clear content
            bookEl.classList.add('opacity-0');
            document.body.style.overflow = ''; // Restore scrolling
            resetZoom();
        }

        function zoomBook(delta) {
            currentZoom = Math.min(Math.max(0.5, currentZoom + delta), 2.5);
            document.getElementById('zoom-container').style.transform = `scale(${currentZoom})`;
        }

        function resetZoom() {
            currentZoom = 1;
            document.getElementById('zoom-container').style.transform = `scale(1)`;
        }
        
        function bookFlipNext() {
            if (pageFlip) pageFlip.flipNext();
        }
        
        function bookFlipPrev() {
            if (pageFlip) pageFlip.flipPrev();
        }

        async function openFlipbook(url, title = 'Document') {
            modal.classList.remove('hidden');
            loader.classList.remove('hidden');
            bookEl.classList.add('opacity-0');
            document.body.style.overflow = 'hidden'; 
            titleSpan.textContent = title;
            document.getElementById('loading-progress').textContent = 'Chargement du document...';

            try {
                // Check if file exists
                const response = await fetch(url, { method: 'HEAD' });
                if (!response.ok) {
                    throw new Error(`Fichier introuvable (${response.status})`);
                }

                // Load PDF
                const loadingTask = pdfjsLib.getDocument(url);
                const pdf = await loadingTask.promise;
                
                // Config
                const pixelRatio = window.devicePixelRatio || 1;
                // High quality scale: 2.0 is usually enough for retina, 3.0 for very high quality
                const scale = Math.min(window.innerWidth > 1000 ? 2.0 : 1.5, 3.0); 

                // Get dimensions from the first page
                const page1 = await pdf.getPage(1);
                const viewport1 = page1.getViewport({ scale: 1 });
                const baseWidth = viewport1.width;
                const baseHeight = viewport1.height;
                const isPortrait = baseHeight > baseWidth;

                // Render all pages to images (High Quality)
                const images = [];
                
                for (let i = 1; i <= pdf.numPages; i++) {
                    document.getElementById('loading-progress').textContent = `Préparation page ${i} / ${pdf.numPages}`;
                    
                    const page = await pdf.getPage(i);
                    const viewport = page.getViewport({ scale: scale });
                    
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    await page.render({
                        canvasContext: context,
                        viewport: viewport
                    }).promise;

                    images.push(canvas.toDataURL('image/jpeg', 0.9));
                }

                // Initialize PageFlip
                loader.classList.add('hidden');
                bookEl.classList.remove('opacity-0');
                
                if (typeof St === 'undefined' || !St.PageFlip) {
                     throw new Error('La librairie PageFlip n\'a pas pu charger.');
                }

                // Create a new instance
                pageFlip = new St.PageFlip(bookEl, {
                    width: baseWidth, 
                    height: baseHeight,
                    size: 'stretch',
                    // Smart responsiveness
                    minWidth: 300,
                    maxWidth: 1000,
                    minHeight: 400,
                    maxHeight: 1400,
                    
                    maxShadowOpacity: 0.5,
                    showCover: true, // Force cover mode (first page is single)
                    mobileScrollSupport: false,
                    usePortrait: false, // Force spread mode (2 pages) when possible
                    startPage: 0,
                    flippingTime: 1000, // Smoother animation
                    useMouseEvents: true,
                    swipeDistance: 30,
                    drawShadow: true
                });

                pageFlip.loadFromImages(images);

                // Update controls & Center Cover
                const updateInfo = () => {
                    if (!pageFlip) return;
                    const current = pageFlip.getCurrentPageIndex();
                    document.getElementById('page-total').textContent = pageFlip.getPageCount();
                    document.getElementById('page-current').textContent = current + 1;

                    // Center the cover page visually when in spread mode (landscape)
                    // The library renders [Empty][Cover] for page 0 in landscape.
                    // We use clip-path to hide the empty left side and translateX to center the Cover.
                    const isLandscape = pageFlip.getOrientation() === 'landscape';
                    
                    if (isLandscape && current === 0) {
                        bookEl.style.transform = 'translateX(-25%)';
                        bookEl.style.clipPath = 'inset(0 0 0 50%)'; // Hide left half (empty/white page)
                    } else {
                        bookEl.style.transform = 'translateX(0)';
                        bookEl.style.clipPath = 'inset(0 0 0 0)'; // Show full book
                    }
                    bookEl.style.transition = 'transform 0.6s cubic-bezier(0.25, 0.8, 0.25, 1), clip-path 0.6s cubic-bezier(0.25, 0.8, 0.25, 1)';
                };

                pageFlip.on('init', updateInfo);
                pageFlip.on('flip', updateInfo);
                pageFlip.on('changeOrientation', updateInfo);
                
                // Keyboard support
                const keyHandler = (e) => {
                    if (modal.classList.contains('hidden')) {
                        document.removeEventListener('keydown', keyHandler);
                        return;
                    }
                    if (e.key === 'ArrowRight') bookFlipNext();
                    if (e.key === 'ArrowLeft') bookFlipPrev();
                    if (e.key === 'Escape') closeFlipbook();
                };
                document.addEventListener('keydown', keyHandler);

            } catch (error) {
                console.error('Error loading PDF:', error);
                alert('Impossible de charger le document : ' + error.message);
                closeFlipbook();
            }
        }
    </script>
@endsection