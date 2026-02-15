@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Success banner -->
    @if(session('success'))
        <div class="rounded-xl border border-green-400/40 bg-green-700/20 text-green-200 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- Header + actions -->
    <div class="flex flex-col md:flex-row md:items-center gap-3">
        <h1 class="text-2xl font-semibold">Produits</h1>
        <form data-search-form class="md:ml-auto flex items-center gap-2">
            <input name="q" value="{{ $q ?? '' }}" type="text" placeholder="Recherche…" class="px-3 py-2 rounded-lg bg-white/10 border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
            <select id="searchCategory" name="category" class="px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-white/20">
                <option value="">Toutes les catégories</option>
                @if(empty($categories))
                    <option value="" disabled>Aucune catégorie disponible</option>
                @else
                    @foreach($categories as $cat)
                        <option value="{{ $cat['name'] }}" {{ (isset($category) && $category == $cat['name']) ? 'selected' : '' }}>{{ $cat['name'] }}</option>
                    @endforeach
                @endif
            </select>
            <button id="newProductBtn" type="button" class="px-3 py-2 rounded-lg bg-[#1b334f] border border-white/10 hover:bg-[#234161]">
                 Ajouter un produit
             </button>
         </form>
     </div> 

    <script type="text/plain" data-disabled="duplicate">
        document.addEventListener('DOMContentLoaded', () => {
            const indexUrl = "{{ route('admin.products') }}";
            const tbody = document.getElementById('productsTableBody');
            const searchForm = document.querySelector('[data-search-form]');
            const searchInput = searchForm.querySelector('input[name="q"]');
            const searchCategory = document.getElementById('searchCategory');
            const csrfToken = (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || "{{ csrf_token() }}";

            const productRowHtml = (p) => {
                const s = ((p.stock ?? 0) == 0) ? 'inactif' : String(p.status ?? 'actif').toLowerCase();
                const statusLabel = s === 'actif' ? 'Actif' : 'Inactif';
                const statusClass = s === 'actif' ? 'bg-green-600/30 text-green-300' : 'bg-yellow-600/30 text-yellow-300';
                const priceFormatted = (p.price !== undefined && p.price !== null) ? new Intl.NumberFormat('fr-DZ', { style: 'currency', currency: 'DZD' }).format(p.price) : '—';
                const createdAt = p.created_at ? new Date(p.created_at).toLocaleDateString('fr-FR') : '—';
                const updatedAt = p.updated_at ? new Date(p.updated_at).toLocaleDateString('fr-FR') : '—';

                return `
                    <tr class="border-t border-white/10" data-id="${p.id ?? ''}" data-desc="${p.description ?? ''}" data-tags="${(p.tags ?? []).join(',')}" data-images="${(p.images ?? []).join('|')}" data-pdf="${p.pdf ?? ''}">
                        <td class="px-4 py-3">${p.id ?? '—'}</td>
                        <td class="px-4 py-3">${p.sku ?? '—'}</td>
                        <td class="px-4 py-3">${p.name ?? '—'}</td>
                        <td class="px-4 py-3">${p.category ?? '—'}</td>
                        <td class="px-4 py-3">${p.subcate ?? '—'}</td>
                        <td class="px-4 py-3">${priceFormatted}</td>
                        <td class="px-4 py-3">${p.stock ?? '—'}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded ${statusClass} text-xs">${statusLabel}</span>
                        </td>
                        <td class="px-4 py-3 text-white/60">${createdAt}</td>
                        <td class="px-4 py-3 text-white/60">${updatedAt}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="#" data-edit-id="${p.id ?? 0}" class="text-emerald-400 hover:text-emerald-300 text-xs px-2 py-1 rounded hover:bg-emerald-500/20" title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <button type="button" onclick="viewProduct('${p.id ?? 0}')" class="text-indigo-400 hover:text-indigo-300 text-xs px-2 py-1 rounded hover:bg-indigo-500/20" title="Voir détails">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <form method="POST" action="/admin/products/${p.id ?? 0}" data-delete-form class="inline">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs px-2 py-1 rounded hover:bg-red-500/20" title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                `;
            };

            const bindRowActions = () => {
                document.querySelectorAll('[data-edit-id]').forEach(btn => {
                    btn.onclick = (e) => {
                        e.preventDefault();
                        const productId = btn.dataset.editId;
                        // Logic to open edit modal, similar to existing viewProduct
                        // For now, we'll just log it
                        console.log('Edit product:', productId);
                    };
                });

                document.querySelectorAll('[data-delete-form]').forEach(form => {
                    form.onsubmit = async (e) => {
                        e.preventDefault();
                        if (!confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
                            return;
                        }
                        const action = form.action;
                        const method = form.querySelector('input[name="_method"]').value;
                        const token = form.querySelector('input[name="_token"]').value;

                        const res = await fetch(action, {
                            method: method,
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                            },
                        });
                        const data = await res.json();
                        if (res.ok && data.ok) {
                            refreshTable(); // Refresh table after deletion
                        } else {
                            alert(data.message || 'Erreur lors de la suppression.');
                        }
                    };
                });
            };

            const refreshTable = async () => {
                const query = searchInput.value;
                const category = searchCategory.value;
                const url = new URL(indexUrl);
                if (query) url.searchParams.append('q', query);
                if (category) url.searchParams.append('category', category);

                const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
                const data = await res.json();

                if (res.ok && data.ok) {
                    const products = data.products || [];
                    if (products.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="11" class="px-4 py-6 text-white/60 text-center">Aucun produit disponible.</td></tr>`;
                    } else {
                        tbody.innerHTML = products.map(productRowHtml).join('');
                    }
                    bindRowActions();
                } else {
                    console.error('Failed to fetch products:', data.message);
                    tbody.innerHTML = `<tr><td colspan="11" class="px-4 py-6 text-red-400 text-center">Erreur lors du chargement des produits.</td></tr>`;
                }
            };

            searchInput.addEventListener('input', refreshTable);
            searchCategory.addEventListener('change', refreshTable);

            // Initial load
            refreshTable();
        });
    </script>

    <!-- New Product Panel (toggle) -->
    <div id="newProductPanel" class="hidden rounded-xl bg-[#122241] border border-white/10 p-4 max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-lg font-semibold">Ajouter un Nouveau produit</div>
                <div class="text-white/60 text-sm">Complétez le formulaire pour ajouter un produit.</div>
            </div>
            <button id="closeNewProduct" class="px-3 py-1.5 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15">
                Fermer
            </button>
        </div>

        <form method="POST" action="{{ route('admin.products.store') }}" data-new-product-form class="mt-4 space-y-4" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="md:col-span-2">
                    <label class="block text-sm text-white/80 mb-1">Nom du produit</label>
                    <input name="name" type="text" required class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Catégorie</label>
                    <select id="newCategory" name="category" required class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20"></select>
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Sous-catégorie</label>
                    <select id="newSubcategory" name="subcategory" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20"></select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm text-white/80 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20"></textarea>
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Prix (DZD)</label>
                    <input name="price" type="number" min="0" step="0.01" required class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Stock</label>
                    <input name="stock" type="number" min="0" required class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Statut</label>
                    <select name="status" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                        <option value="actif" selected>Actif</option>
                        <option value="inactif">Inactif</option>
                        <option value="rupture">Rupture</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm text-white/80 mb-1">Tags</label>
                    <select id="newTags" name="tags[]" multiple class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 text-white h-28"></select>
                    <div id="newTagsPreview" class="mt-2 flex flex-wrap gap-2"></div>
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Images (multiple)</label>
                    <div class="flex items-center gap-2">
                        <label class="flex-1 cursor-pointer group">
                            <div class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 group-hover:border-white/20 transition flex items-center justify-between">
                                <span id="newImagesCount" class="text-white/60 text-sm">Sélectionner des images</span>
                                <svg class="w-5 h-5 text-white/40 group-hover:text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <input name="images[]" type="file" multiple accept="image/*" class="hidden" onchange="handleImageSelection(this, 'newImagesPreview', 'newImagesCount')">
                        </label>
                        <button type="button" onclick="this.previousElementSibling.querySelector('input').click()" class="p-2 rounded-lg bg-indigo-600/20 border border-indigo-500/30 text-indigo-300 hover:bg-indigo-600/30 transition" title="Ajouter plus">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                    <div id="newImagesPreview" class="mt-3 flex flex-wrap gap-3"></div>
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Fiche technique (PDF)</label>
                    <input name="pdf" type="file" accept="application/pdf" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10">
                    <div id="newPdfPreview" class="mt-2 text-white/70 text-xs"></div>
                </div>
                <div class="md:col-span-3 mt-2 flex items-center justify-end gap-2">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-secondary hover:bg-secondary_2 text-white">Enregistrer</button>
                    <button type="button" id="cancelNewProduct" class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15">Annuler</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Quick stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-xl bg-[#122241] border border-white/10 p-4">
            <div class="text-white/70 text-sm">Total produits</div>
            <div class="text-3xl font-semibold mt-1">{{ count($products) }}</div>
        </div>
        <div class="rounded-xl bg-[#122241] border border-white/10 p-4">
            <div class="text-white/70 text-sm">En stock</div>
            <div class="text-3xl font-semibold mt-1">{{ collect($products)->where('stock', '>', 0)->count() }}</div>
        </div>
        <div class="rounded-xl bg-[#122241] border border-white/10 p-4">
            <div class="text-white/70 text-sm">Rupture</div>
            <div class="text-3xl font-semibold mt-1">{{ collect($products)->where('stock', '=', 0)->count() }}</div>
        </div>
    </div>

    <!-- Table -->
    <div class="rounded-xl bg-[#122241] border border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-4 py-3 text-sm text-white/80">ID</th>
                        <th class="px-4 py-3 text-sm text-white/80">SKU</th>
                        <th class="px-4 py-3 text-sm text-white/80">Nom</th>
                        <th class="px-4 py-3 text-sm text-white/80">Catégorie</th>
                        <th class="px-4 py-3 text-sm text-white/80">Sous-catégorie</th>
                        <th class="px-4 py-3 text-sm text-white/80">Prix</th>
                        <th class="px-4 py-3 text-sm text-white/80">Stock</th>
                        <th class="px-4 py-3 text-sm text-white/80">Statut</th>
                        <th class="px-4 py-3 text-sm text-white/80">Créé</th>
                        <th class="px-4 py-3 text-sm text-white/80">Mis à jour</th>
                        <th class="px-4 py-3 text-sm text-white/80">Actions</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody">
                @forelse($products as $p)
                    <tr class="border-t border-white/10" data-id="{{ $p['id'] ?? '' }}" data-desc="{{ $p['description'] ?? '' }}" data-tags="{{ implode(',', $p['tags'] ?? []) }}" data-images="{{ isset($p['images']) ? implode('|', $p['images']) : '' }}" data-pdf="{{ $p['pdf'] ?? '' }}">
                        <td class="px-4 py-3">{{ $p['id'] ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $p['sku'] ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $p['name'] ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $p['category'] ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $p['subcate'] ?? '—' }}</td>
                        <td class="px-4 py-3">{{ isset($p['price']) ? number_format($p['price'], 0, ',', ' ') . ' DZD' : '—' }}</td>
                        <td class="px-4 py-3">{{ $p['stock'] ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @php $s = ($p['stock'] ?? 0) == 0 ? 'inactif' : strtolower($p['status'] ?? 'actif'); @endphp
                            @if($s === 'actif')
                                <span class="px-2 py-1 rounded bg-green-600/30 text-green-300 text-xs">Actif</span>
                            @elseif($s === 'inactif')
                                <span class="px-2 py-1 rounded bg-yellow-600/30 text-yellow-300 text-xs">Inactif</span>
                            @else
                                <span class="px-2 py-1 rounded bg-yellow-600/30 text-yellow-300 text-xs">Inactif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-white/60">{{ isset($p['created_at']) ? \Illuminate\Support\Carbon::parse($p['created_at'])->format('d/m/Y') : '—' }}</td>
                        <td class="px-4 py-3 text-white/60">{{ isset($p['updated_at']) ? \Illuminate\Support\Carbon::parse($p['updated_at'])->format('d/m/Y') : '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.products.edit', ['product' => $p['id'] ?? 0]) }}" data-edit-id="{{ $p['id'] ?? 0 }}" class="text-emerald-400 hover:text-emerald-300 text-xs px-2 py-1 rounded hover:bg-emerald-500/20" title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <button type="button" onclick="viewProduct('{{ $p['id'] ?? 0 }}')" class="text-indigo-400 hover:text-indigo-300 text-xs px-2 py-1 rounded hover:bg-indigo-500/20" title="Voir détails">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('admin.products.destroy', ['product' => $p['id'] ?? 0]) }}" data-delete-form class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs px-2 py-1 rounded hover:bg-red-500/20" title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="px-4 py-6 text-white/60 text-center">Aucun produit disponible.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="productDetailsModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="rounded-xl bg-[#122241] border border-white/10 p-6 max-w-2xl w-full mx-4 max-h-[85vh] overflow-y-auto">
    <div class="flex items-center justify-between mb-4">
      <div class="text-lg font-semibold">Détails du produit</div>
      <button id="pdClose" class="px-3 py-1.5 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15">Fermer</button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">

      <div class="flex flex-col">
        <span class="text-white/60 text-xs">ID</span>
        <span id="pdId" class="text-white"></span>
      </div>
      <div class="flex flex-col">
        <span class="text-white/60 text-xs">SKU</span>
        <span id="pdSku" class="text-white"></span>
      </div>
      <div class="flex flex-col">
        <span class="text-white/60 text-xs">Nom</span>
        <span id="pdName" class="text-white"></span>
      </div>
      <div class="flex flex-col">
        <span class="text-white/60 text-xs">Catégorie</span>
        <span id="pdCategory" class="text-white"></span>
      </div>
      <div class="flex flex-col">
        <span class="text-white/60 text-xs">Sous-catégorie</span>
        <span id="pdSubcategory" class="text-white"></span>
      </div>
      <div class="flex flex-col">
        <span class="text-white/60 text-xs">Prix</span>
        <span id="pdPrice" class="text-white"></span>
      </div>
      <div class="flex flex-col">
        <span class="text-white/60 text-xs">Stock</span>
        <span id="pdStock" class="text-white"></span>
      </div>
      <div class="flex flex-col">
        <span class="text-white/60 text-xs">Statut</span>
        <span id="pdStatus" class="text-white"></span>
      </div>
      <div class="flex flex-col">
        <span class="text-white/60 text-xs">Créé le</span>
        <span id="pdCreated" class="text-white"></span>
      </div>
      <div class="flex flex-col">
        <span class="text-white/60 text-xs">Mis à jour le</span>
        <span id="pdUpdated" class="text-white"></span>
      </div>
      <div class="col-span-2 flex flex-col">
        <span class="text-white/60 text-xs">Description</span>
        <span id="pdDesc" class="text-white"></span>
      </div>
      <div class="col-span-2 flex flex-col">
        <span class="text-white/60 text-xs">Tags</span>
        <div id="pdTags" class="flex flex-wrap gap-2 mt-1"></div>
      </div>
      <div class="col-span-2 flex flex-col">
        <span class="text-white/60 text-xs">Images</span>
        <div id="pdImages" class="flex flex-wrap gap-2 mt-1"></div>
      </div>
      <div class="col-span-2 flex flex-col">
        <span class="text-white/60 text-xs">Fiche technique</span>
        <div class="mt-1"><a id="pdPdf" href="#" target="_blank" class="text-indigo-300 underline hover:text-indigo-400"></a></div>
      </div>
    </div>
  </div>
</div>

<!-- Edit Product Panel -->
<div id="editProductPanel" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="rounded-xl bg-[#122241] border border-white/10 p-3 md:p-4 max-w-lg md:max-w-xl w-full mx-4 max-h-[80vh] overflow-y-auto overflow-x-hidden">
        <form id="editProductForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm text-white/80 mb-1">SKU</label>
                    <input type="text" name="sku" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-white/80 mb-1">Nom</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm text-white/80 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10"></textarea>
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Catégorie</label>
                    <select id="editCategory" name="category" required class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10"></select>
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Sous-catégorie</label>
                    <select id="editSubcategory" name="subcategory" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10"></select>
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Prix (DZD)</label>
                    <input type="number" name="price" min="0" step="0.01" required class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Stock</label>
                    <input type="number" name="stock" min="0" required class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Statut</label>
                    <select name="status" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10">
                        <option value="actif">Actif</option>
                        <option value="inactif">Inactif</option>
                        <option value="rupture">Rupture</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm text-white/80 mb-1">Tags</label>
                    <select id="editTags" name="tags[]" multiple class="w-full px-2 py-1.5 rounded-lg bg-[#0f1e34] border border-white/10 text-white h-20"></select>
                    <div id="editTagsPreview" class="mt-2 flex flex-wrap gap-2"></div>
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Images (multiple)</label>
                    <div class="flex items-center gap-2">
                        <label class="flex-1 cursor-pointer group">
                            <div class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 group-hover:border-white/20 transition flex items-center justify-between">
                                <span id="editImagesCount" class="text-white/60 text-sm">Ajouter des images</span>
                                <svg class="w-5 h-5 text-white/40 group-hover:text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <input name="images[]" type="file" multiple accept="image/*" class="hidden" onchange="handleImageSelection(this, 'editNewImagesPreview', 'editImagesCount')">
                        </label>
                        <button type="button" onclick="this.previousElementSibling.querySelector('input').click()" class="p-2 rounded-lg bg-indigo-600/20 border border-indigo-500/30 text-indigo-300 hover:bg-indigo-600/30 transition" title="Ajouter plus">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                    <div id="editImagesList" class="mt-3 flex flex-wrap gap-3"></div>
                    <div id="editNewImagesPreview" class="mt-3 flex flex-wrap gap-3"></div>
                </div>
                
                <div>
                    <label class="block text-sm text-white/80 mb-1">Fiche technique (PDF)</label>
                    <input name="pdf" type="file" accept="application/pdf" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10">
                    <div id="editPdfPreview" class="mt-2 text-white/70 text-xs"></div>
                </div>
                <div class="md:col-span-3 mt-2 flex items-center justify-end gap-2">
                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-secondary hover:bg-secondary_2 text-white">Enregistrer</button>
                    <button type="button" id="editProductCancelBtn" class="px-3 py-1.5 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15">Annuler</button>
                </div>
            </div>
            
        </form>
    </div>
</div>

<!-- Centered Confirm Modal -->
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const indexUrl = "{{ route('admin.products') }}";
    const tbody = document.getElementById('productsTableBody');

    const formatDate = (iso) => { if (!iso) return '—'; const d = new Date(iso); if (isNaN(d)) return '—'; const dd = String(d.getDate()).padStart(2,'0'); const mm = String(d.getMonth()+1).padStart(2,'0'); const yyyy = d.getFullYear(); return `${dd}/${mm}/${yyyy}`; };
    const rowHtml = (p) => {
        const s = (+p.stock === 0) ? 'rupture' : String(p.status ?? 'actif').toLowerCase();
        const statusLabel = s === 'rupture' ? 'Rupture' : (s === 'actif' ? 'Actif' : 'Inactif');
        const statusClass = s === 'actif' ? 'bg-green-600/30 text-green-300' : (s === 'rupture' ? 'bg-red-600/30 text-red-300' : 'bg-yellow-600/30 text-yellow-300');
        return `
            <tr class="border-t border-white/10" data-id="${p.id}" data-desc="${p.description ?? ''}" data-tags="${(p.tags ?? []).join(',')}" data-images="${(p.images ?? []).join('|')}" data-pdf="${p.pdf ?? ''}">
                <td class="px-4 py-3">${p.id}</td>
                <td class="px-4 py-3">${p.sku ?? '—'}</td>
                <td class="px-4 py-3">${p.name ?? '—'}</td>
                <td class="px-4 py-3">${p.category ?? '—'}</td>
                <td class="px-4 py-3">${p.subcate ?? '—'}</td>
                <td class="px-4 py-3">${p.price != null ? (Math.round(p.price).toLocaleString('fr-DZ')) + ' DZD' : '—'}</td>
                <td class="px-4 py-3">${p.stock ?? '—'}</td>
                <td class="px-4 py-3">
                    <span class="inline-block px-2 py-1 rounded ${statusClass} text-xs">${statusLabel}</span>
                </td>
                <td class="px-4 py-3 text-white/60">${formatDate(p.created_at)}</td>
                <td class="px-4 py-3 text-white/60">${formatDate(p.updated_at)}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.products.edit', ['product' => '__ID__']) }}" data-edit-id="${p.id}" class="text-emerald-400 hover:text-emerald-300 text-xs px-2 py-1 rounded hover:bg-emerald-500/20" title="Modifier">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <button type="button" onclick="viewProduct('${p.id}')" class="text-indigo-400 hover:text-indigo-300 text-xs px-2 py-1 rounded hover:bg-indigo-500/20" title="Voir détails">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                        <form method="POST" action="{{ route('admin.products.destroy', ['product' => '__ID__']) }}" data-delete-form class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 text-xs px-2 py-1 rounded hover:bg-red-500/20" title="Supprimer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        `.replaceAll('__ID__', p.id);
    };

    const toInt = (v) => parseInt(v, 10) || 0;

    const refreshTable = (products) => {
        if (!products || products.length === 0) {
            tbody.innerHTML = `<tr><td colspan="11" class="px-4 py-12 text-white/60 text-center align-middle">Aucun produit disponible.</td></tr>`;
            return;
        }
        const sorted = [...products].sort((a, b) => toInt(a.id) - toInt(b.id));
        tbody.innerHTML = sorted.map(rowHtml).join('');
        bindRowActions();
    };

    const insertProductRowSorted = (p) => {
        const emptyRow = tbody.querySelector('tr td[colspan="11"]');
        if (emptyRow) emptyRow.parentElement.remove();

        const newId = toInt(p.id);
        const tmp = document.createElement('tbody');
        tmp.innerHTML = rowHtml(p);
        const newTr = tmp.firstElementChild;

        let inserted = false;
        tbody.querySelectorAll('tr').forEach(tr => {
            const currId = toInt(tr.getAttribute('data-id'));
            if (!inserted && newId < currId) {
                tbody.insertBefore(newTr, tr);
                inserted = true;
            }
        });
        if (!inserted) {
            tbody.appendChild(newTr);
        }
        bindRowActions();
    };

    // Reusable centered confirm modal
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
        document.querySelectorAll('[data-delete-form]').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const confirmed = await showConfirm('Êtes-vous sûr de vouloir supprimer ce produit ?');
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
                    showAlert('success', 'Produit supprimé.');
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
                if (!res.ok || !data.ok) {
                    showAlert('error', 'Impossible de charger le produit.');
                    return;
                }
                const panel = document.getElementById('editProductPanel');
                const form = document.getElementById('editProductForm');
                form.action = "{{ route('admin.products.update', ['product' => '__ID__']) }}".replace('__ID__', id);
                form.querySelector('[name=id]').value = id;
                form.querySelector('[name=sku]').value = data.product.sku ?? '';
                form.querySelector('[name=name]').value = data.product.name ?? '';
                const ec = document.getElementById('editCategory');
                const es = document.getElementById('editSubcategory');
                if (ec && categoriesCache.length) {
                    ec.innerHTML = '<option value=""></option>' + categoriesCache.map(c => `<option value="${c.name}">${c.name}</option>`).join('');
                    ec.value = data.product.category ?? '';
                    const cat = categoriesCache.find(c => c.name === ec.value);
                    const children = cat ? (cat.children || []) : [];
                    if (es) {
                        es.innerHTML = '<option value=""></option>' + children.map(ch => `<option value="${ch.name}">${ch.name}</option>`).join('');
                        es.value = data.product.subcate ?? '';
                    }
                }
                form.querySelector('[name=description]').value = data.product.description ?? '';
                form.querySelector('[name=price]').value = data.product.price ?? 0;
                form.querySelector('[name=stock]').value = data.product.stock ?? 0;
                form.querySelector('[name=status]').value = data.product.status ?? 'actif';
                const et = document.getElementById('editTags');
                if (et) {
                    if (!et.options.length || !window._tagsList) { await loadTags(); }
                    const tags = data.product.tags || [];
                    tags.forEach(t => {
                        if (!Array.from(et.options).some(o => o.value === t)) {
                            const opt = document.createElement('option'); opt.value = t; opt.textContent = t; et.appendChild(opt);
                        }
                    });
                    Array.from(et.options).forEach(opt => { opt.selected = tags.includes(opt.value); });
                    updateEditTagsPreview();
                }
                renderEditImages(data.product.images || []);
                panel.classList.remove('hidden');
            });
        });
    };

    const searchForm = document.querySelector('[data-search-form]');
    const sc = document.getElementById('searchCategory');
    const doSearch = async () => {
        const q = (searchForm?.querySelector('[name=q]')?.value || '');
        const cat = sc ? sc.value : '';
        const url = indexUrl + '?q=' + encodeURIComponent(q) + (cat ? '&category=' + encodeURIComponent(cat) : '');
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        if (res.ok && data.ok) {
            let products = data.products || [];
            if (cat) { products = products.filter(p => (p.category || '') === cat); }
            refreshTable(products);
        } else {
            showAlert('error', 'Erreur de recherche.');
        }
    };
    if (searchForm) {
        searchForm.addEventListener('submit', async (e) => { e.preventDefault(); doSearch(); });
        const qInput = searchForm.querySelector('[name=q]');
        qInput && qInput.addEventListener('input', doSearch);
    }
    sc && sc.addEventListener('change', doSearch);

    /**
     * Client-side Image Compression for Faster Uploads (High Quality Mode)
     */
    async function compressImage(file, maxWidth = 1600, maxHeight = 1600, quality = 0.85) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (event) => {
                const img = new Image();
                img.src = event.target.result;
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;

                    if (width > height) {
                        if (width > maxWidth) {
                            height *= maxWidth / width;
                            width = maxWidth;
                        }
                    } else {
                        if (height > maxHeight) {
                            width *= maxHeight / height;
                            height = maxHeight;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.imageSmoothingEnabled = true;
                    ctx.imageSmoothingQuality = 'high';
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob((blob) => {
                        const compressedFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".webp", {
                            type: 'image/webp',
                            lastModified: Date.now()
                        });
                        resolve(compressedFile);
                    }, 'image/webp', quality);
                };
            };
        });
    }

    // Dynamic Image Management
    let selectedFiles = {
        new: [],
        edit: []
    };

    window.handleImageSelection = (input, previewId, countId) => {
        const type = previewId.includes('new') ? 'new' : 'edit';
        const files = Array.from(input.files);
        selectedFiles[type] = [...selectedFiles[type], ...files];
        renderSelectedPreviews(type, previewId, countId);
        input.value = ""; // Reset input so same file can be selected again
    };

    function renderSelectedPreviews(type, previewId, countId) {
        const box = document.getElementById(previewId);
        const countLabel = document.getElementById(countId);
        if (!box) return;

        const files = selectedFiles[type];
        countLabel.textContent = files.length > 0 ? `${files.length} image(s) sélectionnée(s)` : "Sélectionner des images";

        box.innerHTML = files.map((file, index) => `
            <div class="relative group w-20 h-20">
                <img src="${URL.createObjectURL(file)}" class="w-full h-full object-cover rounded-lg border border-white/10 shadow-lg">
                <button type="button" onclick="removeSelectedFile('${type}', ${index}, '${previewId}', '${countId}')" 
                    class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-xl hover:bg-red-700 transition">
                    ×
                </button>
            </div>
        `).join('');
    }

    window.removeSelectedFile = (type, index, previewId, countId) => {
        selectedFiles[type].splice(index, 1);
        renderSelectedPreviews(type, previewId, countId);
    };

    async function getProcessedFormData(form) {
        const formData = new FormData(form);
        const type = form.hasAttribute('data-new-product-form') ? 'new' : 'edit';
        
        // Remove original files from input
        formData.delete('images[]');
        
        // Add currently selected and compressed files
        const filesToProcess = selectedFiles[type];
        if (filesToProcess.length > 0) {
            for (const file of filesToProcess) {
                const compressed = await compressImage(file, 1600, 1600, 0.85);
                formData.append('images[]', compressed);
            }
        }
        
        return formData;
    }

    const createForm = document.querySelector('[data-new-product-form]');
    const newPanel = document.getElementById('newProductPanel');
    if (createForm) {
        createForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = createForm.querySelector('button[type=submit]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="flex items-center gap-2"><svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Veuillez patienter...</span>';
            }
            try {
                const processedData = await getProcessedFormData(createForm);
                const res = await fetch(createForm.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: processedData,
                });
                const data = await res.json();
                if (res.ok && data.ok) {
                    insertProductRowSorted(data.product);
                    createForm.reset();
                    newPanel && newPanel.classList.add('hidden');
                    showAlert('success', 'Produit ajouté.');
                } else {
                    showAlert('error', (data && data.message) || 'Erreur de validation.');
                }
            } catch (err) {
                showAlert('error', 'Une erreur est survenue lors de l\'envoi.');
            } finally {
                if (btn) { btn.disabled = false; btn.textContent = 'Enregistrer'; }
            }
        });
    }

    const editForm = document.getElementById('editProductForm');
    const editCancelBtn = document.getElementById('editProductCancelBtn');
    if (editForm) {
        editForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = editForm.querySelector('button[type=submit]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="flex items-center gap-2"><svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Veuillez patienter...</span>';
            }
            try {
                const processedData = await getProcessedFormData(editForm);
                const res = await fetch(editForm.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: processedData,
                });
                const data = await res.json();
                if (res.ok && data.ok) {
                    const tr = tbody.querySelector(`tr[data-id="${data.product.id}"]`);
                    if (tr) {
                        const tmp = document.createElement('tbody');
                        tmp.innerHTML = rowHtml(data.product);
                        tr.replaceWith(tmp.firstElementChild);
                        bindRowActions();
                    }
                    document.getElementById('editProductPanel').classList.add('hidden');
                    showAlert('success', 'Produit mis à jour.');
                } else {
                    showAlert('error', (data && data.message) || 'Erreur de mise à jour.');
                }
            } catch (err) {
                showAlert('error', 'Une erreur est survenue lors de l\'envoi.');
            } finally {
                if (btn) { btn.disabled = false; btn.textContent = 'Enregistrer'; }
            }
        });
    }
    if (editCancelBtn) {
        editCancelBtn.addEventListener('click', () => {
            document.getElementById('editProductPanel').classList.add('hidden');
        });
    }

    function renderEditImages(images){
        const box = document.getElementById('editImagesList');
        if(!box) return;
        const html = (Array.isArray(images) ? images : []).map(url => `
            <div class="relative group">
              <img src="${url}" class="w-16 h-16 object-cover rounded border border-white/10 hover:ring-2 hover:ring-indigo-400">
              <button type="button" data-del-image="${url}" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-6 h-6">×</button>
            </div>
        `).join('');
        box.innerHTML = html || '<div class="text-white/60 text-xs">Aucune image associée</div>';
    }

    document.getElementById('editImagesList')?.addEventListener('click', async (e)=>{
        const btn = e.target.closest('[data-del-image]');
        if(!btn) return;
        const url = btn.getAttribute('data-del-image');
        const form = document.getElementById('editProductForm');
        const id = form?.querySelector('[name=id]')?.value;
        if(!id) return;
        const route = "{{ route('admin.products.delete-image', ['product' => '__ID__']) }}".replace('__ID__', id);
        const fd = new FormData();
        fd.append('_token','{{ csrf_token() }}');
        fd.append('_method','DELETE');
        fd.append('url', url);
        const res = await fetch(route, { method:'POST', headers:{ 'Accept':'application/json','X-Requested-With':'XMLHttpRequest' }, body: fd });
        const data = await res.json();
        if(res.ok && data.ok){
            renderEditImages(data.images || []);
            const tr = document.querySelector(`tr[data-id="${id}"]`);
            if(tr){ tr.setAttribute('data-images', (data.images || []).join('|')); }
            showAlert('success','Image supprimée.');
        } else {
            showAlert('error', (data && data.message) || 'Erreur suppression image');
        }
    });

    // Panel toggle for new product
    const newProductBtn = document.getElementById('newProductBtn');
    const closeNewProduct = document.getElementById('closeNewProduct');
    const cancelNewProduct = document.getElementById('cancelNewProduct');
    if (newProductBtn) newProductBtn.addEventListener('click', () => newPanel.classList.remove('hidden'));
    if (closeNewProduct) closeNewProduct.addEventListener('click', () => newPanel.classList.add('hidden'));
    if (cancelNewProduct) cancelNewProduct.addEventListener('click', () => newPanel.classList.add('hidden'));

    function updateNewImagesPreview(files){
        const box = document.getElementById('newImagesPreview');
        if(!box) return; const arr = Array.from(files||[]);
        box.innerHTML = arr.length ? arr.map(f=>`<div class="relative group"><img src="${URL.createObjectURL(f)}" class="w-16 h-16 object-cover rounded border border-white/10 hover:ring-2 hover:ring-indigo-400"></div>`).join('') : '';
    }
    function updatePdfPreview(input, boxId){
        const box = document.getElementById(boxId);
        if(!box) return; const f = (input && input.files && input.files[0]);
        box.innerHTML = f ? `<span class="inline-flex items-center px-2 py-1 rounded bg-indigo-600/20 text-indigo-200 text-xs">${f.name}</span>` : '';
    }
    const newImagesInput = document.querySelector('[data-new-product-form] input[name="images[]"]');
    newImagesInput && newImagesInput.addEventListener('change', e=>updateNewImagesPreview(e.target.files));
    const newPdfInput = document.querySelector('[data-new-product-form] input[name="pdf"]');
    newPdfInput && newPdfInput.addEventListener('change', e=>updatePdfPreview(e.target, 'newPdfPreview'));
    const editImagesInput = document.querySelector('#editProductForm input[name="images[]"]');
    editImagesInput && editImagesInput.addEventListener('change', e=>{
        const box = document.getElementById('editNewImagesPreview');
        if(!box) return; const arr = Array.from(e.target.files||[]);
        box.innerHTML = arr.length ? arr.map(f=>`<div class="relative group"><img src="${URL.createObjectURL(f)}" class="w-16 h-16 object-cover rounded border border-white/10 hover:ring-2 hover:ring-indigo-400"></div>`).join('') : '';
    });
    const editPdfInput = document.querySelector('#editProductForm input[name="pdf"]');
    editPdfInput && editPdfInput.addEventListener('change', e=>updatePdfPreview(e.target, 'editPdfPreview'));

    const categoriesUrl = "{{ route('admin.categories') }}";
    const tagsUrl = "{{ route('admin.tags') }}";
    const catSel = document.getElementById('newCategory');
    const subSel = document.getElementById('newSubcategory');
    const searchCatSel = document.getElementById('searchCategory');
    const tagsSel = document.getElementById('newTags');
    const editCat = document.getElementById('editCategory');
    const editSub = document.getElementById('editSubcategory');
    const editTags = document.getElementById('editTags');
    let categoriesCache = [];
    const loadCategories = async () => {
        const res = await fetch(categoriesUrl, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        const list = (res.ok && data.categories) ? data.categories : [];
        categoriesCache = list;
        if (catSel) { catSel.innerHTML = '<option value=""></option>' + list.map(c => `<option value="${c.name}">${c.name}</option>`).join(''); }
        if (subSel) { subSel.innerHTML = ''; }
        if (searchCatSel) { searchCatSel.innerHTML = '<option value="">Toutes catégories</option>' + list.map(c => `<option value="${c.name}">${c.name}</option>`).join(''); }
        if (editCat) { editCat.innerHTML = '<option value=""></option>' + list.map(c => `<option value="${c.name}">${c.name}</option>`).join(''); }
        if (editSub) { editSub.innerHTML = ''; }
    };
    catSel && catSel.addEventListener('change', () => {
        if (!subSel) return;
        const name = catSel.value;
        const cat = categoriesCache.find(c => c.name === name);
        const children = cat ? (cat.children || []) : [];
        subSel.innerHTML = '<option value=""></option>' + children.map(ch => `<option value="${ch.name}">${ch.name}</option>`).join('');
    });
    editCat && editCat.addEventListener('change', () => {
        if (!editSub) return;
        const name = editCat.value;
        const cat = categoriesCache.find(c => c.name === name);
        const children = cat ? (cat.children || []) : [];
        editSub.innerHTML = '<option value=""></option>' + children.map(ch => `<option value="${ch.name}">${ch.name}</option>`).join('');
    });
    loadCategories();
    const updateTagsPreview = () => {
        const box = document.getElementById('newTagsPreview');
        if (!tagsSel || !box) return;
        const selected = Array.from(tagsSel.selectedOptions).map(o => o.value);
        box.innerHTML = selected.map(t => `<span class="inline-flex items-center px-2 py-1 rounded bg-indigo-600/20 text-indigo-200 text-xs">${t}</span>`).join('');
    };
    const updateEditTagsPreview = () => {
        const box = document.getElementById('editTagsPreview');
        if (!editTags || !box) return;
        const selected = Array.from(editTags.selectedOptions).map(o => o.value);
        box.innerHTML = selected.map(t => `
            <span class="inline-flex items-center gap-2 px-2 py-1 rounded bg-indigo-600/20 text-indigo-200 text-xs">
                <span>${t}</span>
                <button type="button" data-remove-tag="${t}" class="rounded bg-white/10 px-1">×</button>
            </span>
        `).join('');
        box.querySelectorAll('[data-remove-tag]').forEach(btn => {
            btn.addEventListener('click', () => {
                const val = btn.getAttribute('data-remove-tag');
                Array.from(editTags.options).forEach(o => { if (o.value === val) o.selected = false; });
                updateEditTagsPreview();
            });
        });
    };
    const loadTags = async () => {
        const res = await fetch(tagsUrl, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        const list = (res.ok && data.tags) ? data.tags : [];
        const names = list.map(t => t.name);
        window._tagsList = names;
        if (tagsSel) { tagsSel.innerHTML = names.map(n => `<option value="${n}">${n}</option>`).join(''); updateTagsPreview(); }
        if (editTags) { editTags.innerHTML = names.map(n => `<option value="${n}">${n}</option>`).join(''); updateEditTagsPreview(); }
    };
    loadTags();
    tagsSel && tagsSel.addEventListener('change', updateTagsPreview);
    editTags && editTags.addEventListener('change', updateEditTagsPreview);
    window.viewProduct = (id) => {
        const modal = document.getElementById('productDetailsModal');
        const tr = document.querySelector(`tr[data-id="${id}"]`);
        if (!tr || !modal) return;
        const cells = tr.querySelectorAll('td');
        const dash = '—';
        document.getElementById('pdId').textContent = cells[0]?.textContent.trim() || dash;
        document.getElementById('pdSku').textContent = cells[1]?.textContent.trim() || dash;
        document.getElementById('pdName').textContent = cells[2]?.textContent.trim() || dash;
        document.getElementById('pdCategory').textContent = cells[3]?.textContent.trim() || dash;
        document.getElementById('pdSubcategory').textContent = cells[4]?.textContent.trim() || dash;
        document.getElementById('pdPrice').textContent = cells[5]?.textContent.trim() || dash;
        document.getElementById('pdStock').textContent = cells[6]?.textContent.trim() || dash;
        document.getElementById('pdStatus').textContent = tr.querySelector('td:nth-child(8) span')?.textContent.trim() || dash;
        document.getElementById('pdCreated').textContent = cells[8]?.textContent.trim() || dash;
        document.getElementById('pdUpdated').textContent = cells[9]?.textContent.trim() || dash;
        document.getElementById('pdDesc').textContent = tr.getAttribute('data-desc') || dash;
        const tagsBox = document.getElementById('pdTags');
        const tags = (tr.getAttribute('data-tags') || '').split(',').filter(x => x);
        tagsBox.innerHTML = tags.length ? tags.map(t => `<span class="inline-flex items-center px-2 py-1 rounded bg-indigo-600/20 text-indigo-200 text-xs">${t}</span>`).join('') : dash;
        const imagesBox = document.getElementById('pdImages');
        const images = (tr.getAttribute('data-images') || '').split('|').filter(x => x);
        imagesBox.innerHTML = images.length ? images.map(src => `<a href="${src}" target="_blank" rel="noopener"><img src="${src}" class="w-16 h-16 object-cover rounded border border-white/10 hover:ring-2 hover:ring-indigo-400"></a>`).join('') : dash;
        const pdfHref = tr.getAttribute('data-pdf') || '';
        const pdfLink = document.getElementById('pdPdf');
        pdfLink.textContent = pdfHref ? 'Télécharger' : dash;
        pdfLink.href = pdfHref || '#';
        modal.classList.remove('hidden');
    };
    const pdClose = document.getElementById('pdClose');
    pdClose && pdClose.addEventListener('click', () => {
        document.getElementById('productDetailsModal').classList.add('hidden');
    });
    bindRowActions();
});
</script>
@endsection