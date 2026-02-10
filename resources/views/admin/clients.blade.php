@extends('admin.layouts.app')
@section('content')
<div class="flex flex-col gap-4">
    @if(session('success'))
        <div class="rounded-xl border border-green-400/40 bg-green-700/20 text-green-200 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center gap-3">
        <h1 class="text-2xl font-semibold">Clients</h1>
        <form method="GET" action="{{ route('admin.clients') }}" data-search-form class="md:ml-auto flex items-center gap-2">
            <input
                type="text"
                name="q"
                id="clientSearchInput"
                value="{{ old('q', $q ?? '') }}"
                class="px-3 py-2 rounded-lg bg-white/10 border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20"
                placeholder="Rechercher un client..."
            />
        </form>
        <button type="button" id="newClientBtn" class="px-3 py-2 rounded-lg bg-[#1b334f] border border-white/10 hover:bg-[#234161]">Ajouter un client</button>
    </div>

    <div id="newClientPanel" class="hidden rounded-xl bg-[#122241] border border-white/10 p-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-lg font-semibold">Ajouter un nouveau client</div>
                <div class="text-white/60 text-sm">Complétez le formulaire.</div>
            </div>
            <button id="closeNewClient" class="px-3 py-1.5 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15">Fermer</button>
        </div>

        <form method="POST" action="{{ route('admin.clients.store') }}" data-new-client-form enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-white/80 mb-1">Nom</label>
                    <input name="name" type="text" required class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Email</label>
                    <input name="email" type="email" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Téléphone</label>
                    <input name="phone" type="text" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Société</label>
                    <input name="company" type="text" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Adresse</label>
                    <input name="address" type="text" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Ville</label>
                    <input name="city" type="text" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Site web (optionnel)</label>
                    <input name="website" type="text" placeholder="https://..." class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Logo (Fichier, optionnel)</label>
                    <input name="logo_file" type="file" accept="image/*" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Type</label>
                    <select name="type" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                        <option value="Client" selected>Client</option>
                        <option value="Partenaire">Partenaire</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Statut</label>
                    <select name="status" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                        <option value="actif" selected>Actif</option>
                        <option value="prospect">Prospect</option>
                        <option value="inactif">Inactif</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-white/80 mb-1">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20"></textarea>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-4">
                <button type="submit" class="px-4 py-2 rounded-lg bg-secondary text-white">Enregistrer</button>
                <button type="button" id="cancelNewClient" class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15">Annuler</button>
            </div>
        </form>
    </div>


    <div class="rounded-xl bg-[#122241] border border-white/10 overflow-hidden">
        <!-- Loading state -->
        <div id="tableLoading" class="absolute inset-0 bg-[#122241]/80 backdrop-blur-[2px] z-10 flex flex-col justify-center items-center gap-3 hidden">
            <div class="relative">
                <div class="w-10 h-10 rounded-full border-4 border-white/10"></div>
                <div class="absolute top-0 left-0 w-10 h-10 rounded-full border-4 border-secondary border-t-transparent animate-spin"></div>
            </div>
            <span class="text-white/60 text-xs">Chargement des clients...</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-white/70 text-sm">Référence</th>
                        <th class="px-4 py-3 text-left text-white/70 text-sm">Nom</th>
                        <th class="px-4 py-3 text-left text-white/70 text-sm">Logo</th>
                        <th class="px-4 py-3 text-left text-white/70 text-sm">Email</th>
                        <th class="px-4 py-3 text-left text-white/70 text-sm">Téléphone</th>
                        <th class="px-4 py-3 text-left text-white/70 text-sm">Entreprise</th>
                        <th class="px-4 py-3 text-left text-white/70 text-sm">Ville</th>
                        <th class="px-4 py-3 text-left text-white/70 text-sm">Type</th>
                        <th class="px-4 py-3 text-left text-white/70 text-sm">Statut</th>
                        <th class="px-4 py-3 text-left text-white/70 text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody id="clientsTableBody" class="divide-y divide-white/10">
                    @if(!empty($clients) && count($clients) > 0)
                        @foreach($clients as $client)
                            <tr data-id="{{ $client->id }}" data-address="{{ $client->address ?? '' }}" data-website="{{ $client->siteweb ?? '' }}" data-notes="{{ $client->notes ?? '' }}" data-logo="{{ $client->logo ?? '' }}" data-status="{{ $client->status ?? 'actif' }}">
                                <td class="px-4 py-3 text-white/80 flex items-center justify-center">{{ $client->ref_id ?? ('ALC-' . str_pad((string)$client->id, 4, '0', STR_PAD_LEFT)) }}</td>
                                <td class="px-4 py-3 text-white">{{ $client->name }}</td>
