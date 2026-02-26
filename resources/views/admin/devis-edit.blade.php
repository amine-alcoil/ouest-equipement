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
        <form id="editDevisForm" class="space-y-6" enctype="multipart/form-data">
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
                        <label class="block text-white text-sm font-medium mb-2">Collecteur 1 (mm)</label>
                        <input type="number" step="0.01" name="collecteur1_mm" value="{{ $devis['collecteur1_mm'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Ø Collecteur 1 (mm)</label>
                        <input type="number" step="0.01" name="collecteur1_diametre" value="{{ $devis['collecteur1_diametre'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Collecteur 2 (mm)</label>
                        <input type="number" step="0.01" name="collecteur2_mm" value="{{ $devis['collecteur2_mm'] ?? '' }}" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
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

                <!-- Attachments (Specific only) -->
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Pièces jointes (schémas, plans)</label>
                    <input type="file" name="files[]" multiple class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent">
                    
                    @if(!empty($devis['attachments']))
                        <div class="mt-6">
                            <p class="text-sm font-semibold text-white/60 uppercase tracking-widest mb-3">Fichiers actuels</p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4" id="attachments-list">
                                @foreach($devis['attachments'] as $attachment)
                                    @php
                                        $ext = pathinfo($attachment, PATHINFO_EXTENSION);
                                        $isImg = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    <div class="relative group aspect-square rounded-xl overflow-hidden border border-white/10 bg-white/5 hover:border-secondary/50 transition-all">
                                        @if($isImg)
                                            <img src="{{ $attachment }}" class="w-full h-full object-cover">
                                        @elseif(strtolower($ext) === 'pdf')
                                            <div class="w-full h-full flex flex-col items-center justify-center bg-red-500/10 text-red-400">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                <span class="text-[10px] mt-1 font-bold">PDF</span>
                                            </div>
                                        @elseif(in_array(strtolower($ext), ['dwg', 'dxf']))
                                            <div class="w-full h-full flex flex-col items-center justify-center bg-indigo-500/10 text-indigo-400">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.483V8.517a2 2 0 011.553-1.943L9 5.236m10 14.764l5.447-2.724A2 2 0 0021 15.483V8.517a2 2 0 00-1.553-1.943L15 5.236m-6 0V20m0-18l6 3.236m-6 0l6 3.236M9 20l6-3.236"/></svg>
                                                <span class="text-[10px] mt-1 font-bold">CAD</span>
                                            </div>
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center bg-gray-500/10 text-gray-400">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                <span class="text-[10px] mt-1 font-bold">{{ strtoupper($ext) }}</span>
                                            </div>
                                        @endif

                                        <!-- Overlay Actions -->
                                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center gap-2 transition-opacity">
                                            <a href="{{ $attachment }}" target="_blank" class="p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition-all transform translate-y-2 group-hover:translate-y-0" title="Voir">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            <button type="button" onclick="deleteAttachment('{{ $attachment }}', this)" class="p-2 rounded-full bg-red-600/80 hover:bg-red-600 text-white transition-all transform translate-y-2 group-hover:translate-y-0" title="Supprimer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
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

<!-- Custom Confirmation Modal -->
<div id="deleteFileModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDeleteFileModal()"></div>
    <div class="relative mx-auto max-w-sm w-[92%] transform transition-all">
        <div class="rounded-2xl bg-[#122241] border border-white/10 shadow-2xl overflow-hidden">
            <div class="px-6 pt-6 pb-4 text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-red-500/10 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Supprimer le fichier ?</h3>
                <p class="text-white/60 text-sm">Cette action est irréversible. Êtes-vous sûr de vouloir continuer ?</p>
            </div>
            <div class="px-6 py-4 bg-white/5 flex items-center gap-3">
                <button onclick="closeDeleteFileModal()" class="flex-1 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-medium transition-colors">
                    Annuler
                </button>
                <button id="confirmDeleteFileBtn" class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold transition-all shadow-lg shadow-red-600/20">
                    Supprimer
                </button>
            </div>
        </div>
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

    let attachmentToDelete = null;
    let btnToDelete = null;

    function closeDeleteFileModal() {
        document.getElementById('deleteFileModal').classList.add('hidden');
        attachmentToDelete = null;
        btnToDelete = null;
    }

    document.getElementById('confirmDeleteFileBtn').addEventListener('click', function() {
        if (!attachmentToDelete || !btnToDelete) return;
        
        const url = attachmentToDelete;
        const btnElement = btnToDelete;
        const itemContainer = btnElement.closest('.relative');
        const originalContent = btnElement.innerHTML;

        closeDeleteFileModal();

        // Show spinner on the card button
        btnElement.innerHTML = '<svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        btnElement.disabled = true;

        fetch('{{ route("admin.devis.delete-attachment", $devis["id"]) }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ attachment_url: url })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Erreur HTTP ' + response.status);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                itemContainer.remove();
                showAlert('success', 'Fichier supprimé avec succès');
                
                const list = document.getElementById('attachments-list');
                if (list && list.children.length === 0) {
                    const container = list.closest('.mt-6');
                    if (container) container.remove();
                }
            } else {
                throw new Error(data.message || 'Erreur inconnue');
            }
        })
        .catch(error => {
            console.error('Delete Attachment Error:', error);
            showAlert('error', error.message || 'Erreur de connexion');
            btnElement.innerHTML = originalContent;
            btnElement.disabled = false;
        });
    });

    // Function to delete attachment
    function deleteAttachment(url, btnElement) {
        attachmentToDelete = url;
        btnToDelete = btnElement;
        document.getElementById('deleteFileModal').classList.remove('hidden');
    }

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