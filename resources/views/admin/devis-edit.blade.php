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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Type d'échangeur</label>
                        <input type="text" name="type_exchangeur" value="{{ $devis['type_exchangeur'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="Ex. Condenseur">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Cuivre Ø (mm)</label>
                        <input type="number" step="0.01" name="cuivre_diametre" value="{{ $devis['cuivre_diametre'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Pas Ailette (mm)</label>
                        <input type="number" step="0.01" name="pas_ailette" value="{{ $devis['pas_ailette'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">La hauteur (mm)</label>
                        <input type="number" step="0.01" name="hauteur_mm" value="{{ $devis['hauteur_mm'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">La largeur (mm)</label>
                        <input type="number" step="0.01" name="largeur_mm" value="{{ $devis['largeur_mm'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">La longueur (mm)</label>
                        <input type="number" step="0.01" name="longueur_mm" value="{{ $devis['longueur_mm'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">La longueur totale (mm)</label>
                        <input type="number" step="0.01" name="longueur_totale_mm" value="{{ $devis['longueur_totale_mm'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Collecteur 1</label>
                        <input type="text" name="collecteur1" value="{{ $devis['collecteur1'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="ex. 2">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Ø Collecteur 1 (mm)</label>
                        <input type="number" step="0.01" name="collecteur1_diametre" value="{{ $devis['collecteur1_diametre'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Collecteur 2</label>
                        <input type="text" name="collecteur2" value="{{ $devis['collecteur2'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="ex. 2">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Ø Collecteur 2 (mm)</label>
                        <input type="number" step="0.01" name="collecteur2_diametre" value="{{ $devis['collecteur2_diametre'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Nombre de tubes</label>
                        <input type="number" name="nombre_tubes" value="{{ $devis['nombre_tubes'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="ex. 24">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Géométrie X (mm)</label>
                        <input type="number" step="0.01" name="geometrie_x_mm" value="{{ $devis['geometrie_x_mm'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Géométrie Y (mm)</label>
                        <input type="number" step="0.01" name="geometrie_y_mm" value="{{ $devis['geometrie_y_mm'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                </div>

                <div>
                    <label class="block text-white text-sm font-medium mb-2">Description technique</label>
                    <textarea name="requirements" rows="4" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="Décrivez vos exigences spécifiques...">{{ $devis['requirements'] ?? '' }}</textarea>
                </div>
            </div>

            <!-- Attachments (Available for both) -->
            <div>
                <label class="block text-white text-sm font-medium mb-2">Pièces jointes (schémas, plans)</label>
                <input type="file" name="files[]" multiple class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent">
                
                @if(!empty($devis['attachments']))
                    <div class="mt-4">
                        <p class="text-sm font-medium text-white mb-2">Fichiers actuels :</p>
                        <ul class="space-y-2">
                            @foreach($devis['attachments'] as $attachment)
                                <li class="flex items-center text-sm text-white/80 bg-white/5 rounded px-3 py-2">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    <a href="{{ $attachment }}" target="_blank" class="hover:text-secondary hover:underline truncate">
                                        {{ basename($attachment) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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
        
        // Hide both sections first
        standardFields.classList.add('hidden');
        specificFields.classList.add('hidden');
        
        // Reset ALL fields state (but not values, as we are editing)
        document.querySelectorAll('#standardFields input, #standardFields textarea, #specificFields input, #specificFields textarea').forEach(field => {
            field.required = false;
            field.disabled = true;
        });

        if (this.value === 'standard') {
            standardFields.classList.remove('hidden');
            // Enable ONLY standard fields
            standardFields.querySelectorAll('input, textarea').forEach(field => {
                field.required = true;
                field.disabled = false;
            });
        } else if (this.value === 'specific') {
            specificFields.classList.remove('hidden');
            // Enable ONLY specific fields
            specificFields.querySelectorAll('input, textarea').forEach(field => {
                field.required = field.name === 'requirements'; // Only requirements required
                field.disabled = false;
            });
        }
    });

    // Trigger change event on page load to set initial state
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