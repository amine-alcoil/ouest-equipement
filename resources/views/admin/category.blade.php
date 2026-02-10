@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="rounded-xl border border-green-400/40 bg-green-700/20 text-green-200 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center gap-3">
        <h1 class="text-2xl font-semibold">Catégories</h1>
        <form data-search-form class="md:ml-auto flex items-center gap-2">
            <input name="q" value="{{ $q ?? '' }}" type="text" placeholder="Recherche…" class="px-3 py-2 rounded-lg bg-white/10 border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
            <div class="flex items-center gap-2">
                <button type="button" data-tab="" class="px-3 py-2 rounded-lg bg-white/10 border border-white/10">Tous</button>
                <button type="button" data-tab="Actif" class="px-3 py-2 rounded-lg bg-white/10 border border-white/10">Actif</button>
                <button type="button" data-tab="Inactif" class="px-3 py-2 rounded-lg bg-white/10 border border-white/10">Inactif</button>
            </div>
            <button id="newCategoryBtn" type="button" class="px-3 py-2 rounded-lg bg-[#1b334f] border border-white/10 hover:bg-[#234161]">
                Ajouter une catégorie
            </button>
        </form>
    </div>

    <div id="newCategoryPanel" class="hidden rounded-xl bg-[#122241] border border-white/10 p-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-lg font-semibold">Ajouter une Nouvelle catégorie</div>
                <div class="text-white/60 text-sm">Complétez le formulaire pour ajouter une catégorie.</div>
            </div>
            <button id="closeNewCategory" class="px-3 py-1.5 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15">
                Fermer
            </button>
        </div>

        <form method="POST" action="{{ route('admin.categories.store') }}" data-new-category-form class="mt-4 space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm text-white/80 mb-1">Nom</label>
                    <input name="name" type="text" required class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Sous-catégories</label>
                    <div class="flex items-center gap-2">
                        <input type="text" data-children-input class="flex-1 px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20" placeholder="Ajouter une sous-catégorie">
                        <button type="button" data-add-child class="px-3 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15" title="Ajouter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                    <div data-children-list data-input-name="children[]" class="mt-2 rounded-lg bg-[#0f1e34] border border-white/10 p-2 min-h-[44px]"></div>
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Statut</label>
                    <select name="status" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                        <option value="Actif" selected>Actif</option>
                        <option value="Inactif">Inactif</option>
                    </select>
                </div>
                
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg bg-secondary hover:bg-secondary_2 text-white">
                    Enregistrer
                </button>
                <button type="button" id="cancelNewCategory" class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15">
                    Annuler
                </button>
            </div>
        </form>
    </div>

    <div id="manageTagsPanel" class="hidden rounded-xl bg-[#122241] border border-white/10 p-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-lg font-semibold">Gérer les tags</div>
                <div class="text-white/60 text-sm">Ajoutez, modifiez ou supprimez des tags.</div>
            </div>
            <button id="closeManageTags" class="px-3 py-1.5 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15">Fermer</button>
        </div>
        <form method="POST" action="{{ route('admin.tags.store') }}" data-new-tag-form class="mt-4 space-y-3">
            @csrf
            <div class="flex items-center gap-2">
                <input name="name" type="text" required placeholder="Nom du tag" class="flex-1 px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                <button type="submit" class="px-3 py-2 rounded-lg bg-secondary hover:bg-secondary_2 text-white">Ajouter</button>
            </div>
        </form>
        <div id="tagsPanelChipList" class="mt-2 rounded-lg bg-[#0f1e34] border border-white/10 p-2 min-h-[44px]"></div>
    </div>

    <div id="editTagPanel" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="rounded-xl bg-[#122241] border border-white/10 p-6 max-w-sm w-full mx-4">
            <form id="editTagForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" />
                <label class="block text-sm text-white/80 mb-1">Nom du tag</label>
                <input type="text" name="name" required class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10">
                <div class="mt-4 flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-secondary hover:bg-secondary_2 text-white">Enregistrer</button>
                    <button type="button" id="editTagCancelBtn" class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-xl bg-[#122241] border border-white/10 p-4">
            <div class="text-white/70 text-sm">Total catégories</div>
            <div class="text-3xl font-semibold mt-1">{{ count($categories ?? []) }}</div>
        </div>
    </div>

    <div class="rounded-xl bg-[#122241] border border-white/10">
        <div class="px-4 py-3 border-b border-white/10 text-white/80 flex items-center justify-between">
            <span>Tags</span>
            <button id="newTagBtn" type="button" class="px-3 py-1.5 rounded-lg bg-indigo-700/70 border border-white/10 hover:bg-indigo-700">Ajouter tags</button>
        </div>
        <div class="p-3">
            <div id="tagsChipList" class="flex flex-wrap gap-2"></div>
        </div>
    </div>

    <div class="rounded-xl bg-[#122241] border border-white/10 overflow-hidden relative min-h-[200px]">
        <!-- Loading state -->
        <div id="tableLoading" class="absolute inset-0 bg-[#122241]/80 backdrop-blur-[2px] z-10 flex flex-col justify-center items-center gap-3 hidden">
            <div class="relative">
                <div class="w-10 h-10 rounded-full border-4 border-white/10"></div>
                <div class="absolute top-0 left-0 w-10 h-10 rounded-full border-4 border-secondary border-t-transparent animate-spin"></div>
            </div>
            <span class="text-white/60 text-xs">Chargement des catégories...</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-4 py-3 text-sm text-white/80">ID</th>
                        <th class="px-4 py-3 text-sm text-white/80">Nom</th>
                        <th class="px-4 py-3 text-sm text-white/80">Sous-catégories</th>
                       
                        <th class="px-4 py-3 text-sm text-white/80">Statut</th>
                        <th class="px-4 py-3 text-sm text-white/80">Actions</th>
                    </tr>
                </thead>
                <tbody id="categoriesTableBody">
                @forelse(($categories ?? []) as $c)
                    <tr class="border-t border-white/10" data-id="{{ $c['id'] ?? '' }}">
                        <td class="px-4 py-3">{{ $c['id'] ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $c['name'] ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @php $children = $c['children'] ?? []; @endphp
                            @if(is_array($children) && count($children))
                                @foreach($children as $ch)
                                    @php $name = is_array($ch) ? ($ch['name'] ?? '') : (string)$ch; @endphp
                                    <span class="inline-block px-2 py-1 rounded bg-white/10 border border-white/10 text-xs">{{ $name }}</span>
                                @endforeach
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @php $status = $c['status'] ?? 'Actif'; @endphp
                            @if($status === 'Actif')
                                <span class="px-2 py-1 rounded bg-green-600/30 text-green-300 text-xs">Actif</span>
                            @else
                                <span class="px-2 py-1 rounded bg-yellow-600/30 text-yellow-300 text-xs">Inactif</span>
                            @endif
                        </td>
                        
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.categories.edit', ['category' => $c['id'] ?? 0]) }}" data-edit-id="{{ $c['id'] ?? 0 }}" class="px-2 py-1 rounded bg-white/10 border border-white/10 hover:bg-white/15 text-xs">Modifier</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', ['category' => $c['id'] ?? 0]) }}" data-delete-form class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 rounded bg-red-600/30 border border-white/10 hover:bg-red-600/40 text-xs">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-white/60 text-center">Aucune catégorie disponible.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="editCategoryPanel" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="rounded-xl bg-[#122241] border border-white/10 p-6 max-w-xl w-full mx-4">
        <form id="editCategoryForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm text-white/80 mb-1">Nom</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm text-white/80 mb-1">Sous-catégories</label>
                    <div class="flex items-center gap-2">
                        <input type="text" data-children-input class="flex-1 px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10" placeholder="Ajouter une sous-catégorie">
                        <button type="button" data-add-child class="px-3 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15" title="Ajouter">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                    <div data-children-list data-input-name="children[]" class="mt-2 rounded-lg bg-[#0f1e34] border border-white/10 p-2 min-h-[44px]"></div>
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Statut</label>
                    <select name="status" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10">
                        <option value="Actif">Actif</option>
                        <option value="Inactif">Inactif</option>
                    </select>
                </div>

            </div>
            <div class="mt-6 flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg bg-secondary hover:bg-secondary_2 text-white">Enregistrer</button>
                <button type="button" id="editCategoryCancelBtn" class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const indexUrl = "{{ route('admin.categories') }}";
    const tagsIndexUrl = "{{ route('admin.tags') }}";
    const tbody = document.getElementById('categoriesTableBody');
    const tableLoading = document.getElementById('tableLoading');
    const tagsChipList = document.getElementById('tagsChipList');
    const tagsPanelChipList = document.getElementById('tagsPanelChipList');
    const csrfToken = (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || "{{ csrf_token() }}";

    const rowHtml = (c) => {
        const status = c.status ?? 'Actif';
        const statusClass = status === 'Actif' ? 'bg-green-600/30 text-green-300' : 'bg-yellow-600/30 text-yellow-300';
        const children = Array.isArray(c.children) ? c.children : (Array.isArray(c.subcate) ? c.subcate : []);
        const childrenHtml = children.length 
            ? children.map(ch => `<span class="inline-block px-2 py-1 rounded bg-white/10 border border-white/10 text-xs">${(ch.name ?? ch)}</span>`).join(' ') 
            : '—';

        return `
            <tr class="border-t border-white/10" data-id="${c.id}">
                <td class="px-4 py-3">${c.id}</td>
                <td class="px-4 py-3">${c.name ?? '—'}</td>
                <td class="px-4 py-3">${childrenHtml}</td>
                <td class="px-4 py-3"><span class="inline-block px-2 py-1 rounded ${statusClass} text-xs">${status}</span></td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.categories.edit', ['category' => '__ID__']) }}" data-edit-id="${c.id}" class="px-2 py-1 rounded bg-white/10 border border-white/10 hover:bg-white/15 text-xs">Modifier</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', ['category' => '__ID__']) }}" data-delete-form class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2 py-1 rounded bg-red-600/30 border border-white/10 hover:bg-red-600/40 text-xs">Supprimer</button>
                        </form>
                    </div>
                </td>
            </tr>
        `.replaceAll('__ID__', c.id);
    };

    const makeTagChipEl = (t) => {
        const el = document.createElement('span');
        el.className = 'inline-flex items-center gap-2 px-2 py-1 rounded bg-indigo-600/20 border border-indigo-400/30 text-indigo-200 text-xs';
        el.innerHTML = `<span>${t.name}</span>
            <button type="button" data-tag-edit="${t.id}" class="rounded bg-white/10 px-1" title="Modifier">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/><path fill-rule="evenodd" d="M2 15a1 1 0 011-1h9a1 1 0 110 2H3a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
            </button>
            <button type="button" data-tag-delete="${t.id}" class="rounded bg-white/10 px-1" title="Supprimer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 8a1 1 0 112 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 112 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd"/><path d="M4 6h12l-1 12H5L4 6zm3-2h6a1 1 0 011 1v1H6V5a1 1 0 011-1z"/></svg>
            </button>`;
        return el;
    };

    const toInt = (v) => parseInt(v, 10) || 0;

    const makeChip = (text, inputName = 'tags[]') => {
        const chip = document.createElement('span');
        chip.className = 'inline-flex items-center gap-1 px-2 py-1 rounded bg-white/10 border border-white/10 text-xs';
        chip.innerHTML = `<span>${text}</span><button type="button" class="ml-1 rounded bg-white/10 px-1">×</button>`;
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = inputName;
        hidden.value = text;
        chip.appendChild(hidden);
        chip.querySelector('button').addEventListener('click', () => chip.remove());
        return chip;
    };
    const bindTagsUI = (root) => {
        root.querySelectorAll('[data-tags-list]').forEach(list => {
            const container = list.previousElementSibling && list.previousElementSibling.classList.contains('flex') ? list.previousElementSibling : root;
            const input = container.querySelector('[data-tags-input]');
            const addBtn = container.querySelector('[data-add-tag]');
            const inputName = list.getAttribute('data-input-name') || 'tags[]';
            if (input && addBtn) {
                addBtn.addEventListener('click', () => {
                    const v = input.value.trim();
                    if (!v) return;
                    list.appendChild(makeChip(v, inputName));
                    input.value = '';
                    input.focus();
                });
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') { e.preventDefault(); addBtn.click(); }
                });
            }
        });
        root.querySelectorAll('[data-children-list]').forEach(list => {
            const container = list.previousElementSibling && list.previousElementSibling.classList.contains('flex') ? list.previousElementSibling : root;
            const input = container.querySelector('[data-children-input]');
            const addBtn = container.querySelector('[data-add-child]');
            const inputName = list.getAttribute('data-input-name') || 'children[]';
            if (input && addBtn) {
                addBtn.addEventListener('click', () => {
                    const v = input.value.trim();
                    if (!v) return;
                    list.appendChild(makeChip(v, inputName));
                    input.value = '';
                    input.focus();
                });
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') { e.preventDefault(); addBtn.click(); }
                });
            }
        });
    };
    const bindEditTagsUI = (panel) => { bindTagsUI(panel); };
    let lastCats = [];
    let statusFilter = '';
    const refreshTable = (cats) => {
        lastCats = cats || [];
        const filtered = statusFilter ? lastCats.filter(c => (c.status ?? '') === statusFilter) : lastCats;
        if (filtered.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="px-4 py-12 text-white/60 text-center align-middle">Aucune catégorie disponible.</td></tr>`;
            return;
        }
        const sorted = [...filtered].sort((a, b) => toInt(a.id) - toInt(b.id));
        tbody.innerHTML = sorted.map(rowHtml).join('');
        bindRowActions();
    };

    const initLoad = async () => {
        if (tableLoading) tableLoading.classList.remove('hidden');
        try {
            const res = await fetch(indexUrl, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            if (res.ok && data.ok) { refreshTable(data.categories || []); }
        } finally {
            if (tableLoading) setTimeout(() => tableLoading.classList.add('hidden'), 300);
        }
    };
    initLoad();

    const insertRowSorted = (c) => {
        lastCats.push(c);
        refreshTable(lastCats);
    };

    const showConfirm = (message = 'Êtes-vous sûr ?') => new Promise((resolve) => {
        const m = document.createElement('div');
        m.innerHTML = `
            <div class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="absolute inset-0 bg-black/60"></div>
                <div class="relative mx-auto max-w-sm w-[92%]">
                    <div class="rounded-xl bg-[#122241] border border-white/10 shadow-2xl">
                        <div class="px-4 py-3 border-b border-white/10 text-white font-semibold">Confirmer</div>
                        <div class="px-4 py-4 text-white/80">${message}</div>
                        <div class="px-4 py-3 flex items-center justify-end gap-2">
                            <button data-cancel class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15">Annuler</button>
                            <button data-ok class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">Supprimer</button>
                        </div>
                    </div>
                </div>
            </div>`;
        document.body.appendChild(m);
        const cleanup = () => { m.remove(); };
        m.querySelector('[data-cancel]').addEventListener('click', () => { cleanup(); resolve(false); });
        m.querySelector('[data-ok]').addEventListener('click', () => { cleanup(); resolve(true); });
        m.querySelector('.absolute.inset-0').addEventListener('click', () => { cleanup(); resolve(false); });
        document.addEventListener('keydown', function onKey(e){ if(e.key==='Escape'){ cleanup(); resolve(false); document.removeEventListener('keydown', onKey); }});
    });

    const bindRowActions = () => {
        document.querySelectorAll('[data-delete-form]').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const confirmed = await showConfirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');
                if (!confirmed) return;
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: new FormData(form),
                });
                const data = await res.json();
                if (res.ok && data.ok) {
                    lastCats = lastCats.filter(c => toInt(c.id) !== toInt(data.id || form.action.split('/').pop()));
                    refreshTable(lastCats);
                    showAlert('success', 'Catégorie supprimée.');
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
                const res = await fetch(editUrl, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await res.json();
                if (!res.ok || !data.ok) {
                    showAlert('error', 'Impossible de charger la catégorie.');
                    return;
                }
                const panel = document.getElementById('editCategoryPanel');
                const form = document.getElementById('editCategoryForm');
                form.action = "{{ route('admin.categories.update', ['category' => '__ID__']) }}".replace('__ID__', id);
                form.querySelector('[name=id]').value = id;
                form.querySelector('[name=name]').value = data.category.name ?? '';
                form.querySelector('[name=status]').value = data.category.status ?? 'Actif';
                const childrenList = panel.querySelector('[data-children-list]');
                if (childrenList) {
                    childrenList.innerHTML = '';
                    const inputName = childrenList.getAttribute('data-input-name') || 'children[]';
                    const children = data.category.children || [];
                    console.log('Children being loaded into edit form:', children);
                    children.forEach(ch => {
                        const t = (ch.name ?? ch);
                        childrenList.appendChild(makeChip(t, inputName));
                    });
                }

                bindEditTagsUI(panel);
                panel.classList.remove('hidden');
            });
        });
    };

    const searchForm = document.querySelector('[data-search-form]');
    if (searchForm) {
        const searchInput = searchForm.querySelector('[name=q]');
        searchInput.addEventListener('input', async () => {
            if (tableLoading) tableLoading.classList.remove('hidden');
            try {
                const q = searchInput.value || '';
                const res = await fetch(indexUrl + '?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                if (res.ok && data.ok) { refreshTable(data.categories || []); }
            } finally {
                if (tableLoading) setTimeout(() => tableLoading.classList.add('hidden'), 300);
            }
        });
        searchForm.querySelectorAll('[data-tab]').forEach(btn => {
            btn.addEventListener('click', () => { statusFilter = btn.getAttribute('data-tab') || ''; refreshTable(lastCats); });
        });
    }

    const createForm = document.querySelector('[data-new-category-form]');
    const newPanel = document.getElementById('newCategoryPanel');
    if (newPanel) bindTagsUI(newPanel);

    if (createForm) {
        createForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = createForm.querySelector('button[type=submit]');
            if (btn) btn.disabled = true;
            try {
                const fd = new FormData(createForm);

                const res = await fetch(createForm.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: fd,
                });
                const data = await res.json();
                if (res.ok && data.ok) {
                    insertRowSorted(data.category);
                    createForm.reset();
                    newPanel && newPanel.classList.add('hidden');
                    showAlert('success', 'Catégorie ajoutée.');
                } else {
                    showAlert('error', (data && data.message) || 'Erreur de validation.');
                }
            } finally {
                if (btn) btn.disabled = false;
            }
        });
    }

    const editForm = document.getElementById('editCategoryForm');
    const editCancelBtn = document.getElementById('editCategoryCancelBtn');
    if (editForm) {
        editForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(editForm);

            const res = await fetch(editForm.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: fd,
            });
            const data = await res.json();
            if (res.ok && data.ok) {
                const idx = lastCats.findIndex(c => toInt(c.id) === toInt(data.category.id));
                if (idx !== -1) {
                    lastCats[idx] = data.category;
                }
                refreshTable(lastCats);
                document.getElementById('editCategoryPanel').classList.add('hidden');
                showAlert('success', 'Catégorie mise à jour.');
            } else {
                showAlert('error', (data && data.message) || 'Erreur de mise à jour.');
            }
        });
    }
    if (editCancelBtn) {
        editCancelBtn.addEventListener('click', () => {
            document.getElementById('editCategoryPanel').classList.add('hidden');
        });
    }

    const newBtn = document.getElementById('newCategoryBtn');
    const closeNew = document.getElementById('closeNewCategory');
    const cancelNew = document.getElementById('cancelNewCategory');
    if (newBtn) newBtn.addEventListener('click', () => newPanel.classList.remove('hidden'));
    if (closeNew) closeNew.addEventListener('click', () => newPanel.classList.add('hidden'));
    if (cancelNew) cancelNew.addEventListener('click', () => newPanel.classList.add('hidden'));

    const newTagBtn = document.getElementById('newTagBtn');
    const manageTagsPanel = document.getElementById('manageTagsPanel');
    const closeManageTags = document.getElementById('closeManageTags');
    const newTagForm = document.querySelector('[data-new-tag-form]');
    if (newTagBtn) newTagBtn.addEventListener('click', () => manageTagsPanel.classList.remove('hidden'));
    if (closeManageTags) closeManageTags.addEventListener('click', () => manageTagsPanel.classList.add('hidden'));

    const refreshTagsTable = (tags) => {
        if (!tagsChipList) return;
        tagsChipList.innerHTML = '';
        (tags || []).sort((a,b) => toInt(a.id) - toInt(b.id)).forEach(t => {
            tagsChipList.appendChild(makeTagChipEl(t));
        });
        bindTagActions(tagsChipList);
    };
    const refreshTagsPanelTable = (tags) => {
        if (!tagsPanelChipList) return;
        tagsPanelChipList.innerHTML = '';
        (tags || []).sort((a,b) => toInt(a.id) - toInt(b.id)).forEach(t => {
            tagsPanelChipList.appendChild(makeTagChipEl(t));
        });
        bindTagActions(tagsPanelChipList);
    };
    const bindTagActions = (root) => {
        root.querySelectorAll('[data-tag-delete]').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.getAttribute('data-tag-delete');
                const ok = await showConfirm('Supprimer ce tag ?'); if (!ok) return;
                const url = "{{ route('admin.tags.destroy', ['tag' => '__ID__']) }}".replace('__ID__', id);
                const fd = new FormData(); fd.append('_method','DELETE'); fd.append('_token', csrfToken);
                const res = await fetch(url, { method: 'POST', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken }, body: fd });
                const data = await res.json(); if (res.ok && data.ok) { loadTags(); showAlert('success', 'Tag supprimé.'); } else { showAlert('error', (data && data.message) || 'Erreur suppression tag'); }
            });
        });
        root.querySelectorAll('[data-tag-edit]').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.getAttribute('data-tag-edit');
                const editUrl = "{{ route('admin.tags.edit', ['tag' => '__ID__']) }}".replace('__ID__', id);
                const res = await fetch(editUrl, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                if (!res.ok || !data.ok) { showAlert('error', 'Impossible de charger le tag.'); return; }
                const panel = document.getElementById('editTagPanel');
                const form = document.getElementById('editTagForm');
                form.action = "{{ route('admin.tags.update', ['tag' => '__ID__']) }}".replace('__ID__', id);
                form.querySelector('[name=id]').value = id;
                form.querySelector('[name=name]').value = data.tag.name ?? '';
                panel.classList.remove('hidden');
            });
        });
    };
    const loadTags = async () => {
        const res = await fetch(tagsIndexUrl, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        if (res.ok && data.ok) { refreshTagsTable(data.tags || []); refreshTagsPanelTable(data.tags || []); }
    };
    if (newTagForm) {
        newTagForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = newTagForm.querySelector('button[type=submit]'); if (btn) btn.disabled = true;
            try {
                const fd = new FormData(newTagForm);
                const res = await fetch(newTagForm.action, { method: 'POST', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: fd });
                const data = await res.json();
                if (res.ok && data.ok) { newTagForm.reset(); loadTags(); showAlert('success', 'Tag ajouté.'); }
                else { showAlert('error', (data && data.message) || 'Erreur ajout tag'); }
            } finally { if (btn) btn.disabled = false; }
        });
    }

    const editTagForm = document.getElementById('editTagForm');
    const editTagCancelBtn = document.getElementById('editTagCancelBtn');
    if (editTagForm) {
        editTagForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(editTagForm);
            fd.append('_token', csrfToken);
            const res = await fetch(editTagForm.action, { method: 'POST', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken }, body: fd });
            const data = await res.json();
            if (res.ok && data.ok) { document.getElementById('editTagPanel').classList.add('hidden'); loadTags(); showAlert('success', 'Tag mis à jour.'); }
            else { showAlert('error', (data && data.message) || 'Erreur mise à jour tag'); }
        });
    }
    if (editTagCancelBtn) { editTagCancelBtn.addEventListener('click', () => { document.getElementById('editTagPanel').classList.add('hidden'); }); }

    bindRowActions();
    loadTags();
});
</script>
@endsection
