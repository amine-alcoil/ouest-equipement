@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Modifier le devis</h1>
        <a href="{{ route('admin.devis') }}" class="rounded-lg bg-white/10 hover:bg-white/20 text-white px-4 py-2 transition">
            Retour
        </a>
    </div>

    <!-- Edit Form -->
    <div class="bg-white/5 backdrop-blur-sm rounded-lg border border-white/10 p-6">
        <form id="editDevisForm" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Type Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Type de devis *</label>
                    <select id="type" name="type" required class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-secondary focus:border-transparent">
                        <option value="standard" {{ $devis['type'] == 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="specific" {{ $devis['type'] == 'specific' ? 'selected' : '' }}>Spécifique</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Statut *</label>
                    <select name="status" required class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-secondary focus:border-transparent">
                        <option value="nouveau" {{ $devis['status'] == 'nouveau' ? 'selected' : '' }}>Nouveau</option>
                        <option value="en_cours" {{ $devis['status'] == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="envoye" {{ $devis['status'] == 'envoye' ? 'selected' : '' }}>Envoyé</option>
                        <option value="confirme" {{ $devis['status'] == 'confirme' ? 'selected' : '' }}>Confirmé</option>
                        <option value="annule" {{ $devis['status'] == 'annule' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Nom complet *</label>
                    <input type="text" name="name" value="{{ $devis['name'] }}" required class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="Jean Dupont">
                </div>
                
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Email *</label>
                    <input type="email" name="email" value="{{ $devis['email'] }}" required class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="jean@email.com">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Téléphone</label>
                    <input type="tel" name="phone" value="{{ $devis['phone'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="06 12 34 56 78">
                </div>
                
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Entreprise</label>
                    <input type="text" name="company" value="{{ $devis['company'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="SARL Dupont">
                </div>
            </div>

            <!-- Standard Devis Fields -->
            <div id="standardFields" class="space-y-4 {{ $devis['type'] == 'specific' ? 'hidden' : '' }}">
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Produit</label>
                    <input type="text" name="product" value="{{ $devis['product'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="Échangeur 50kW">
                </div>

                <div>
    <label class="block text-white text-sm font-medium mb-2">Quantité *</label>
    <input type="number" name="quantity" min="1"
       value="{{ $devis['quantity'] ?? 1 }}"
       class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white">

</div>

                
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Message</label>
                    <textarea name="message" rows="4" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="Décrivez votre demande...">{{ $devis['message'] ?? '' }}</textarea>
                </div>
            </div>

            <!-- Specific Devis Fields -->
            <div id="specificFields" class="space-y-4 {{ $devis['type'] == 'standard' ? 'hidden' : '' }}">
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Exigences spécifiques</label>
                    <textarea name="requirements" rows="4" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="Décrivez vos exigences spécifiques...">{{ $devis['requirements'] ?? '' }}</textarea>
                </div>
                
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Budget estimé (DA)</label>
                    <input type="number" name="budget" value="{{ $devis['budget'] ?? '' }}" min="0" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="250000">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.devis') }}" class="rounded-lg bg-white/10 hover:bg-white/20 text-white px-6 py-2 transition">
                    Annuler
                </a>
                <button type="submit" class="rounded-lg bg-secondary hover:bg-secondary_2 text-white px-6 py-2 transition">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle fields based on devis type
    document.getElementById('type').addEventListener('change', function() {
        const standardFields = document.getElementById('standardFields');
        const specificFields = document.getElementById('specificFields');
        
        if (this.value === 'standard') {
            standardFields.classList.remove('hidden');
            specificFields.classList.add('hidden');
            standardFields.querySelectorAll('input, textarea').forEach(field => { field.required = true; field.disabled = false; });
            specificFields.querySelectorAll('input, textarea').forEach(field => { field.required = false; field.disabled = true; });
        } else if (this.value === 'specific') {
            standardFields.classList.add('hidden');
            specificFields.classList.remove('hidden');
            standardFields.querySelectorAll('input, textarea').forEach(field => { field.required = false; field.disabled = true; });
            specificFields.querySelectorAll('input, textarea').forEach(field => { field.required = true; field.disabled = false; });
        }
    });
    document.getElementById('type').dispatchEvent(new Event('change'));

    // Form submission
    document.getElementById('editDevisForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        
        submitButton.textContent = 'Mise à jour...';
        submitButton.disabled = true;

        fetch('{{ route("admin.devis.update", $devis["id"]) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message || 'Devis créé avec succès!');
                setTimeout(() => { window.location.href = '{{ route("admin.devis") }}'; }, 300);
            } else {
                showAlert('error', data.message || 'Une erreur est survenue');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Erreur lors de la création du devis.');
        })
        .finally(() => {
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        });
    });
</script>
@endsection