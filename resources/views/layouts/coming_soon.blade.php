<div id="comingSoonModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    <div class="relative h-full w-full flex items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white rounded-2xl border border-gray-200 shadow-xl">
            <button type="button" data-close-modal class="absolute top-4 right-4 p-2 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            <div class="p-6">
                <div class="mx-auto w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="mt-4 text-2xl font-semibold text-gray-900 text-center">Coming soon</h3>
                <p class="mt-2 text-gray-600 text-center">Cette fonctionnalité arrive très bientôt.</p>
                <div class="mt-6 flex items-center justify-center gap-3">
                    <a href="/devis" class="inline-flex items-center gap-2 bg-secondary hover:bg-secondary_2 text-white font-semibold rounded-xl px-5 py-2">
                        Demande devis
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
(function(){
    var modal = document.getElementById('comingSoonModal');
    function open() { modal.classList.remove('hidden'); }
    function close() { modal.classList.add('hidden'); }

    // Expose global function for dynamic elements
    window.showComingSoon = function(productName) {
        var devisLink = modal.querySelector('a[href^="/devis"]');
        if (productName && devisLink) {
            devisLink.href = '/devis?product=' + encodeURIComponent(productName);
        } else if (devisLink) {
            devisLink.href = '/devis';
        }
        open();
    };

    document.addEventListener('click', function(e){
        var t = e.target;
        var btn = t.closest('[data-commander]');
        if (btn) { 
            e.preventDefault(); 
            var productName = btn.getAttribute('data-product-name');
            window.showComingSoon(productName);
        }
        if (t.closest('[data-close-modal]')) { close(); }
    });
    document.addEventListener('keydown', function(e){ if(e.key==='Escape') close(); });
    var overlay = modal.querySelector('.absolute.inset-0');
    overlay.addEventListener('click', function(){ close(); });
})();
</script>