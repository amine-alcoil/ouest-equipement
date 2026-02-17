@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Créer un nouveau devis</h1>
        <a href="{{ route('admin.devis') }}" class="rounded-lg bg-white/10 hover:bg-white/20 text-white px-4 py-2 transition">
            Retour
        </a>
    </div>

    <!-- Create Form -->
    <div class="bg-white/5 backdrop-blur-sm rounded-lg border border-white/10 p-6">
        <form id="createDevisForm" class="space-y-6">
            @csrf
            
            <!-- Type Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Type de devis *</label>
                    <select id="type" name="type" required class="w-full bg-gray-800 border border-white/20 rounded-lg px-3 py-2 text-white focus:ring-2 focus:ring-secondary focus:border-transparent">
                        <option value="">Sélectionner un type</option>
                        <option value="standard">Standard</option>
                        <option value="specific">Spécifique</option>
                    </select>
                </div>
            </div>

            <!-- Client Selection Autocomplete -->
            <div class="mb-6 relative">
                <label for="client_autocomplete" class="block text-white text-sm font-medium mb-2">Sélectionner ou rechercher un client</label>
                <input type="text" id="client_autocomplete" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="Nom du client ou email">
                <div id="client_suggestions" class="absolute z-10 w-full bg-gray-800 border border-white/20 rounded-lg mt-1 max-h-60 overflow-y-auto hidden">
                    <!-- Suggestions will be injected here by JavaScript -->
                </div>
            </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Nom complet *</label>
                    <input type="text" name="name" required class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="Jean Dupont">
                </div>
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Email *</label>
                    <input type="email" name="email" required class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="jean@email.com">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Téléphone</label>
                    <input type="tel" name="phone" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="06 12 34 56 78">
                </div>
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Entreprise</label>
                    <input type="text" name="company" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="SARL Dupont">
                </div>
            </div>

            <!-- ✅ FIXED: Standard Devis Fields -->
            <div id="standardFields" class="space-y-4 hidden">
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Produit *</label>
                    <input type="text" name="product" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="Échangeur 50kW">
                </div>
                
                <div>  <!-- ✅ REMOVED extra nested div -->
                    <label class="block text-white text-sm font-medium mb-2">Quantité *</label>
                    <input type="number" name="quantity" min="1" value="1" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-white text-sm font-medium mb-2">Message *</label>
                    <textarea name="message" rows="4" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="Décrivez votre demande..."></textarea>
                </div>
            </div>

            <!-- Specific Devis Fields -->
            <div id="specificFields" class="space-y-4 hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Type d'échangeur</label>
                        <input type="text" name="type_exchangeur" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="Ex. Condenseur">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Cuivre Ø (mm)</label>
                        <input type="number" step="0.01" name="cuivre_diametre" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Pas Ailette (mm)</label>
                        <input type="number" step="0.01" name="pas_ailette" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">La hauteur (mm)</label>
                        <input type="number" step="0.01" name="hauteur_mm" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">La largeur (mm)</label>
                        <input type="number" step="0.01" name="largeur_mm" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">La longueur (mm)</label>
                        <input type="number" step="0.01" name="longueur_mm" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">La longueur totale (mm)</label>
                        <input type="number" step="0.01" name="longueur_totale_mm" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Collecteur 1</label>
                        <input type="text" name="collecteur1" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="ex. 2">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Ø Collecteur 1 (mm)</label>
                        <input type="number" step="0.01" name="collecteur1_diametre" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Collecteur 2</label>
                        <input type="text" name="collecteur2" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="ex. 2">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Ø Collecteur 2 (mm)</label>
                        <input type="number" step="0.01" name="collecteur2_diametre" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Nombre de tubes</label>
                        <input type="number" name="nombre_tubes" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="ex. 24">
                    </div>
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Géométrie X (mm)</label>
                        <input type="number" step="0.01" name="geometrie_x_mm" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-white text-sm font-medium mb-2">Géométrie Y (mm)</label>
                        <input type="number" step="0.01" name="geometrie_y_mm" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="mm">
                    </div>
                </div>

                <div>
                    <label class="block text-white text-sm font-medium mb-2">Description technique *</label>
                    <textarea name="requirements" rows="4" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent" placeholder="Décrivez vos exigences spécifiques..."></textarea>
                </div>
            </div>

            <!-- Attachments (Available for both) -->
            <div>
                <label class="block text-white text-sm font-medium mb-2">Pièces jointes (schémas, plans)</label>
                <input type="file" name="files[]" multiple class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-white/50 focus:ring-2 focus:ring-secondary focus:border-transparent">
            </div>

           

            <!-- Submit Button -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.devis') }}" class="rounded-lg bg-white/10 hover:bg-white/20 text-white px-6 py-2 transition">
                    Annuler
                </a>
                <button type="submit" class="rounded-lg bg-secondary hover:bg-secondary_2 text-white px-6 py-2 transition">
                    Créer le devis
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // ✅ FIXED JavaScript - Now works perfectly for both types
    document.getElementById('type').addEventListener('change', function() {
        const standardFields = document.getElementById('standardFields');
        const specificFields = document.getElementById('specificFields');
        
        // Hide both sections first
        standardFields.classList.add('hidden');
        specificFields.classList.add('hidden');
        
        // Reset ALL fields
        document.querySelectorAll('#standardFields input, #standardFields textarea, #specificFields input, #specificFields textarea').forEach(field => {
            field.required = false;
            field.disabled = true;
            field.value = '';
        });
        
        if (this.value === 'standard') {
            standardFields.classList.remove('hidden');
            // Enable ONLY standard fields (now works with fixed HTML)
            standardFields.querySelectorAll('input, textarea').forEach(field => {
                field.required = true;
                field.disabled = false;
            });
            document.querySelector('input[name="quantity"]').value = '1'; // Default quantity
        } else if (this.value === 'specific') {
            specificFields.classList.remove('hidden');
            // Enable ONLY specific fields
            specificFields.querySelectorAll('input, textarea').forEach(field => {
                field.required = field.name === 'requirements'; // Only requirements required
                field.disabled = false;
            });
        }
    });

    // Form submission
    document.getElementById('createDevisForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        
        submitButton.textContent = 'Création...';
        submitButton.disabled = true;

        fetch('{{ route("admin.devis.store") }}', {
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

    // Trigger initial state
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('type').dispatchEvent(new Event('change'));
    });

    const allClients = @json($clients); // Make clients data available to JS

    // Client Autocomplete Logic
    const clientAutocompleteInput = document.getElementById('client_autocomplete');
    const clientSuggestionsDiv = document.getElementById('client_suggestions');
    const nameInput = document.querySelector('input[name="name"]');
    const emailInput = document.querySelector('input[name="email"]');
    const phoneInput = document.querySelector('input[name="phone"]');
    const companyInput = document.querySelector('input[name="company"]');

    clientAutocompleteInput.addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        clientSuggestionsDiv.innerHTML = ''; // Clear previous suggestions

        if (searchValue.length > 0) {
            const filteredClients = allClients.filter(client =>
                client.name.toLowerCase().includes(searchValue) ||
                client.email.toLowerCase().includes(searchValue) ||
                (client.company && client.company.toLowerCase().includes(searchValue))
            );

            if (filteredClients.length > 0) {
                filteredClients.forEach(client => {
                    const suggestionItem = document.createElement('div');
                    suggestionItem.classList.add('p-2', 'cursor-pointer', 'hover:bg-white/10', 'text-white');
                    suggestionItem.textContent = `${client.name} (${client.email})`;
                    suggestionItem.dataset.name = client.name;
                    suggestionItem.dataset.email = client.email;
                    suggestionItem.dataset.phone = client.phone || '';
                    suggestionItem.dataset.company = client.company || '';

                    suggestionItem.addEventListener('click', function() {
                        nameInput.value = this.dataset.name;
                        emailInput.value = this.dataset.email;
                        phoneInput.value = this.dataset.phone;
                        companyInput.value = this.dataset.company;
                        clientAutocompleteInput.value = this.dataset.name; // Display selected client name in autocomplete
                        clientSuggestionsDiv.classList.add('hidden'); // Hide suggestions
                    });
                    clientSuggestionsDiv.appendChild(suggestionItem);
                });
                clientSuggestionsDiv.classList.remove('hidden');
            } else {
                clientSuggestionsDiv.classList.add('hidden');
            }
        } else {
            clientSuggestionsDiv.classList.add('hidden');
            // Optionally clear the form fields if autocomplete is cleared
            nameInput.value = '';
            emailInput.value = '';
            phoneInput.value = '';
            companyInput.value = '';
        }
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(event) {
        if (!clientAutocompleteInput.contains(event.target) && !clientSuggestionsDiv.contains(event.target)) {
            clientSuggestionsDiv.classList.add('hidden');
        }
    });
</script>
@endsection
