@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Gestion des Devis</h1>
        <div class="flex items-center gap-3">
            <button onclick="openCreateModal()" class="px-3 py-2 rounded-lg bg-[#1b334f] border border-white/10 hover:bg-[#234161]">
                + Nouveau Devis
            </button>
            <button onclick="exportDevis()" class="rounded-lg bg-secondary hover:bg-secondary_2 text-white px-4 py-2 transition">
                Exporter
            </button>
        </div>
    </div>

    <!-- Stats Cards - ADD these data-stat attributes -->
<div class="grid sm:grid-cols-1 xl:grid-cols-6 gap-4">
    <!-- Total Devis -->
    <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
        <div class="text-white/70 text-sm">Total Devis</div>
        <div class="mt-2 text-3xl font-bold text-secondary" data-stat="total">{{ $totalDevis ?? 0 }}</div>
        <!-- ... rest unchanged -->
    </div>

    <!-- Devis Standard -->
    <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
        <div class="text-white/70 text-sm">Devis Standard</div>
        <div class="mt-2 text-3xl font-bold text-secondary" data-stat="standard">{{ $stats['standard'] ?? 0 }}</div>
        <!-- ... -->
    </div>

    <!-- Devis Spécifiques -->
    <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
        <div class="text-white/70 text-sm">Devis Spécifiques</div>
        <div class="mt-2 text-3xl font-bold text-secondary" data-stat="specific">{{ $stats['specific'] ?? 0 }}</div>
        <!-- ... -->
    </div>

   {{-- En attente (Pending: nouveau + en_cours + envoye) --}}
<div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
    <div class="text-white/70 text-sm">En attente</div>
    <div class="mt-2 text-3xl font-bold text-orange-400" data-stat="pending">
    {{ $stats['pending'] ?? 0 }}
</div>
    
</div>


    <!-- Confirmés -->
    <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
        <div class="text-white/70 text-sm">Confirmés</div>
        <div class="mt-2 text-3xl font-bold text-emerald-400" data-stat="confirme">{{ $stats['status']['confirme'] ?? 0 }}</div>
        <!-- ... -->
    </div>

    <!-- Annulé -->
    <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
        <div class="text-white/70 text-sm">Annulés</div>
        <div class="mt-2 text-3xl font-bold text-red-400" data-stat="annule">{{ $stats['status']['annule'] ?? 0 }}</div>
        <!-- ... -->
    </div>
