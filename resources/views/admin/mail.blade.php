<style>.loading-spinner{width:16px;height:16px;border:2px solid rgba(255,255,255,.4);border-top-color:#fff;border-radius:50%;animation:spin .8s linear infinite;display:inline-block;margin-left:8px;vertical-align:middle}@keyframes spin{to{transform:rotate(360deg)}}</style>
<div id="emailModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50"></div>
    <div class="relative mx-auto mt-20 w-full max-w-xl rounded-2xl bg-[#122241] border border-white/10 p-6 shadow-xl">
        <h3 class="text-lg font-semibold text-white mb-4">Envoyer le devis par email</h3>
        <form id="emailForm" class="space-y-4">
            @csrf
            <input type="hidden" name="id" id="devisId">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Nom du destinataire</label>
                    <input id="recipientName" name="recipient_name" type="text" required class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-secondary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Email du destinataire</label>
                    <input id="recipientEmail" name="recipient_email" type="email" required class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-secondary focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-white text-sm font-medium mb-2">Objet</label>
                <input id="emailSubject" name="subject" type="text" required class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-secondary focus:border-transparent">
            </div>

            <div>
                <label class="block text-white text-sm font-medium mb-2">Message</label>
                <textarea id="emailText" name="text" rows="6" required class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-secondary focus:border-transparent"></textarea>
            </div>

            <div>
                <label class="block text-white text-sm font-medium mb-2">Pièce jointe (pdf, doc, docx)</label>
                <input id="emailFile" name="file" type="file" accept=".pdf,.doc,.docx" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-secondary focus:border-transparent">
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="closeEmailModal()" class="rounded-lg bg-white/10 hover:bg-white/20 text-white px-4 py-2 transition">Annuler</button>
                <button type="submit" class="rounded-lg bg-secondary hover:bg-secondary_2 text-white px-4 py-2 transition">Envoyer</button>
            </div>
        </form>
    </div>
</div>

<script>
function setLoading(btn, isLoading, text){
    if(!btn) return;
    if(isLoading){
        btn.dataset.originalHtml = btn.innerHTML;
        btn.innerHTML = (text || 'Chargement...') + ' <span class="loading-spinner"></span>';
        btn.disabled = true;
    } else {
        btn.innerHTML = btn.dataset.originalHtml || btn.innerHTML;
        btn.disabled = false;
    }
}

document.getElementById('emailForm').addEventListener('submit', function(e){
    e.preventDefault();
    const fd = new FormData(this);
    const submit = this.querySelector('button[type="submit"]');
    setLoading(submit, true, 'Envoi...');

    fetch('{{ route("admin.devis.send-email") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: fd
    }).then(r => r.json()).then(d => {
        if (d.success) {
            showNotification('success', 'Succès', 'Email envoyé avec succès!');
            const id = fd.get('id');
            closeEmailModal();
            const row = document.querySelector(`tr[data-id="${id}"]`);
            const select = row?.querySelector('select[data-original-value]');
            if (select) {
                select.value = 'envoye';
                select.dataset.originalValue = 'envoye';
                applyStatusStyle(select);
            }
            if (typeof refreshStatsAfterAction === 'function') { refreshStatsAfterAction(); }
        } else {
            showNotification('error', 'Erreur', 'Erreur lors de l\'envoi de l\'email.');
        }
    }).catch(() => {
        showNotification('error', 'Erreur', 'Erreur réseau');
    }).finally(() => {
        setLoading(submit, false);
    });
});

function showNotification(type, title, message) {
    let container = document.getElementById('alertContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'alertContainer';
        container.className = 'fixed top-20 right-4 z-[9999] max-w-md w-auto space-y-3 pointer-events-none';
        document.body.appendChild(container);
    }

    const colors = {
        success: 'from-emerald-500 to-green-600 border-emerald-400/30',
        error: 'from-red-500 to-rose-600 border-red-400/30',
        warning: 'from-amber-500 to-orange-600 border-amber-400/30'
    };
    
    const icons = {
        success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',
        error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'
    };

    const alertHtml = `
        <div class="alert-notification pointer-events-auto transform transition-all duration-500 ease-out" 
             style="animation: slideIn 0.5s ease-out forwards;">
            <div class="bg-gradient-to-r ${colors[type] || colors.success} text-white rounded-xl shadow-2xl overflow-hidden border">
                <div class="p-4 flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                ${icons[type] || icons.success}
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 pt-0.5">
                        <h4 class="font-semibold text-base mb-1">${title}</h4>
                        <p class="text-sm text-white/95 leading-relaxed">${message}</p>
                    </div>
                    <button onclick="this.closest('.alert-notification').remove()" class="flex-shrink-0 p-1 hover:bg-white/20 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="h-1 bg-white/20">
                    <div class="h-full bg-white/40 alert-progress" style="animation: progress 5s linear;"></div>
                </div>
            </div>
        </div>
    `;

    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = alertHtml;
    const alertElement = tempDiv.firstElementChild;
    container.appendChild(alertElement);

    setTimeout(() => {
        if (alertElement && alertElement.parentNode) {
            alertElement.style.opacity = '0';
            alertElement.style.transform = 'translateX(100%)';
            setTimeout(() => alertElement.remove(), 500);
        }
    }, 5000);
}
</script>