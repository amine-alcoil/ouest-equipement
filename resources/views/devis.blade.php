@extends('layouts.app')

@section('content')
<section class="relative bg-primary text-white py-20">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-3xl md:text-4xl font-bold">Demande de devis</h1>
        <p class="mt-2 text-white/80">Choisissez le type de demande et renseignez le formulaire.</p>
    </div>
</section>

<section class="max-w-5xl mx-auto px-6 py-10">

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 text-red-700 px-4 py-3">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
    <div id="devisSuccessOverlay" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="rounded-2xl bg-white p-6 border border-gray-200 shadow-xl w-full max-w-md text-center">
            <div class="mx-auto w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                <svg class="w-7 h-7 text-emerald-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="mt-4 text-lg font-semibold text-gray-900">Merci !</div>
            <div class="mt-1 text-gray-600">{{ session('success') }}</div>
            <div class="mt-6">
                <button type="button" class="px-4 py-2 rounded-lg bg-secondary hover:bg-secondary_2 text-white" onclick="document.getElementById('devisSuccessOverlay').remove()">OK</button>
            </div>
        </div>
    </div>
    @endif

    <div class="rounded-xl overflow-hidden shadow-lg">
        <div class="bg-gray-100 flex flex-col sm:flex-row">
            <button data-tab="standard" class="tab-btn flex-1 px-4 py-3 text-center font-semibold bg-transparent text-gray-800 transition-colors hover:bg-secondary/10 text-sm md:text-base">Demande standard</button>
            <button data-tab="specific" class="tab-btn flex-1 px-4 py-3 text-center font-semibold bg-transparent text-gray-800 transition-colors hover:bg-secondary/10 text-sm md:text-base">Demande spécifique</button>
        </div>

        <div id="tab-standard" class="tab-panel p-6">
            <div class="rounded-2xl p-6">
                <h2 class="text-2xl font-semibold text-gray-900">Envoyer un message</h2>
                <p class="text-gray-600 mt-1">Nous revenons vers vous au plus vite.</p>
                <form method="POST" action="{{ route('devis.standard') }}" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nom complet</label>
                        <input name="name" type="text" placeholder="Votre nom" required class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Email</label>
                        <input name="email" type="email" placeholder="vous@exemple.com" required class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Téléphone (optionnel)</label>
                        <input name="phone" type="text" placeholder="+213 ..." class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Entreprise</label>
                        <input name="company" type="text" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Produit souhaité</label>
                        <input name="product" type="text" value="{{ request('product') }}" placeholder="Ex. Échangeur à plaques" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Quantité</label>
                        <input name="quantity" type="number" min="1" placeholder="1" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Message</label>
                        <textarea name="message" rows="5" placeholder="Décrivez votre besoin" required class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition"></textarea>
                    </div>
                   <div class="mt-6 flex flex-wrap items-center gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 bg-secondary hover:bg-secondary_2 text-white font-semibold px-5 py-3 rounded-lg transition-colors">
                            Envoyer
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M22 2L11 13"/>
                            <path d="M22 2l-6 20-5-9-9-5 20-6z"/>
                        </svg>
                        </button>
                        <span class="text-gray-600">Nous répondons sous 24–48h.</span>
                    </div>
                </form>
            </div>
        </div>

        <div id="tab-specific" class="tab-panel p-6 hidden">
            <div class="rounded-2xl p-6">
                <h2 class="text-2xl font-semibold text-gray-900">Demande de devis spécifique</h2>
                <p class="text-gray-600 mt-1">Veuillez remplir les spécifications et joindre vos schémas.</p>
                <div id="schemaSlider" class="relative rounded-lg overflow-hidden">
                    <div class="slide">
                        
                        <img src="{{ asset('images/Schema_1.png') }}" alt="Schéma Entrée" class="w-full h-auto object-contain">
                    </div>
                    <div class="slide hidden">
                        
                        <img src="{{ asset('images/Schema_2.png') }}" alt="Schéma Sortie" class="w-full h-auto object-contain">
                    </div>
                    <div class="slide hidden">
                        
                        <img src="{{ asset('images/Schema_3.png') }}" alt="Schéma 3" class="w-full h-auto object-contain">
                    </div>
                    <div class="absolute inset-x-0 bottom-2 flex justify-center gap-2">
                        <button type="button" class="dot w-2 h-2 rounded-full bg-secondary"></button>
                        <button type="button" class="dot w-2 h-2 rounded-full bg-gray-300"></button>
                        <button type="button" class="dot w-2 h-2 rounded-full bg-gray-300"></button>
                    </div>
                </div>
                <form method="POST" action="{{ route('devis.specific') }}" enctype="multipart/form-data" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nom complet</label>
                        <input name="name" type="text" placeholder="Votre nom" required class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Email</label>
                        <input name="email" type="email" placeholder="vous@exemple.com" required class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Téléphone</label>
                        <input name="phone" type="text" placeholder="+213 ..." required class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Entreprise</label>
                        <input name="company" type="text" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Type d'échangeur</label>
                        <input name="type_exchangeur" type="text" placeholder="Ex. Condenseur" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Cuivre Ø</label>
                        <input name="cuivre_diametre" type="number" step="0.01" min="0" placeholder="mm" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Pas Ailette</label>
                        <input name="pas_ailette" type="number" step="0.01" min="0" placeholder="mm" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">La hauteur</label>
                        <input name="hauteur_mm" type="number" step="0.01" min="0" placeholder="mm" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">La largeur</label>
                        <input name="largeur_mm" type="number" step="0.01" min="0" placeholder="mm" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">La longueur</label>
                        <input name="longueur_mm" type="number" step="0.01" min="0" placeholder="mm" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">La longueur totale</label>
                        <input name="longueur_totale_mm" type="number" step="0.01" min="0" placeholder="mm" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Collecteur 1</label>
                        <input name="collecteur1_nb" type="number" min="0" placeholder="ex. 2" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Ø Collecteur 1</label>
                        <input name="collecteur1_diametre" type="number" step="0.01" min="0" placeholder="mm" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Collecteur 2</label>
                        <input name="collecteur2_nb" type="number" min="0" placeholder="ex. 2" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Ø Collecteur 2</label>
                        <input name="collecteur2_diametre" type="number" step="0.01" min="0" placeholder="mm" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nombre de tubes</label>
                        <input name="nombre_tubes" type="number" min="1" placeholder="ex. 24" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Géométrie X (mm)</label>
                        <input name="geometrie_x_mm" type="number" step="0.01" min="0" placeholder="mm" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Géométrie Y (mm)</label>
                        <input name="geometrie_y_mm" type="number" step="0.01" min="0" placeholder="mm" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    
                    <div class="md:col-span-2 text-sm text-gray-600">Note: les dimensions en mm</div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Description technique</label>
                        <textarea name="requirements" rows="5" placeholder="Décrivez votre besoin" required class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Pièces jointes (schémas, plans)</label>
                        <input name="files[]" type="file" multiple accept="image/*,.pdf,.dxf,.dwg" class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition">
                    </div>
                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 bg-secondary hover:bg-secondary_2 text-white font-semibold px-5 py-3 rounded-lg transition-colors">
                            Envoyer
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M22 2L11 13"/>
                            <path d="M22 2l-6 20-5-9-9-5 20-6z"/>
                        </svg>
                        </button>
                        <span class="text-gray-600">Nous répondons sous 24–48h.</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var buttons = document.querySelectorAll('.tab-btn');
        var panels = {
            standard: document.getElementById('tab-standard'),
            specific: document.getElementById('tab-specific')
        };
        function activate(name) {
            buttons.forEach(function (b) {
                var isActive = b.getAttribute('data-tab') === name;
                b.classList.toggle('bg-secondary', isActive);
                b.classList.toggle('text-white', isActive);
                b.classList.toggle('bg-transparent', !isActive);
                b.classList.toggle('text-gray-800', !isActive);
            });
            panels.standard.classList.toggle('hidden', name !== 'standard');
            panels.specific.classList.toggle('hidden', name !== 'specific');
        }
        buttons.forEach(function (b) {
            b.addEventListener('click', function () {
                activate(b.getAttribute('data-tab'));
            });
        });
        var initial = 'standard';
        var qs = new URLSearchParams(window.location.search);
        var tabParam = qs.get('tab');
        if (tabParam) { initial = tabParam; }
        else if (location.hash === '#specific') { initial = 'specific'; }
        activate(initial);
        var slider = document.getElementById('schemaSlider');
        if (slider) {
            var slides = slider.querySelectorAll('.slide');
            var dots = slider.querySelectorAll('.dot');
            var idx = 0;
            function show(i) {
                slides[idx].classList.add('hidden');
                dots[idx].classList.remove('bg-secondary');
                dots[idx].classList.add('bg-gray-300');
                idx = i;
                slides[idx].classList.remove('hidden');
                dots[idx].classList.add('bg-secondary');
                dots[idx].classList.remove('bg-gray-300');
            }
            dots.forEach(function(d, i){ d.addEventListener('click', function(){ show(i); }); });
            setInterval(function(){ show((idx + 1) % slides.length); }, 4000);
        }
    });
</script>
@endsection