<td class="px-4 py-3 text-white/80">
    @if(!empty($client->logo))
        <img src="{{ $client->logo }}" alt="Logo" class="h-8 w-8 rounded object-cover cursor-pointer hover:scale-110 transition-transform bg-white" data-preview-src="{{ $client->logo }}">
    @else
        —
    @endif
</td>
                                <td class="px-4 py-3 text-white/80">{{ $client->email ?? '—' }}</td>
                                <td class="px-4 py-3 text-white/80">{{ $client->phone ?? '—' }}</td>
                                <td class="px-4 py-3 text-white/80">{{ $client->company ?? '—' }}</td>
                                <td class="px-4 py-3 text-white/80">{{ $client->city ?? '—' }}</td>
                                <td class="px-4 py-3 text-white/80">{{ $client->type ?? 'Client' }}</td>
                                <td class="px-4 py-3">
                                    @if(($client->status ?? 'actif') === 'actif')
                                        <span class="px-2 py-1 rounded bg-green-600/30 text-green-300 text-xs">Actif</span>
                                    @elseif(($client->status ?? '') === 'prospect')
                                        <span class="px-2 py-1 rounded bg-yellow-600/30 text-yellow-300 text-xs">Prospect</span>
                                    @else
                                        <span class="px-2 py-1 rounded bg-gray-600/30 text-gray-300 text-xs">Inactif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.clients.edit', ['client' => $client->id]) }}" data-edit-id="{{ $client->id }}" class="text-emerald-400 hover:text-emerald-300 text-xs px-2 py-1 rounded hover:bg-emerald-500/20" title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        @if(($client->status ?? 'actif') === 'actif')
                                        <button type="button" data-toggle-status="{{ $client->id }}" class="text-red-400 hover:text-red-300 text-xs px-2 py-1 rounded hover:bg-red-500/20" title="Désactiver">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                        @else
                                        <button type="button" data-toggle-status="{{ $client->id }}" class="text-emerald-400 hover:text-emerald-300 text-xs px-2 py-1 rounded hover:bg-emerald-500/20" title="Activer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                        @endif
                                        <a href="{{ route('admin.devis') }}?q={{ urlencode($client->name ?? '') }}" class="text-blue-400 hover:text-blue-300 text-xs px-2 py-1 rounded hover:bg-blue-500/20" title="Historique devis">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                                        </a>
                                        <button type="button" onclick="viewClient('{{ $client->id }}')" class="text-indigo-400 hover:text-indigo-300 text-xs px-2 py-1 rounded hover:bg-indigo-500/20" title="Voir détails">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                        <form method="POST" action="{{ route('admin.clients.destroy', ['client' => $client->id]) }}" data-delete-form class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 text-xs px-2 py-1 rounded hover:bg-red-500/20" title="Supprimer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr id="noClientsRow">
                            <td colspan="10" class="px-4 py-6 text-white/60 text-center align-middle">Aucun client pour l’instant.</td>
                        </tr>
                    @endif
                    {{-- This row is always present but hidden, to be shown when search yields no results --}}
                    <tr id="noSearchResultsRow" class="hidden">
                        <td colspan="10" class="px-4 py-6 text-white/60 text-center align-middle">Aucun client disponible.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="editClientPanel" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="rounded-xl bg-[#122241] border border-white/10 p-6 max-w-2xl w-full mx-4">
        <form id="editClientForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-white/80 mb-1">Nom</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Email</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Téléphone</label>
                    <input type="text" name="phone" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Société</label>
                    <input type="text" name="company" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Adresse</label>
                    <input type="text" name="address" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Ville</label>
                    <input type="text" name="city" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Site web (optionnel)</label>
                    <input type="text" name="website" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20" placeholder="https://...">
                </div>

                <div>
                    <label class="block text-sm text-white/80 mb-1">Logo (Fichier, optionnel)</label>
                    <input type="file" name="logo_file" accept="image/*" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Type</label>
                    <select name="type" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                        <option value="Client">Client</option>
                        <option value="Partenaire">Partenaire</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Statut</label>
                    <select name="status" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                        <option value="actif">Actif</option>
                        <option value="prospect">Prospect</option>
                        <option value="inactif">Inactif</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-white/80 mb-1">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20"></textarea>
                </div>
            </div>
            <div class="mt-6 flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg bg-secondary hover:bg-secondary_2 text-white">Enregistrer</button>
                <button type="button" id="editCancelBtn" class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15">Annuler</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de confirmation centré -->