</div>


    <!-- Tabs -->
    <div class="border-b border-white/10">
        <nav class="flex space-x-8">
            <button onclick="showTab('standard')" id="tab-standard" class="tab-button py-2 px-1 border-b-2 border-secondary text-white font-medium">
                Devis Standard
            </button>
            <button onclick="showTab('specific')" id="tab-specific" class="tab-button py-2 px-1 border-b-2 border-transparent text-white/70 hover:text-white font-medium">
                Devis Spécifiques
            </button>
        </nav>
    </div>

    <!-- Standard Devis Table -->
    <div id="content-standard" class="tab-content relative min-h-[200px]">
        <!-- Loading Overlay -->
        <div id="tableLoading" class="hidden absolute inset-0 z-50 flex items-center justify-center bg-[#122241]/50 backdrop-blur-[2px] rounded-2xl transition-all duration-300">
            <div class="relative">
                <div class="w-12 h-12 rounded-full border-4 border-white/10"></div>
                <div class="absolute top-0 left-0 w-12 h-12 rounded-full border-4 border-secondary border-t-transparent animate-spin"></div>
            </div>
        </div>

        <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold">Devis Standard</h3>
                <div class="flex items-center gap-2">
                    <input id="search-standard" oninput="filterTable('standard')" type="text" placeholder="Rechercher..." class="rounded-lg bg-white/10 border border-white/15 px-3 py-1.5 text-sm">
                    <select id="filter-standard" onchange="filterTable('standard')" class="rounded-lg bg-[#122241] border border-white/15 px-3 py-1.5 text-sm text-white">
                        <option value="">Tous les statuts</option>
                        <option value="nouveau">Nouveau</option>
                        <option value="en_cours">En cours</option>
                        <option value="envoye">Envoyé</option>
                        <option value="confirme">Confirmé</option>
                        <option value="annule">Annulé</option>
                    </select>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-white/60 border-b border-white/10">
                            <th class="text-left py-3 pr-4">Nom</th>
                            <th class="text-left py-3 pr-4">ID</th>
                            <th class="text-left py-3 pr-4">Email</th>
                            <th class="text-left py-3 pr-4">Téléphone</th>
                            <th class="text-left py-3 pr-4">Entreprise</th>
                            <th class="text-left py-3 pr-4">Produit</th>
                            <th class="text-left py-3 pr-4">Quantité</th>
                            <th class="text-left py-3 pr-4">Date</th>
                            <th class="text-left py-3 pr-4">Statut</th>
                            <th class="text-left py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-white/80">
                        @forelse(($standardDevis ?? []) as $devis)
                        <tr class="border-b border-white/10 hover:bg-white/5" data-id="{{ $devis['id'] }}" data-phone="{{ $devis['phone'] ?? '' }}" data-message="{{ $devis['message'] ?? '' }}" data-attachments='@json($devis["attachments"] ?? [])'>
                            <td class="py-3 pr-4">{{ $devis['name'] }}</td>
                            <td class="py-3 pr-4"><span class="inline-block rounded bg-white/10 px-2 py-0.5 text-xs">#{{ $devis['id'] }}</span></td>
                            <td class="py-3 pr-4">{{ $devis['email'] }}</td>
                            <td class="py-3 pr-4">{{ $devis['phone'] ?? '-' }}</td>
                            <td class="py-3 pr-4">{{ $devis['company'] }}</td>
                            <td class="py-3 pr-4">{{ $devis['product'] }}</td>
                            <td class="py-3 pr-4">{{ $devis['quantity'] }}</td>
                            <td class="py-3 pr-4">{{ \Carbon\Carbon::parse($devis['created_at'])->format('d/m/Y') }}</td>
                            <td class="py-3 pr-4">
                                <select onchange="updateStatus('{{ $devis['id'] }}', this.value, this)" class="text-xs rounded bg-white/10 border border-white/20 px-2 py-1" data-original-value="{{ $devis['status'] }}">
                                    <option value="nouveau" {{ $devis['status'] == 'nouveau' ? 'selected' : '' }}>Nouveau</option>
                                    <option value="en_cours" {{ $devis['status'] == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="envoye" {{ $devis['status'] == 'envoye' ? 'selected' : '' }}>Envoyé</option>
                                    <option value="confirme" {{ $devis['status'] == 'confirme' ? 'selected' : '' }}>Confirmé</option>
                                    <option value="annule" {{ $devis['status'] == 'annule' ? 'selected' : '' }}>Annulé</option>
                                </select>
                            </td>
                            <td class="py-3">
                                <div class="flex items-center gap-1">
                                    <button onclick="openEmailModal('{{ addslashes($devis['name']) }}', '{{ $devis['email'] }}', 'standard', '{{ $devis['id'] }}')"
                                            class="text-secondary hover:text-secondary_2 text-xs px-2 py-1 rounded hover:bg-secondary/20"
                                            title="Envoyer email">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                    <button onclick="viewDevis('{{ $devis['id'] }}')"
                                            class="text-blue-400 hover:text-blue-300 text-xs px-2 py-1 rounded hover:bg-blue-500/20"
                                            title="Voir détails">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <a href="{{ route('admin.devis.export-pdf', ['id' => $devis['id']]) }}" target="_blank"
                                       class="text-red-400 hover:text-red-300 text-xs px-2 py-1 rounded hover:bg-red-500/20"
                                       title="Exporter PDF">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                          <path d="M6 2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm9 1v5h5"/>
                                          <path d="M8 17h2.5c1.4 0 2.5-1.1 2.5-2.5S11.9 12 10.5 12H8v5zm2.3-3.4c.6 0 1 .4 1 1s-.4 1-1 1H9.1v-2h1.2zM14 17h1.6c1.3 0 2.4-.7 2.4-2.2 0-1.5-1.1-2.3-2.4-2.3H14v4.5zm1.6-3.5c.6 0 1.1.3 1.1 1.2s-.5 1.1-1.1 1.1H15v-2.3h.6z"/>
                                          <path d="M7 9h10v1H7z"/>
                                        </svg>
                                    </a>
                                    <button onclick="editDevis('{{ $devis['id'] }}')"
                                            class="text-emerald-400 hover:text-emerald-300 text-xs px-2 py-1 rounded hover:bg-emerald-500/20"
                                            title="Modifier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="deleteDevis('{{ $devis['id'] }}', '{{ addslashes($devis['name']) }}')"
                                            class="text-red-400 hover:text-red-300 text-xs px-2 py-1 rounded hover:bg-red-500/20"
                                            title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-white/60">
                                Aucun devis standard trouvé
                            </td>
                        </tr>
                        @endforelse
                        <tr data-empty class="hidden">
                            <td colspan="10" class="text-center py-8 text-white/60">Aucun résultat</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Specific Devis Table -->
    <div id="content-specific" class="tab-content hidden">
        <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold">Devis Spécifiques</h3>
                <div class="flex items-center gap-2">
                    <input id="search-specific" oninput="filterTable('specific')" type="text" placeholder="Rechercher..." class="rounded-lg bg-white/10 border border-white/15 px-3 py-1.5 text-sm">
                    <select id="filter-specific" onchange="filterTable('specific')" class="rounded-lg bg-[#122241] border border-white/15 px-3 py-1.5 text-sm text-white">
                        <option value="">Tous les statuts</option>
                        <option value="nouveau">Nouveau</option>
                        <option value="en_cours">En cours</option>
                        <option value="envoye">Envoyé</option>
                        <option value="confirme">Confirmé</option>
                        <option value="annule">Annulé</option>
                    </select>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-white/60 border-b border-white/10">
                            <th class="text-left py-3 pr-4">Nom</th>
                            <th class="text-left py-3 pr-4">ID</th>
                            <th class="text-left py-3 pr-4">Email</th>
                            <th class="text-left py-3 pr-4">Téléphone</th>
                            <th class="text-left py-3 pr-4">Entreprise</th>
                            <th class="text-left py-3 pr-4">Type d'échangeur</th>
                            <th class="text-left py-3 pr-4">Date</th>
                            <th class="text-left py-3 pr-4">Statut</th>
                            <th class="text-left py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-white/80">
                        @forelse(($specificDevis ?? []) as $devis)
                        <tr class="border-b border-white/10 hover:bg-white/5" data-id="{{ $devis['id'] }}" data-phone="{{ $devis['phone'] ?? '' }}" data-requirements="{{ $devis['requirements'] ?? '' }}" data-type_exchangeur="{{ $devis['type_exchangeur'] ?? '' }}" data-cuivre_diametre="{{ $devis['cuivre_diametre'] ?? '' }}" data-pas_ailette="{{ $devis['pas_ailette'] ?? '' }}" data-hauteur_mm="{{ $devis['hauteur_mm'] ?? '' }}" data-largeur_mm="{{ $devis['largeur_mm'] ?? '' }}" data-longueur_mm="{{ $devis['longueur_mm'] ?? '' }}" data-longueur_totale_mm="{{ $devis['longueur_totale_mm'] ?? '' }}" data-nombre_tubes="{{ $devis['nombre_tubes'] ?? '' }}" data-geometrie_x_mm="{{ $devis['geometrie_x_mm'] ?? '' }}" data-geometrie_y_mm="{{ $devis['geometrie_y_mm'] ?? '' }}" data-collecteur1_nb="{{ $devis['collecteur1_nb'] ?? '' }}" data-collecteur2_nb="{{ $devis['collecteur2_nb'] ?? '' }}" data-collecteur1_diametre="{{ $devis['collecteur1_diametre'] ?? '' }}" data-collecteur2_diametre="{{ $devis['collecteur2_diametre'] ?? '' }}" data-attachments='@json($devis["attachments"] ?? [])'>
                            <td class="py-3 pr-4">{{ $devis['name'] }}</td>
                            <td class="py-3 pr-4"><span class="inline-block rounded bg-white/10 px-2 py-0.5 text-xs">{{ $devis['id'] }}</span></td>
                            <td class="py-3 pr-4">{{ $devis['email'] }}</td>
                            <td class="py-3 pr-4">{{ $devis['phone'] ?? '-' }}</td>
                            <td class="py-3 pr-4">{{ $devis['company'] }}</td>
                            <td class="py-3 pr-4">{{ $devis['type_exchangeur'] ?? '-' }}</td>
                            <td class="py-3 pr-4">{{ \Carbon\Carbon::parse($devis['created_at'])->format('d/m/Y') }}</td>
                            <td class="py-3 pr-4">
                                <select onchange="updateStatus('{{ $devis['id'] }}', this.value, this)" class="text-xs rounded bg-white/10 border border-white/20 px-2 py-1" data-original-value="{{ $devis['status'] }}">
                                    <option value="nouveau" {{ $devis['status'] == 'nouveau' ? 'selected' : '' }}>Nouveau</option>
                                    <option value="en_cours" {{ $devis['status'] == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="envoye" {{ $devis['status'] == 'envoye' ? 'selected' : '' }}>Envoyé</option>
                                    <option value="confirme" {{ $devis['status'] == 'confirme' ? 'selected' : '' }}>Confirmé</option>
                                    <option value="annule" {{ $devis['status'] == 'annule' ? 'selected' : '' }}>Annulé</option>
                                </select>
                            </td>
                            <td class="py-3">
                                <div class="flex items-center gap-1">
                                    <button onclick="openEmailModal('{{ addslashes($devis['name']) }}', '{{ $devis['email'] }}', 'specific', '{{ $devis['id'] }}')" 
                                            class="text-secondary hover:text-secondary_2 text-xs px-2 py-1 rounded hover:bg-secondary/20"
                                            title="Envoyer email">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                    <button onclick="viewDevis('{{ $devis['id'] }}')" 
                                            class="text-blue-400 hover:text-blue-300 text-xs px-2 py-1 rounded hover:bg-blue-500/20"
                                            title="Voir détails">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <a href="{{ route('admin.devis.export-pdf', ['id' => $devis['id']]) }}" target="_blank"
                                       class="text-red-400 hover:text-red-300 text-xs px-2 py-1 rounded hover:bg-red-500/20"
                                       title="Exporter PDF">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                          <path d="M6 2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm9 1v5h5"/>
                                          <path d="M8 17h2.5c1.4 0 2.5-1.1 2.5-2.5S11.9 12 10.5 12H8v5zm2.3-3.4c.6 0 1 .4 1 1s-.4 1-1 1H9.1v-2h1.2zM14 17h1.6c1.3 0 2.4-.7 2.4-2.2 0-1.5-1.1-2.3-2.4-2.3H14v4.5zm1.6-3.5c.6 0 1.1.3 1.1 1.2s-.5 1.1-1.1 1.1H15v-2.3h.6z"/>
                                          <path d="M7 9h10v1H7z"/>
                                        </svg>
                                    </a>
                                    <button onclick="editDevis('{{ $devis['id'] }}')" 
                                            class="text-emerald-400 hover:text-emerald-300 text-xs px-2 py-1 rounded hover:bg-emerald-500/20"
                                            title="Modifier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button onclick="deleteDevis('{{ $devis['id'] }}', '{{ addslashes($devis['name']) }}')" 
                                            class="text-red-400 hover:text-red-300 text-xs px-2 py-1 rounded hover:bg-red-500/20"
                                            title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-white/60">Aucun devis spécifique trouvé</td>
                        </tr>
                        @endforelse
                        <tr data-empty class="hidden">
                            <td colspan="9" class="text-center py-8 text-white/60">Aucun résultat</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('admin.mail')


<div id="deleteConfirm" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div id="confirmOverlay" class="absolute inset-0 bg-black/60"></div>
    <div class="relative mx-auto max-w-sm w-[92%]">
        <div class="rounded-xl bg-[#122241] border border-white/10 shadow-2xl">
            <div class="px-4 py-3 border-b border-white/10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-600/20 text-red-300 flex items-center justify-center">!</div>
                <div class="text-white font-semibold">Confirmer la suppression</div>
            </div>
            <div id="confirmMessage" class="px-4 py-4 text-white/80">
                Êtes-vous sûr de vouloir supprimer cet élément ?
            </div>
            <div class="px-4 py-3 flex items-center justify-end gap-2">
                 <button onclick="closeDeleteConfirm()" class="rounded-lg bg-white/10 hover:bg-white/20 text-white px-4 py-2">Annuler</button>
      <button onclick="confirmDelete()" class="rounded-lg bg-red-600 hover:bg-red-700 text-white px-4 py-2">Supprimer</button>
            </div>
        </div>
    </div>
</div>


<div id="detailsModal" class="fixed inset-0 z-50 hidden">
  <div class="absolute inset-0 bg-black/60"></div>
  <div class="relative h-full flex items-center justify-center p-4">
    <div class="w-full max-w-3xl md:max-w-4xl rounded-2xl bg-[#122241] border border-white/10 p-6 shadow-2xl max-h-[85vh] overflow-y-auto">
      <h3 class="text-lg font-semibold text-white mb-4">Détails du devis</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-white/90 text-sm" id="detailsBody"></div>
      <div class="flex justify-end mt-4">
        <button onclick="closeDetails()" class="rounded-lg bg-white/10 hover:bg-white/20 text-white px-4 py-2">Fermer</button>
      </div>
    </div>
  </div>
</div>

<script>
function showTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-secondary','text-white');
        button.classList.add('border-transparent','text-white/70');
    });
    document.getElementById('content-' + tabName).classList.remove('hidden');
    document.getElementById('tab-' + tabName).classList.add('border-secondary','text-white');
    document.getElementById('tab-' + tabName).classList.remove('border-transparent','text-white/70');
}

function filterTable(tab){
    const searchInput = document.getElementById(`search-${tab}`);
    const filterSelect = document.getElementById(`filter-${tab}`);
    const tableBody = document.querySelector(`#content-${tab} tbody`);
    const rows = tableBody.querySelectorAll('tr:not([data-empty])'); // Exclude the empty state row
    const noResultsRow = tableBody.querySelector('tr[data-empty]');

    const searchTerm = searchInput.value.toLowerCase();
    const statusFilter = filterSelect.value;

    let hasResults = false;

    rows.forEach(row => {
        const name = row.children[0].textContent.toLowerCase();
        const id = row.children[1].textContent.toLowerCase();
        const email = row.children[2].textContent.toLowerCase();
        const company = row.children[4].textContent.toLowerCase();
        const productOrBudget = row.children[5].textContent.toLowerCase(); // Product for standard, Budget for specific
        const status = row.querySelector('select')?.value || '';

        const matchesSearch = name.includes(searchTerm) ||
                              id.includes(searchTerm) ||
                              email.includes(searchTerm) ||
                              company.includes(searchTerm) ||
                              productOrBudget.includes(searchTerm);
        const matchesStatus = statusFilter === '' || status === statusFilter;

        if (matchesSearch && matchesStatus) {
            row.classList.remove('hidden');
            hasResults = true;
        } else {
            row.classList.add('hidden');
        }
    });

    if (noResultsRow) {
        if (hasResults) {
            noResultsRow.classList.add('hidden');
        } else {
            noResultsRow.classList.remove('hidden');
        }
    }
}

function exportDevis(){
    const type = document
        .querySelector('.tab-content:not(.hidden)')
        .id.includes('specific') ? 'specific' : 'standard';

    window.location.href =
        `{{ route('admin.devis.export') }}?type=${type}`;
}


function openCreateModal() { window.location.href='{{ route("admin.devis.create") }}'; }
function viewDevis(id){
  const tr = document.querySelector(`tr[data-id="${id}"]`);
  if(!tr) return;
  const isSpecific = !!tr.closest('#content-specific');
  const tds = tr.querySelectorAll('td');
  let data;
  
  if(isSpecific){
    data = {
      'Nom du client': tds[0]?.innerText.trim()||'',
      'ID Devis': id,
      'Email': tds[2]?.innerText.trim()||'',
      'Téléphone': tr.dataset.phone || tds[3]?.innerText.trim()||'',
      'Entreprise': tds[4]?.innerText.trim()||'',
      "Type d'échangeur": tr.dataset.type_exchangeur || tds[5]?.innerText.trim()||'',
      'Ø Cuivre (mm)': tr.dataset.cuivre_diametre || '',
      'Pas ailette (mm)': tr.dataset.pas_ailette || '',
      'Hauteur (mm)': tr.dataset.hauteur_mm || '',
      'Largeur (mm)': tr.dataset.largeur_mm || '',
      'Longueur (mm)': tr.dataset.longueur_mm || '',
      'Longueur totale (mm)': tr.dataset.longueur_totale_mm || '',
      'Nombre de tubes': tr.dataset.nombre_tubes || '',
      'Géométrie X (mm)': tr.dataset.geometrie_x_mm || '',
      'Géométrie Y (mm)': tr.dataset.geometrie_y_mm || '',
      'Collecteur 1 (nb)': tr.dataset.collecteur1_nb || '',
      'Ø Collecteur 1 (mm)': tr.dataset.collecteur1_diametre || '',
      'Collecteur 2 (nb)': tr.dataset.collecteur2_nb || '',
      'Ø Collecteur 2 (mm)': tr.dataset.collecteur2_diametre || '',
      'Date de création': tds[6]?.innerText.trim()||'',
      'Statut actuel': tr.querySelector('select')?.selectedOptions[0]?.text||'',
      'Type': 'Devis Spécifique'
    };
    if(tr.dataset.requirements){ 
      data['Exigences détaillées'] = tr.dataset.requirements; 
    }
  } else {
    data = {
      'Nom du client': tds[0]?.innerText.trim()||'',
      'ID Devis': id,
      'Email': tds[2]?.innerText.trim()||'',
      'Téléphone': tr.dataset.phone || tds[3]?.innerText.trim()||'',
      'Entreprise': tds[4]?.innerText.trim()||'',
      'Produit demandé': tds[5]?.innerText.trim()||'',
      'Quantité': tds[6]?.innerText.trim()||'',
      'Date de création': tds[7]?.innerText.trim()||'',
      'Statut actuel': tr.querySelector('select')?.selectedOptions[0]?.text||'',
      'Type': 'Devis Standard'
    };
    if(tr.dataset.message){ 
      data['Message du client'] = tr.dataset.message; 
    }
  }
  
  // Add additional information
  
  
  const body = document.getElementById('detailsBody');
  const itemsHtml = Object.entries(data).map(([k,v])=>`
    <div class="mb-3">
      <div class="text-white/60 text-xs uppercase tracking-wide">${k}</div>
      <div class="text-white mt-1 ${k.includes('Message') || k.includes('Exigences') ? 'bg-white/5 p-3 rounded-lg whitespace-pre-wrap' : 'font-medium'}">${v || '-'}</div>
    </div>
  `).join('');
  let attachmentsHtml='';
  try {
    const atts = JSON.parse(tr.dataset.attachments || '[]');
    if (Array.isArray(atts) && atts.length) {
      attachmentsHtml = `
      <div class="md:col-span-2 mb-2">
        <div class="text-white/60 text-xs uppercase tracking-wide">Pièces jointes</div>
        <div class="mt-2 flex flex-wrap gap-2">
          ${atts.map(src => `
            <a href="${src}" download class="group">
              <img src="${src}" class="w-16 h-16 object-cover rounded border border-white/10 group-hover:ring-2 group-hover:ring-indigo-400">
              <div class="text-xs text-white/60 mt-1 text-center">Télécharger</div>
            </a>
          `).join('')}
        </div>
      </div>`;
    }
  } catch(e){}
  body.innerHTML = itemsHtml + attachmentsHtml;
  
  document.getElementById('detailsModal').classList.remove('hidden');
}
function closeDetails(){ document.getElementById('detailsModal').classList.add('hidden'); }
function editDevis(id) { window.location.href='{{ route("admin.devis.edit", ["id" => "__id__"]) }}'.replace('__id__', id); }
let deleteTargetId=null, deleteTargetName='';
function deleteDevis(id,name){
    deleteTargetId=id; deleteTargetName=name;
    document.getElementById('deleteConfirm').classList.remove('hidden');
}
function closeDeleteConfirm(){ document.getElementById('deleteConfirm').classList.add('hidden'); deleteTargetId=null; }
function confirmDelete(){
    if(!deleteTargetId) return;
    fetch('{{ route("admin.devis.destroy", ["id" => "__id__"]) }}'.replace('__id__', deleteTargetId),{
        method:'DELETE',
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{
        if(d.success){
            document.querySelector(`tr[data-id="${deleteTargetId}"]`)?.remove();
            showAlert('success', d.message || 'Devis supprimé.');
            if (typeof refreshStatsAfterAction === 'function') { refreshStatsAfterAction(); }
        } else {
            showAlert('error', d.message || 'Erreur lors de la suppression.');
        }
        closeDeleteConfirm();
    }).catch(()=>{ showAlert('error', 'Erreur'); closeDeleteConfirm(); });
}

function applyStatusStyle(el){
    if(!el) return;
    const v = el.value;
    const remove = ['bg-white/10','border-white/20','text-white/80','bg-blue-500/20','text-blue-300','border-blue-400/30','bg-orange-500/20','text-orange-300','border-orange-400/30','bg-emerald-500/20','text-emerald-300','border-emerald-400/30','bg-red-500/20','text-red-300','border-red-400/30'];
    remove.forEach(c=>el.classList.remove(c));
    let add = ['bg-white/10','border-white/20','text-white/80'];
    if(v==='nouveau') add = ['bg-blue-500/20','text-blue-300','border-blue-400/30'];
    else if(v==='en_cours') add = ['bg-orange-500/20','text-orange-300','border-orange-400/30'];
    else if(v==='envoye') add = ['bg-indigo-500/20','text-indigo-300','border-indigo-400/30'];
    else if(v==='confirme') add = ['bg-emerald-500/20','text-emerald-300','border-emerald-400/30'];
    else if(v==='annule') add = ['bg-red-500/20','text-red-300','border-red-400/30'];
    add.forEach(c=>el.classList.add(c));
}

function updateStatus(id,status,el){
    const original = el?.dataset.originalValue;
    const tableLoading = document.getElementById('tableLoading');
    if (tableLoading) tableLoading.classList.remove('hidden');

    applyStatusStyle(el);
    fetch('{{ route("admin.devis.update-status", ["id" => "__id__"]) }}'.replace('__id__',id),{
        method:'PATCH',
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json','Content-Type':'application/json'},
        body:JSON.stringify({status})
    }).then(r=>r.json()).then(d=>{
        if(!d.success){
            if(el){ el.value = original; applyStatusStyle(el); }
            showAlert('error', d.message || 'Erreur');
        } else {
            if(el){ el.dataset.originalValue = status; }
            showAlert('success', 'Statut mis à jour.');
            if (typeof refreshStatsAfterAction === 'function') { refreshStatsAfterAction(); }
        }
    }).catch(e=>{
        if(el){ el.value = original; applyStatusStyle(el); }
        showAlert('error', 'Erreur');
    }).finally(() => {
        if (tableLoading) {
            setTimeout(() => tableLoading.classList.add('hidden'), 300);
        }
    });
}

document.addEventListener('DOMContentLoaded',()=>{
    // Init tabs based on URL
    const qs = new URLSearchParams(window.location.search);
    if (location.hash === '#specific' || qs.get('tab') === 'specific') { showTab('specific'); }
    else { showTab('standard'); }
    const tStd = document.getElementById('tab-standard');
    const tSpc = document.getElementById('tab-specific');
    tStd && tStd.addEventListener('click', e=>{ e.preventDefault(); showTab('standard'); });
    tSpc && tSpc.addEventListener('click', e=>{ e.preventDefault(); showTab('specific'); });

    document.querySelectorAll('select[data-original-value]').forEach(applyStatusStyle);
    const params = new URLSearchParams(window.location.search);
    const email = params.get('email') || params.get('q') || '';
    if (email) {
        const s1 = document.getElementById('search-standard');
        const s2 = document.getElementById('search-specific');
        if (s1) { s1.value = email; filterTable('standard'); }
        if (s2) { s2.value = email; filterTable('specific'); }
    }
});

function openEmailModal(name,email,type,id){
    document.getElementById('recipientName').value=name;
    document.getElementById('recipientEmail').value=email;
    document.getElementById('emailSubject').value=type==='standard'?'Devis pour votre demande - ALCOIL':'Devis personnalisé - ALCOIL';
    document.getElementById('emailText').value=`Bonjour ${name},\n\nSuite à votre demande de devis, nous vous transmettons notre proposition.\n\nCordialement,\nL'équipe ALCOIL`;
    document.getElementById('devisId').value=id;
    document.getElementById('emailModal').classList.remove('hidden');
}
function closeEmailModal(){ document.getElementById('emailModal').classList.add('hidden');document.getElementById('emailForm').reset();}
</script>
<script>
// Real-time stats update system
let statsData = {
    totalDevis: {{ $totalDevis ?? 0 }},
    stats: @json($stats ?? [])
};

// Update stats cards with animation
function updateStatsCards(newStats) {
    // Total Devis
    const totalEl = document.querySelector('.grid [data-stat="total"]');
    if (totalEl) animateNumber(totalEl, newStats.totalDevis || 0);
    
    // Standard
    const standardEl = document.querySelector('.grid [data-stat="standard"]');
    if (standardEl) animateNumber(standardEl, newStats.standard || 0);
    
    // Specific
    const specificEl = document.querySelector('.grid [data-stat="specific"]');
    if (specificEl) animateNumber(specificEl, newStats.specific || 0);
    
    // Status cards
    // In your real-time stats JS, update the statusCards object:
const statusCards = {
   
    pending: document.querySelector('.grid [data-stat="pending"]'),
    confirme: document.querySelector('.grid [data-stat="confirme"]'),
    annule: document.querySelector('.grid [data-stat="annule"]')  // ✅ ADD THIS

};

// Update all status cards with proper colors and progress bars
Object.entries(statusCards).forEach(([statusKey, el]) => {
    if (el) {
        let count = 0;
        let progressBarClass = '';
        
        if (statusKey === 'pending') {
            // Calculate pending as sum of nouveau, en_cours, and envoye
            count = (newStats.status?.nouveau || 0) + 
                   (newStats.status?.en_cours || 0) + 
                   (newStats.status?.envoye || 0);
            progressBarClass = '.bg-orange-500';
        } else if (statusKey === 'total') {
            count = newStats.totalDevis || 0;
            progressBarClass = '.bg-secondary_2';
        } else {
            // Individual status counts
            count = newStats.status?.[statusKey] || 0;
            // Map status to progress bar colors
            const statusColors = {
                'nouveau': '.bg-blue-400',
                'en_cours': '.bg-orange-400',
                'envoye': '.bg-indigo-400',
                'confirme': '.bg-emerald-400',
                'annule': '.bg-red-400',
                'standard': '.bg-secondary_2',
                'specific': '.bg-secondary_2'
            };
            progressBarClass = statusColors[statusKey] || '.bg-secondary_2';
        }
        
        animateNumber(el, count);
        
        // Update progress bar
        const progressBar = el.parentElement.querySelector(progressBarClass);
        if (progressBar) {
            const percentage = Math.round((count / Math.max(1, newStats.totalDevis || 1)) * 100);
            progressBar.style.width = Math.min(100, percentage) + '%';
        }
    }
});

}

// Smooth number animation
function animateNumber(element, target) {
    const start = parseInt(element.textContent) || 0;
    const duration = 500;
    const startTime = performance.now();
    
    function step(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easeOut = 1 - Math.pow(1 - progress, 3);
        const value = Math.round(start + (target - start) * easeOut);
        
        element.textContent = value;
        
        if (progress < 1) {
            requestAnimationFrame(step);
        }
    }
    requestAnimationFrame(step);
}


// Fetch fresh stats every 3 seconds
function fetchStats() {
    fetch('{{ route("admin.devis.stats") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateStatsCards(data.stats);
            statsData = data.stats;
        }
    })
    .catch(() => {}); // Silent fail - retry next interval
}

// Update stats after status changes, delete, etc.
function refreshStatsAfterAction() {
    setTimeout(() => fetchStats(), 100);
}

// Auto-refresh every 3 seconds
setInterval(fetchStats, 3000);

// Initial load
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(fetchStats, 1000); // First refresh after 1s
});
</script>

@endsection


