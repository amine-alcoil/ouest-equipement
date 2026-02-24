@extends('layouts.app')

@section('title', 'Contact - ALCOIL')

@section('content')
    <!-- Hero -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-[#0f2c4f]"></div>
        <!-- Decorative hero image (subtle, responsive) -->
        <img src="@webp('images/3.jpg')" alt="Contact ALCOIL"
             class="absolute right-0 top-0 h-full w-1/2 object-cover opacity-20 hidden md:block select-none">
        <div class="relative max-w-7xl mx-auto px-4 py-20 text-white">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight">Contactez-nous</h1>
            <p class="mt-4 max-w-2xl text-sm sm:text-base md:text-lg text-slate-200">
                Une question sur nos produits ou un besoin spécifique ? Envoyez-nous un message.
            </p>
            <div class="mt-6 h-1 w-24 bg-secondary rounded"></div>
        </div>
    </section>

    <!-- Flash success -->
    @if (session('success'))
        <div id="contactSuccessOverlay" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="rounded-2xl bg-white p-6 border border-gray-200 shadow-xl w-full max-w-md text-center">
                <div class="mx-auto w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-emerald-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <div class="mt-4 text-lg font-semibold text-gray-900">Merci !</div>
                <div class="mt-1 text-gray-600">{{ session('success') }}</div>
                <div class="mt-6">
                    <button type="button" class="px-4 py-2 rounded-lg bg-secondary hover:bg-secondary_2 text-white" onclick="document.getElementById('contactSuccessOverlay').remove()">OK</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Contact content -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Info + Contact Image -->
            <aside class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <h2 class="text-2xl font-bold text-slate-900">Nos coordonnées</h2>
                <div class="mt-4 space-y-4 text-slate-700">
                    <!-- Address -->
                    <div class="flex items-start gap-3">
                        <!-- Map-pin icon -->
                        <svg id='Google_Maps_24' width='30' height='30' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'><rect width='24' height='24' stroke='currentColor' opacity='0'/>


<g transform="matrix(0.42 0 0 0.42 12 12)" >
<path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: var(--color-secondary); fill-rule: nonzero; opacity: 1;" transform=" translate(-25, -25)" d="M 25 1 C 15.62003 1 8 8.6200302 8 18 C 8 21.934852 9.338472 25.56337 11.578125 28.441406 C 11.579015 28.442506 11.579178 28.444213 11.580078 28.445312 C 12.881845 30.20015 15.540286 33.445552 17.984375 37.027344 C 20.433903 40.617106 22.609579 44.57629 22.947266 47.255859 C 22.947885524836067 47.26042077402914 22.94853654035462 47.2649782160003 22.949219 47.269531 C 23.087696 48.259336 23.94612 49 24.939453 49 C 25.933113 49 26.813207 48.253225 26.933594 47.25 C 27.264043 44.644456 29.344744 40.842893 31.728516 37.353516 C 34.113791 33.861937 36.753391 30.641964 38.150391 28.779297 C 38.140221 28.792867 38.188921 28.726376 38.212891 28.697266 C 40.576728 25.777652 42 22.049173 42 18 C 42 8.6200302 34.37997 1 25 1 z M 25 3 C 25.609143 3 26.187453 3.1095293 26.777344 3.1796875 L 19.857422 11.634766 L 14.494141 7.2988281 C 17.198552 4.64388 20.900052 3 25 3 z M 29.009766 3.6074219 C 33.078798 4.7368158 36.415915 7.5073508 38.302734 11.193359 L 32.947266 17.740234 C 32.801586 13.457791 29.317099 10 25 10 C 24.481389 10 24.028565 10.202032 23.537109 10.296875 L 29.009766 3.6074219 z M 13.201172 8.8242188 L 18.591797 13.181641 L 10.794922 22.710938 C 10.303652 21.227167 10 19.653974 10 18 C 10 14.522139 11.223662 11.365018 13.201172 8.8242188 z M 25 12 C 28.33081 12 31 14.66919 31 18 C 31 21.33081 28.33081 24 25 24 C 21.66919 24 19 21.33081 19 18 C 19 14.66919 21.66919 12 25 12 z M 39.173828 13.285156 C 39.668281 14.775352 40 16.341297 40 18 C 40 21.586143 38.743589 24.870329 36.652344 27.449219 C 36.632933707323225 27.473740626805387 36.614684319748704 27.499159225955594 36.597656 27.525391 C 36.662996 27.427381 36.628771 27.476078 36.550781 27.580078 C 35.217781 29.357411 32.525897 32.638688 30.076172 36.224609 C 27.626447 39.81053 25.368819 43.661536 24.947266 47.005859 C 24.94726027729332 47.00781232914186 24.94726027729332 47.00976567085814 24.947266 47.011719 C 24.94765 47.008453 24.965793 47 24.939453 47 C 24.932753 47 24.931187 47.002388 24.929688 46.992188 C 24.52099 43.785616 22.399697 40.101719 20.078125 36.626953 L 39.173828 13.285156 z M 17.046875 18.230469 C 17.176564 22.527243 20.672521 26 25 26 C 25.504771 26 25.944669 25.800899 26.423828 25.710938 L 18.898438 34.910156 C 16.648702 31.73408 14.255696 28.694038 13.173828 27.234375 C 13.168701283587056 27.22779978971692 13.16349254301128 27.221288947337047 13.158203 27.214844 C 12.567692 26.456407 12.211585 25.54039 11.773438 24.675781 L 17.046875 18.230469 z" stroke-linecap="round" />
</g>
</svg>
                        <div>
                            <div class="font-semibold">Adresse</div>
                            <div>Zone Industrielle, Sidi Bel Abbes, Algérie</div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start gap-3">
                        <!-- At-sign icon -->
                        <svg id='Important_Mail_24' width='30' height='30' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'><rect width='24' height='24' stroke='none' fill='#000000' opacity='0'/>


<g transform="matrix(0.4 0 0 0.4 12 12)" >
<path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: var(--color-secondary); fill-rule: nonzero; opacity: 1;" transform=" translate(-25, -25)" d="M 0 7 L 0 43 L 50 43 L 50 7 Z M 2 9 L 48 9 L 48 41 L 2 41 Z M 4 13.917969 L 4 16.277344 L 25 28.179688 L 46 16.277344 L 46 13.917969 L 25 25.820313 Z" stroke-linecap="round" />
</g>
</svg>
                        <div>
                            <div class="font-semibold">Email</div>
                            <a href="mailto:contact@alcoil.dz" class="hover:text-secondary transition">commercial@ouest-equipement.com</a>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex items-start gap-3">
                        <!-- Phone handset icon -->
                        <svg id='Phone_24' width='30' height='30' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'><rect width='24' height='24' stroke='none' fill='#000000' opacity='0'/>


<g transform="matrix(0.45 0 0 0.45 12 12)" >
<path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: var(--color-secondary); fill-rule: nonzero; opacity: 1;" transform=" translate(-25.01, -24.99)" d="M 11.839844 2.988281 C 11.070313 2.925781 10.214844 3.148438 9.425781 3.703125 C 8.730469 4.1875 7.230469 5.378906 5.828125 6.726563 C 5.128906 7.398438 4.460938 8.097656 3.945313 8.785156 C 3.425781 9.472656 2.972656 10.101563 3 11.015625 C 3.027344 11.835938 3.109375 14.261719 4.855469 17.980469 C 6.601563 21.695313 9.988281 26.792969 16.59375 33.402344 C 23.203125 40.011719 28.300781 43.398438 32.015625 45.144531 C 35.730469 46.890625 38.160156 46.972656 38.980469 47 C 39.890625 47.027344 40.519531 46.574219 41.207031 46.054688 C 41.894531 45.535156 42.59375 44.871094 43.265625 44.171875 C 44.609375 42.769531 45.800781 41.269531 46.285156 40.574219 C 47.390625 39 47.207031 37.140625 45.976563 36.277344 C 45.203125 35.734375 38.089844 31 37.019531 30.34375 C 35.933594 29.679688 34.683594 29.980469 33.566406 30.570313 C 32.6875 31.035156 30.308594 32.398438 29.628906 32.789063 C 29.117188 32.464844 27.175781 31.171875 23 26.996094 C 18.820313 22.820313 17.53125 20.878906 17.207031 20.367188 C 17.597656 19.6875 18.957031 17.320313 19.425781 16.425781 C 20.011719 15.3125 20.339844 14.050781 19.640625 12.957031 C 19.347656 12.492188 18.015625 10.464844 16.671875 8.429688 C 15.324219 6.394531 14.046875 4.464844 13.714844 4.003906 L 13.714844 4 C 13.28125 3.402344 12.605469 3.050781 11.839844 2.988281 Z M 11.65625 5.03125 C 11.929688 5.066406 12.09375 5.175781 12.09375 5.175781 C 12.253906 5.398438 13.65625 7.5 15 9.53125 C 16.34375 11.566406 17.714844 13.652344 17.953125 14.03125 C 17.992188 14.089844 18.046875 14.753906 17.65625 15.492188 L 17.65625 15.496094 C 17.214844 16.335938 15.15625 19.933594 15.15625 19.933594 L 14.871094 20.4375 L 15.164063 20.9375 C 15.164063 20.9375 16.699219 23.527344 21.582031 28.410156 C 26.46875 33.292969 29.058594 34.832031 29.058594 34.832031 L 29.558594 35.125 L 30.0625 34.839844 C 30.0625 34.839844 33.652344 32.785156 34.5 32.339844 C 35.238281 31.953125 35.902344 32.003906 35.980469 32.050781 C 36.671875 32.476563 44.355469 37.582031 44.828125 37.914063 C 44.84375 37.925781 45.261719 38.558594 44.652344 39.425781 L 44.648438 39.425781 C 44.28125 39.953125 43.078125 41.480469 41.824219 42.785156 C 41.195313 43.4375 40.550781 44.046875 40.003906 44.457031 C 39.457031 44.867188 38.96875 44.996094 39.046875 45 C 38.195313 44.972656 36.316406 44.953125 32.867188 43.332031 C 29.417969 41.714844 24.496094 38.476563 18.007813 31.984375 C 11.523438 25.5 8.285156 20.578125 6.664063 17.125 C 5.046875 13.675781 5.027344 11.796875 5 10.949219 C 5.003906 11.027344 5.132813 10.535156 5.542969 9.988281 C 5.953125 9.441406 6.558594 8.792969 7.210938 8.164063 C 8.519531 6.910156 10.042969 5.707031 10.570313 5.339844 L 10.570313 5.34375 C 11.003906 5.039063 11.382813 5 11.65625 5.03125 Z" stroke-linecap="round" />
</g>
</svg>
                        <div>
                            <div class="font-semibold">Téléphone</div>
                            <div class="relative inline-block group" id="phoneContainer">
                                <button type="button" class="hover:text-secondary transition text-left focus:outline-none" id="phoneDropdownBtn">
                                    0555 02 60 38
                                </button>
                                <div id="phoneMenu" class="hidden absolute left-0 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-xl z-[60] overflow-hidden">
                                   
                                    <a href="tel:0555026038" class="block px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 hover:text-secondary transition-colors flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        0555 02 60 38
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        (function() {
                            const btn = document.getElementById('phoneDropdownBtn');
                            const menu = document.getElementById('phoneMenu');
                            
                            btn.addEventListener('click', function(e) {
                                e.stopPropagation();
                                menu.classList.toggle('hidden');
                            });

                            document.addEventListener('click', function() {
                                menu.classList.add('hidden');
                            });

                            menu.addEventListener('click', function(e) {
                                e.stopPropagation();
                            });
                        })();
                    </script>
                </div>

                <!-- Contact image block -->
                <figure class="mt-8 rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                    <img src="@webp('images/1.png')" alt="Nous contacter"
                         class="w-full h-56 md:h-72 object-cover select-none">
                </figure>

                <!-- Map placeholder -->
                
            </aside>

            <!-- Form (rounded, padded, translucent inputs) -->
            <form method="POST" action="{{ route('contact.submit') }}"
                  class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                @csrf
                <h2 class="text-2xl font-bold text-slate-900">Envoyer un message</h2>
                <p class="mt-2 text-slate-600">Nous revenons vers vous au plus vite.</p>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nom complet</label>
                        <input type="text" name="name" required
                               class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-2.5 sm:py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition text-sm sm:text-base"
                               placeholder="Votre nom">
                        @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Email</label>
                        <input type="email" name="email" required
                               class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-2.5 sm:py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition text-sm sm:text-base"
                               placeholder="vous@exemple.com">
                        @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Téléphone (optionnel)</label>
                        <input type="text" name="phone"
                               class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-2.5 sm:py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition text-sm sm:text-base"
                               placeholder="+213 ...">
                        @error('phone')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Message</label>
                        <textarea name="message" rows="5" required
                                  class="mt-1 w-full rounded-lg border border-slate-300/70 bg-white/70 backdrop-blur-sm px-4 py-2.5 sm:py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-secondary transition text-sm sm:text-base"
                                  placeholder="Décrivez votre besoin"></textarea>
                        @error('message')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-secondary hover:bg-secondary_2 text-white font-semibold px-5 py-3 rounded-lg transition-colors">
                        Envoyer
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M22 2L11 13"/>
                            <path d="M22 2l-6 20-5-9-9-5 20-6z"/>
                        </svg>
                    </button>
                    <span class="text-slate-500 text-sm">Nous répondons sous 24–48h.</span>
                </div>
            </form>
        </div>
    </section>
@endsection