<div id="confirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
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
                <button id="confirmCancel" type="button" class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15">Annuler</button>
                <button id="confirmOk" type="button" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<div id="clientDetailsModal" class="fixed inset-0 z-50 hidden">
  <div class="absolute inset-0 bg-black/50"></div>
  <div class="relative mx-auto mt-20 w-full max-w-2xl rounded-2xl bg-[#122241] border border-white/10 p-6">
    <h3 class="text-lg font-semibold text-white mb-4">Détails du client</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-white/90 text-sm" id="clientDetailsBody"></div>
    <div class="flex justify-end mt-4">
      <button onclick="closeClientDetails()" class="rounded-lg bg-white/10 hover:bg-white/20 text-white px-4 py-2">Fermer</button>
    </div>
  </div>
</div>

<div id="imagePreviewModal" class="fixed inset-0 z-50 hidden">
  <div id="imagePreviewOverlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
  <div class="relative mx-auto mt-20 w-full max-w-xl rounded-2xl bg-[#122241] border border-white/10 p-4 flex items-center justify-center">
    <img id="previewImg" src="" alt="Prévisualisation" class="max-w-full max-h-[75vh] rounded">
    <button id="imagePreviewClose" class="absolute top-2 right-2 px-2 py-1 rounded bg-white/10 border border-white/10 hover:bg-white/15">Fermer</button>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const indexUrl = "{{ route('admin.clients') }}";
    const tbody = document.getElementById('clientsTableBody');
    const tableLoading = document.getElementById('tableLoading');
    const panel = document.getElementById('editClientPanel'); // FIX: declare panel

    const toNum = (id) => {
        const m = (id || '').match(/\d+/);
        return m ? parseInt(m[0], 10) : 0;
    };

    const rowHtml = (c) => `
        <tr data-id="${c.id}" data-address="${c.address ?? ''}" data-website="${c.siteweb ?? ''}" data-notes="${c.notes ?? ''}" data-logo="${c.logo ?? ''}" data-status="${c.status ?? 'actif'}">
            <td class="px-4 py-3 text-white/80">${c.ref_id ?? ('ALC-' + String(c.id).padStart(4, '0'))}</td>
            <td class="px-4 py-3 text-white">${c.name ?? '—'}</td>
            <td class="px-4 py-3 text-white/80">${c.logo ? `<img src="${c.logo}" alt="Logo" class="h-8 w-8 rounded object-cover cursor-pointer" data-preview-src="${c.logo}">` : '—'}</td>
            <td class="px-4 py-3 text-white/80">${c.email ?? '—'}</td>
            <td class="px-4 py-3 text-white/80">${c.phone ?? '—'}</td>
            <td class="px-4 py-3 text-white/80">${c.company ?? '—'}</td>
            <td class="px-4 py-3 text-white/80">${c.city ?? '—'}</td>
            <td class="px-4 py-3 text-white/80">${c.type ?? 'Client'}</td>
            <td class="px-4 py-3">
                <span class="px-2 py-1 rounded ${
                    (c.status ?? 'actif') === 'actif'   ? 'bg-green-600/30 text-green-300' :
                    (c.status ?? '') === 'prospect'     ? 'bg-yellow-600/30 text-yellow-300' :
                                                          'bg-gray-600/30 text-gray-300'
                } text-xs">${
                    (c.status ?? 'actif') === 'actif' ? 'Actif' :
                    (c.status ?? '') === 'prospect' ? 'Prospect' : 'Inactif'
                }</span>
            </td>

            <td class="px-4 py-3">
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.clients.edit', ['client' => '__ID__']) }}" data-edit-id="${c.id}" class="text-emerald-400 hover:text-emerald-300 text-xs px-2 py-1 rounded hover:bg-emerald-500/20" title="Modifier">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <button type="button" data-toggle-status="${c.id}" class="${(String(c.status ?? 'actif').toLowerCase() === 'actif') ? 'text-red-400 hover:text-red-300 hover:bg-red-500/20' : 'text-emerald-400 hover:text-emerald-300 hover:bg-emerald-500/20'} text-xs px-2 py-1 rounded" title="${(String(c.status ?? 'actif').toLowerCase() === 'actif') ? 'Désactiver' : 'Activer'}">
                        ${ (String(c.status ?? 'actif').toLowerCase() === 'actif')
                           ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
                           : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
                         }
                    </button>
                    <a href="{{ route('admin.devis') }}?q=${encodeURIComponent(c.name ?? '')}" class="text-blue-400 hover:text-blue-300 text-xs px-2 py-1 rounded hover:bg-blue-500/20" title="Historique devis">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
                    </a>
                    <button type="button" onclick="viewClient('${c.id}')" class="text-indigo-400 hover:text-indigo-300 text-xs px-2 py-1 rounded hover:bg-indigo-500/20" title="Voir détails">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                    <form method="POST" action="{{ route('admin.clients.destroy', ['client' => '__ID__']) }}" data-delete-form class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-300 text-xs px-2 py-1 rounded hover:bg-red-500/20" title="Supprimer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    `.replaceAll('__ID__', c.id);

    const refreshTable = (clients) => {
        clients = clients || [];
        clients.sort((a, b) => toNum(a.ref_id) - toNum(b.ref_id));
        if (clients.length === 0) {
            tbody.innerHTML = `<tr><td colspan="10" class="px-4 py-12 text-white/60 text-center align-middle">Aucun client disponible.</td></tr>`;
            return;
        }
        tbody.innerHTML = clients.map(rowHtml).join('');
        bindRowActions();
    };

    const insertClientRowSorted = (client) => {
        const newNum = toNum(client.id);
        const rows = Array.from(tbody.querySelectorAll('tr[data-id]'));
        let inserted = false;
        for (const row of rows) {
            const existingId = row.getAttribute('data-id') || '';
            const existingNum = toNum(existingId);
            if (newNum < existingNum) {
                const tmp = document.createElement('tbody');
                tmp.innerHTML = rowHtml(client);
                tbody.insertBefore(tmp.firstElementChild, row);
                inserted = true;
                break;
            }
        }
        if (!inserted) {
            const tmp = document.createElement('tbody');
            tmp.innerHTML = rowHtml(client);
            tbody.appendChild(tmp.firstElementChild);
        }
        bindRowActions();
    };

    const showConfirm = (message = 'Are you sure?') => new Promise((resolve) => {
        const modal = document.getElementById('confirmModal');
        const overlay = document.getElementById('confirmOverlay');
        const msgEl = document.getElementById('confirmMessage');
        const okBtn = document.getElementById('confirmOk');
        const cancelBtn = document.getElementById('confirmCancel');

        msgEl.textContent = message;
        modal.classList.remove('hidden');

        const cleanup = () => {
            modal.classList.add('hidden');
            okBtn.removeEventListener('click', onOk);
            cancelBtn.removeEventListener('click', onCancel);
            overlay.removeEventListener('click', onCancel);
            document.removeEventListener('keydown', onKey);
        };
        const onOk = () => { cleanup(); resolve(true); };
        const onCancel = () => { cleanup(); resolve(false); };
        const onKey = (e) => { if (e.key === 'Escape') onCancel(); };

        okBtn.addEventListener('click', onOk);
        cancelBtn.addEventListener('click', onCancel);
        overlay.addEventListener('click', onCancel);
        document.addEventListener('keydown', onKey);
    });

    const bindRowActions = () => {
        document.querySelectorAll('[data-toggle-status]').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.getAttribute('data-toggle-status');
                const row = tbody.querySelector(`tr[data-id="${id}"]`);
                const currentLabel = row?.querySelector('span.text-xs')?.textContent.trim().toLowerCase() || 'actif';
                const nextStatus = currentLabel === 'actif' ? 'inactif' : 'actif';
                const url = "{{ route('admin.clients.update-status', ['client' => '__ID__']) }}".replace('__ID__', id);
                const res = await fetch(url, {
                    method: 'PATCH',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: new URLSearchParams({ status: nextStatus })
                });
                const data = await res.json();
                if (res.ok && data.ok) {
                    if (row) {
                        const pill = row.querySelector('span.text-xs');
                        if (pill) {
                            pill.textContent = nextStatus === 'actif' ? 'Actif' : 'Inactif';
                            pill.className = `px-2 py-1 rounded ${nextStatus === 'actif' ? 'bg-green-600/30 text-green-300' : 'bg-gray-600/30 text-gray-300'} text-xs`;
                        }
                        row && row.setAttribute('data-status', nextStatus);
                        btn.classList.remove('text-red-400','hover:text-red-300','hover:bg-red-500/20','text-emerald-400','hover:text-emerald-300','hover:bg-emerald-500/20');
                        if (nextStatus === 'actif') {
                            btn.classList.add('text-red-400','hover:text-red-300','hover:bg-red-500/20');
                            btn.title = 'Désactiver';
                            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
                        } else {
                            btn.classList.add('text-emerald-400','hover:text-emerald-300','hover:bg-emerald-500/20');
                            btn.title = 'Activer';
                            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                        }

                    }
                } else {
                    showAlert('error', (data && data.message) || 'Erreur statut.');
                }
            });
        });

        document.querySelectorAll('[data-delete-form]').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const confirmed = await showConfirm('Êtes-vous sûr de vouloir supprimer cet élément ?');
                if (!confirmed) { return; }
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(form),
                });
                const data = await res.json();
                if (res.ok && data.ok) {
                    const tr = form.closest('tr');
                    tr && tr.remove();
                    if (!tbody.querySelector('tr')) {
                        refreshTable([]);
                    }
                } else {
                    showAlert('error', (data && data.message) || 'Erreur lors de la suppression.');
                }
            });
        });

        document.querySelectorAll('[data-edit-id]').forEach(link => {
            link.addEventListener('click', async (e) => {
                e.preventDefault();
                const id = link.getAttribute('data-edit-id');
                const editUrl = link.getAttribute('href');
                const res = await fetch(editUrl, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                if (!res.ok || !data.ok) { showAlert('error', 'Impossible de charger le client.'); return; }
                const form = document.getElementById('editClientForm');
                form.action = "{{ route('admin.clients.update', ['client' => '__ID__']) }}".replace('__ID__', id);
                form.querySelector('[name=id]').value = id;
                form.querySelector('[name=name]').value = data.client.name ?? '';
                form.querySelector('[name=email]').value = data.client.email ?? '';
                form.querySelector('[name=phone]').value = data.client.phone ?? '';
                form.querySelector('[name=company]').value = data.client.company ?? '';
                form.querySelector('[name=address]').value = data.client.address ?? '';
                form.querySelector('[name=city]').value = data.client.city ?? '';
                form.querySelector('[name=website]').value = data.client.siteweb ?? '';

                form.querySelector('[name=type]').value = data.client.type ?? 'Client';
                form.querySelector('[name=status]').value = data.client.status ?? 'actif';
                form.querySelector('[name=notes]').value = data.client.notes ?? '';
                panel.classList.remove('hidden'); // FIX: use declared panel
            });
        });
    };

    const searchForm = document.querySelector('[data-search-form]');
    if (searchForm) {
        searchForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (tableLoading) tableLoading.classList.remove('hidden');
            try {
                const q = searchForm.querySelector('[name=q]').value || '';
                const res = await fetch(indexUrl + '?q=' + encodeURIComponent(q), {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (res.ok && data.ok) {
                    refreshTable(data.clients || []);
                    showAlert('success', 'Opération réussie!');
                } else {
                    showAlert('error', 'Erreur de recherche.');
                }
            } finally {
                if (tableLoading) setTimeout(() => tableLoading.classList.add('hidden'), 300);
            }
        });
    }

    const createForm = document.querySelector('[data-new-client-form]');
    const newPanel = document.getElementById('newClientPanel');
    if (createForm) {
        createForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const res = await fetch(createForm.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: new FormData(createForm),
            });
            const data = await res.json();
            if (res.ok && data.ok) {
                createForm.reset();
                newPanel.classList.add('hidden');
                try {
                    const idxRes = await fetch(indexUrl, { headers: { 'Accept': 'application/json' } });
                    const idxData = await idxRes.json();
                    if (idxRes.ok && idxData.ok) {
                        refreshTable(idxData.clients || []);
                        showAlert('success', 'Client ajouté avec succès!');
                    } else {
                        insertClientRowSorted(data.client);
                        showAlert('success', 'Client ajouté avec succès!');
                    }
                } catch (e) {
                    insertClientRowSorted(data.client);
                }
            } else {
                showAlert('error', (data && data.message) || 'Erreur lors de l’ajout.');
            }
        });
    }

    // EDIT FORM SUBMIT
    const editForm = document.getElementById('editClientForm');
    if (editForm) {
        editForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const res = await fetch(editForm.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: new FormData(editForm),
            });
            const data = await res.json();
            if (res.ok && data.ok) {
                panel.classList.add('hidden');
                // refresh row in place
                const id = editForm.querySelector('[name=id]').value;
                const row = tbody.querySelector(`tr[data-id="${id}"]`);
                if (row) {
                    const tmp = document.createElement('tbody');
                    tmp.innerHTML = rowHtml(data.client);
                    row.replaceWith(tmp.firstElementChild);
                    bindRowActions();
                    showAlert('success', 'Client mis à jour.');
                }
            } else {
                showAlert('error', (data && data.message) || 'Erreur lors de la modification.');
            }
        });
    }

    // CANCEL EDIT
    document.getElementById('editCancelBtn').addEventListener('click', () => {
        panel.classList.add('hidden');
    });

    // NEW CLIENT PANEL TOGGLE
    const newClientBtn = document.getElementById('newClientBtn');
    const closeNewClient = document.getElementById('closeNewClient');
    const cancelNewClient = document.getElementById('cancelNewClient');

    newClientBtn.addEventListener('click', () => newPanel.classList.remove('hidden'));
    closeNewClient.addEventListener('click', () => newPanel.classList.add('hidden'));
    cancelNewClient.addEventListener('click', () => newPanel.classList.add('hidden'));

    const imgModal = document.getElementById('imagePreviewModal');
    const imgOverlay = document.getElementById('imagePreviewOverlay');
    const imgClose = document.getElementById('imagePreviewClose');
    const previewImg = document.getElementById('previewImg');
    tbody.addEventListener('click', (e) => {
        const img = e.target.closest('img[data-preview-src]');
        if (!img) return;
        previewImg.src = img.getAttribute('data-preview-src');
        imgModal.classList.remove('hidden');
    });
    [imgOverlay, imgClose].forEach(el => el && el.addEventListener('click', () => {
        imgModal.classList.add('hidden');
        previewImg.src = '';
    }));

    bindRowActions();

    // Live search for clients table
    const clientSearchInput = document.getElementById('clientSearchInput');
    if (clientSearchInput) {
        clientSearchInput.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const clientRows = tbody.querySelectorAll('tr[data-id]'); // Query actual client rows
            let visibleClientsCount = 0;
            const noClientsRow = document.getElementById('noClientsRow'); // Original "no clients" message
            const noSearchResultsRow = document.getElementById('noSearchResultsRow'); // New "no search results" message

            clientRows.forEach(row => {
                const name = row.children[1].textContent.toLowerCase(); // Client Name
                const email = row.children[3].textContent.toLowerCase(); // Client Email
                const company = row.children[5].textContent.toLowerCase(); // Client Company
                const refId = row.children[0].textContent.toLowerCase(); // Client Reference ID

                if (name.includes(searchValue) || email.includes(searchValue) || company.includes(searchValue) || refId.includes(searchValue)) {
                    row.style.display = '';
                    visibleClientsCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Show/hide the "no clients found" message
            if (visibleClientsCount === 0) {
                if (searchValue.length > 0) {
                    // No clients match the search
                    if (noSearchResultsRow) noSearchResultsRow.style.display = '';
                    if (noClientsRow) noClientsRow.style.display = 'none'; // Hide original if search is active
                } else {
                    // Search is empty, and no clients are visible (meaning no clients initially)
                    if (noSearchResultsRow) noSearchResultsRow.style.display = 'none';
                    if (noClientsRow) noClientsRow.style.display = ''; // Show original if search is empty
                }
            } else {
                // Clients are visible
                if (noSearchResultsRow) noSearchResultsRow.style.display = 'none';
                if (noClientsRow) noClientsRow.style.display = 'none'; // Hide original if search has results
            }
        });
    }

    window.viewClient = function(id){
        const tr = document.querySelector(`tr[data-id="${id}"]`);
        if(!tr) return;
        const tds = tr.querySelectorAll('td');
        const pill = tr.querySelector('span.text-xs');
        const data = {
            Référence: tds[0]?.innerText.trim()||'',
            Nom: tds[1]?.innerText.trim()||'',
            Email: tds[3]?.innerText.trim()||'',
            Téléphone: tds[4]?.innerText.trim()||'',
            Entreprise: tds[5]?.innerText.trim()||'',
            Adresse: tr.dataset.address || '-',
            Ville: tds[6]?.innerText.trim()||'',
            'Site web': tr.dataset.website || '-',
            Type: tds[7]?.innerText.trim()||'',
            Statut: pill?.textContent.trim()||'',

            Notes: tr.dataset.notes || '-'
        };
        const body = document.getElementById('clientDetailsBody');
        const logo = tr.dataset.logo || '';
        const blocks = [];
        blocks.push(...Object.entries(data).map(([k,v])=>`<div><div class="text-white/60">${k}</div><div class="text-white">${v}</div></div>`));
        if (logo) {
            blocks.unshift(`<div class="md:col-span-2 flex flex-col items-center"><div class="text-white"><img src="${logo}" alt="Logo" class="h-16 w-16 rounded object-cover"></div></div>`);
        }
        body.innerHTML = blocks.join('');
        document.getElementById('clientDetailsModal').classList.remove('hidden');
    };
    window.closeClientDetails = function(){ document.getElementById('clientDetailsModal').classList.add('hidden'); };
});
</script>
@endsection