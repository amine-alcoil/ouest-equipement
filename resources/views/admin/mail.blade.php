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
            showAlert('success', 'Email envoyé avec succès!');
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
            showAlert('error', 'Erreur lors de l\'envoi de l\'email.');
        }
    }).catch(() => {
        showAlert('error', 'Erreur réseau');
    }).finally(() => {
        setLoading(submit, false);
    });
});
</script>