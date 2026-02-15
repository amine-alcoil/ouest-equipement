@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Gestion des Utilisateurs</h1>
        <div class="flex items-center gap-3">
            <button onclick="toggleCreateUser(true)" class="px-3 py-2 rounded-lg bg-[#1b334f] border border-white/10 hover:bg-[#234161]">
                + Ajouter
            </button>
        </div>
    </div>

    <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
        <!-- Loading Overlay -->
        <div id="tableLoading" class="hidden absolute inset-0 z-50 flex items-center justify-center bg-[#122241]/50 backdrop-blur-[2px] rounded-2xl transition-all duration-300">
            <div class="relative">
                <div class="w-12 h-12 rounded-full border-4 border-white/10"></div>
                <div class="absolute top-0 left-0 w-12 h-12 rounded-full border-4 border-secondary border-t-transparent animate-spin"></div>
            </div>
        </div>
        <div class="flex items-center justify-between mb-4">
            <div class="font-semibold">Liste des utilisateurs</div>
            <div class="flex items-center gap-2">
                <input id="searchUsers" type="text" placeholder="Rechercher par nom ou email..." class="rounded-lg bg-white/10 border border-white/15 px-3 py-1.5 text-sm text-white" oninput="filterUsers()">
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-white/10">
            <table class="min-w-full text-sm">
                <thead class="bg-white/5">
                    <tr>
                        <th class="text-left px-4 py-3">Nom</th>
                        <th class="text-left px-4 py-3">Email</th>
                        <th class="text-left px-4 py-3">Rôle</th>
                        <th class="text-left px-4 py-3">Statut</th>
                        <th class="text-left px-4 py-3">Connexions</th>
                        <th class="text-left px-4 py-3">Dernière activité</th>
                        <th class="text-left px-4 py-3">Date de création</th>
                        <th class="text-left px-4 py-3">Date de mise à jour</th>
                        <th class="text-right px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    @forelse($users as $u)
                    <tr class="border-t border-white/10">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                {{ $u->name }}
                                @if($u->role === 'admin')
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#24da00ff" fill-rule="evenodd" d="M9.592 3.2a5.574 5.574 0 0 1-.495.399c-.298.2-.633.338-.985.408c-.153.03-.313.043-.632.068c-.801.064-1.202.096-1.536.214a2.713 2.713 0 0 0-1.655 1.655c-.118.334-.15.735-.214 1.536a5.707 5.707 0 0 1-.068.632c-.07.352-.208.687-.408.985c-.087.13-.191.252-.399.495c-.521.612-.782.918-.935 1.238c-.353.74-.353 1.6 0 2.34c.153.32.414.626.935 1.238c.208.243.312.365.399.495c.2.298.338.633.408.985c.03.153.043.313.068.632c.064.801.096 1.202.214 1.536a2.713 2.713 0 0 0 1.655 1.655c.334.118.735.15 1.536.214c.319.025.479.038.632.068c.352.07.687.209.985.408c.13.087.252.191.495.399c.612.521.918.782 1.238.935c.74.353 1.6.353 2.34 0c.32-.153.626-.414 1.238-.935c.243-.208.365-.312.495-.399c.298-.2.633-.338.985-.408c.153-.03.313-.043.632-.068c.801-.064 1.202-.096 1.536-.214a2.713 2.713 0 0 0 1.655-1.655c.118-.334.15-.735.214-1.536c.025-.319.038-.479.068-.632c.07-.352.209-.687.408-.985c.087-.13.191-.252.399-.495c.521-.612.782-.918.935-1.238c.353-.74.353-1.6 0-2.34c-.153-.32-.414-.626-.935-1.238a5.574 5.574 0 0 1-.399-.495a2.713 2.713 0 0 1-.408-.985a5.72 5.72 0 0 1-.068-.632c-.064-.801-.096-1.202-.214-1.536a2.713 2.713 0 0 0-1.655-1.655c-.334-.118-.735-.15-1.536-.214a5.707 5.707 0 0 1-.632-.068a2.713 2.713 0 0 1-.985-.408a5.73 5.73 0 0 1-.495-.399c-.612-.521-.918-.782-1.238-.935a2.713 2.713 0 0 0-2.34 0c-.32.153-.626.414-1.238.935Zm6.781 6.663a.814.814 0 0 0-1.15-1.15l-4.85 4.85l-1.596-1.595a.814.814 0 0 0-1.15 1.15l2.17 2.17a.814.814 0 0 0 1.15 0l5.427-5.425Z" clip-rule="evenodd"/></svg>
                                       
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">{{ $u->email }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded px-2 py-0.5 text-xs border"
                                  style="border-color: rgba(255,255,255,0.15); background-color: rgba(255,255,255,0.06)">
                                {{ $u->role }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded px-2 py-0.5 text-xs border {{ ($u->status ?? 'actif') === 'actif' ? 'text-green-300 border-green-300/25 bg-green-900/20' : 'text-yellow-300 border-yellow-300/25 bg-yellow-900/20' }}">
                                {{ $u->status ?? 'actif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            {{ $u->total_logins ?? 0 }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $u->last_activity ? $u->last_activity->format('Y-m-d H:i') : '—' }}
                        </td>
                        <td class="px-4 py-3">{{ $u->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3">{{ $u->updated_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-1">
                                <button onclick="openEdit('{{ $u->id }}','{{ e($u->name) }}','{{ e($u->email) }}','{{ $u->role }}','{{ $u->status ?? 'actif' }}')" class="px-2 py-1 rounded bg-white/10 border border-white/10 hover:bg-white/15 text-xs">
                                    Modifier
                                </button>
                                <button type="button" onclick="confirmDelete({{ $u->id }}, '{{ e($u->name) }}')" class="px-2 py-1 rounded bg-red-600/30 border border-white/10 hover:bg-red-600/40 text-xs">
                                    Supprimer
                                </button>
                                <form id="deleteForm-{{ $u->id }}" method="POST" action="{{ route('admin.users.destroy', $u) }}" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-8 text-white/60">Aucun utilisateur trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="createUserModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/60" onclick="toggleCreateUser(false)"></div>
        <div class="relative mx-auto max-w-md w-[92%]">
            <div class="rounded-xl bg-[#122241] border border-white/10 shadow-2xl">
                <div class="px-4 py-3 border-b border-white/10 flex items-center justify-between">
                    <div class="text-white font-semibold">Ajouter utilisateur</div>
                    <button class="text-white/70 hover:text-white" onclick="toggleCreateUser(false)">✕</button>
                </div>
                <form method="POST" action="{{ route('admin.users.store') }}" class="px-4 py-4 space-y-3" onsubmit="showLoading()">
                    @csrf
                    <div>
                        <label class="block text-sm mb-1 text-white/80">Nom</label>
                        <input name="name" required class="w-full rounded-lg bg-white/10 border border-white/15 px-3 py-2 text-sm text-white" />
                    </div>
                    <div>
                        <label class="block text-sm mb-1 text-white/80">Email</label>
                        <input name="email" type="email" required class="w-full rounded-lg bg-white/10 border border-white/15 px-3 py-2 text-sm text-white" />
                    </div>
                    <div>
                        <label class="block text-sm mb-1 text-white/80">Mot de passe</label>
                        <div class="flex items-center gap-2">
                            <input id="create_password" name="password" type="password" required class="w-full rounded-lg bg-white/10 border border-white/15 px-3 py-2 text-sm text-white" />
                            <button type="button" class="rounded-lg bg-white/10 border border-white/15 px-3 py-2 text-xs hover:bg-white/15" onclick="togglePassword('create_password')">Afficher</button>
                            <button type="button" class="rounded-lg bg-[#1b334f] border border-white/10 px-3 py-2 text-xs hover:bg-[#234161]" onclick="generatePassword('create_password')">Générer</button>
                            <button type="button" class="rounded-lg bg-white/10 border border-white/15 px-3 py-2 text-xs hover:bg-white/15" onclick="copyPassword('create_password')">Copier</button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm mb-1 text-white/80">Rôle</label>
                            <select name="role" required class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white">
                                <option value="user" class="bg-[#122241] text-white">user</option>
                                <option value="admin" class="bg-[#122241] text-white">admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm mb-1 text-white/80">Statut</label>
                            <select name="status" class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white">
                                <option value="actif" class="bg-[#122241] text-white">actif</option>
                                <option value="inactif" class="bg-[#122241] text-white">inactif</option>
                            </select>
                        </div>
                    </div>
                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button" class="rounded-lg bg-white/10 border border-white/15 px-4 py-2 hover:bg-white/15" onclick="toggleCreateUser(false)">Annuler</button>
                        <button type="submit" class="rounded-lg bg-secondary hover:bg-secondary_2 text-white px-4 py-2">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editUserModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/60" onclick="toggleEditUser(false)"></div>
        <div class="relative mx-auto max-w-md w-[92%]">
            <div class="rounded-xl bg-[#122241] border border-white/10 shadow-2xl">
                <div class="px-4 py-3 border-b border-white/10 flex items-center justify-between">
                    <div class="text-white font-semibold">Modifier utilisateur</div>
                    <button class="text-white/70 hover:text-white" onclick="toggleEditUser(false)">✕</button>
                </div>
                <form id="editUserForm" method="POST" class="px-4 py-4 space-y-3" onsubmit="showLoading()">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm mb-1 text-white/80">Nom</label>
                        <input name="name" id="edit_name" required class="w-full rounded-lg bg-white/10 border border-white/15 px-3 py-2 text-sm text-white" />
                    </div>
                    <div>
                        <label class="block text-sm mb-1 text-white/80">Email</label>
                        <input name="email" id="edit_email" type="email" required class="w-full rounded-lg bg-white/10 border border-white/15 px-3 py-2 text-sm text-white" />
                    </div>
                    <div>
                        <label class="block text-sm mb-1 text-white/80">Mot de passe (laisser vide pour conserver)</label>
                        <div class="flex items-center gap-2">
                            <input name="password" id="edit_password" type="password" class="w-full rounded-lg bg-white/10 border border-white/15 px-3 py-2 text-sm text-white" />
                            <button type="button" class="rounded-lg bg-white/10 border border-white/15 px-3 py-2 text-xs hover:bg-white/15" onclick="togglePassword('edit_password')">Afficher</button>
                            <button type="button" class="rounded-lg bg-[#1b334f] border border-white/10 px-3 py-2 text-xs hover:bg-[#234161]" onclick="generatePassword('edit_password')">Générer</button>
                            <button type="button" class="rounded-lg bg-white/10 border border-white/15 px-3 py-2 text-xs hover:bg-white/15" onclick="copyPassword('edit_password')">Copier</button>
                        </div>
                        <div class="text-xs text-white/60 mt-1">Laisser vide pour conserver le mot de passe actuel.</div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm mb-1 text-white/80">Rôle</label>
                            <select name="role" id="edit_role" required class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white">
                                <option value="user" class="bg-[#122241] text-white">user</option>
                                <option value="admin" class="bg-[#122241] text-white">admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm mb-1 text-white/80">Statut</label>
                            <select name="status" id="edit_status" class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white">
                                <option value="actif" class="bg-[#122241] text-white">actif</option>
                                <option value="inactif" class="bg-[#122241] text-white">inactif</option>
                            </select>
                        </div>
                    </div>
                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button" class="rounded-lg bg-white/10 border border-white/15 px-4 py-2 hover:bg-white/15" onclick="toggleEditUser(false)">Annuler</button>
                        <button type="submit" class="rounded-lg bg-secondary hover:bg-secondary_2 text-white px-4 py-2">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <div id="deleteConfirmationModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/60" onclick="toggleDeleteConfirmation(false)"></div>
        <div class="relative mx-auto max-w-md w-[92%]">
            <div class="rounded-xl bg-[#122241] border border-white/10 shadow-2xl">
                <div class="px-4 py-3 border-b border-white/10 flex items-center justify-between">
                    <div class="text-white font-semibold">Confirmer la suppression</div>
                    <button class="text-white/70 hover:text-white" onclick="toggleDeleteConfirmation(false)">✕</button>
                </div>
                <div class="px-4 py-4 space-y-3">
                    <p class="text-white/80">Êtes-vous sûr de vouloir supprimer l'utilisateur <span id="deleteUserName" class="font-bold"></span> ?</p>
                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button" class="rounded-lg bg-white/10 border border-white/15 px-4 py-2 hover:bg-white/15" onclick="toggleDeleteConfirmation(false)">Annuler</button>
                        <button type="button" id="confirmDeleteButton" class="rounded-lg bg-red-600 hover:bg-red-700 text-white px-4 py-2">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="errorModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/60" onclick="toggleErrorModal(false)"></div>
        <div class="relative mx-auto max-w-md w-[92%]">
            <div class="rounded-xl bg-[#122241] border border-white/10 shadow-2xl">
                <div class="px-4 py-3 border-b border-white/10 flex items-center justify-between">
                    <div class="text-white font-semibold">Erreur</div>
                    <button class="text-white/70 hover:text-white" onclick="toggleErrorModal(false)">✕</button>
                </div>
                <div class="px-4 py-4 space-y-3">
                    <p id="errorMessage" class="text-white/80"></p>
                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button" class="rounded-lg bg-white/10 border border-white/15 px-4 py-2 hover:bg-white/15" onclick="toggleErrorModal(false)">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showLoading() {
    const loading = document.getElementById('tableLoading');
    if (loading) loading.classList.remove('hidden');
}

function toggleCreateUser(show) {
    document.getElementById('createUserModal').classList.toggle('hidden', !show);
}
function toggleEditUser(show) {
    document.getElementById('editUserModal').classList.toggle('hidden', !show);
}
function openEdit(id, name, email, role, status) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_password').value = '';
    document.getElementById('edit_role').value = role;
    
    const statusSelect = document.getElementById('edit_status');
    statusSelect.value = status;
    
    if (id == 1) {
        statusSelect.disabled = true;
        statusSelect.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        statusSelect.disabled = false;
        statusSelect.classList.remove('opacity-50', 'cursor-not-allowed');
    }

    const form = document.getElementById('editUserForm');
    form.action = "{{ route('admin.users.update', ':id') }}".replace(':id', id);
    toggleEditUser(true);
}
function filterUsers() {
    const q = document.getElementById('searchUsers').value.trim().toLowerCase();
    const rows = document.querySelectorAll('#usersTableBody tr');
    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
    });
}
function togglePassword(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.type = (el.type === 'password') ? 'text' : 'password';
}
function generatePassword(id) {
    const el = document.getElementById(id);
    if (!el) return;
    const length = 12;
    const lowers = 'abcdefghijklmnopqrstuvwxyz';
    const uppers = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const nums = '0123456789';
    const syms = '!@#$%^&*()-_=+[]{}<>?';
    const all = lowers + uppers + nums + syms;
    let pwd = '';
    pwd += lowers[Math.floor(Math.random()*lowers.length)];
    pwd += uppers[Math.floor(Math.random()*uppers.length)];
    pwd += nums[Math.floor(Math.random()*nums.length)];
    pwd += syms[Math.floor(Math.random()*syms.length)];
    for (let i = pwd.length; i < length; i++) {
        pwd += all[Math.floor(Math.random()*all.length)];
    }
    el.value = pwd;
    el.type = 'text';
}
function copyPassword(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.select();
    el.setSelectionRange(0, 99999);
    try { document.execCommand('copy'); } catch(e) {}
}

function confirmDelete(userId, userName) {
    if (userId === 1) {
        toggleErrorModal(true, 'Vous ne pouvez pas supprimer le premier utilisateur administrateur.');
        return;
    }
    toggleDeleteConfirmation(true, userId, userName);
}

function toggleDeleteConfirmation(show, userId = null, userName = null) {
    const modal = document.getElementById('deleteConfirmationModal');
    modal.classList.toggle('hidden', !show);
    if (show) {
        document.getElementById('deleteUserName').textContent = userName;
        const confirmButton = document.getElementById('confirmDeleteButton');
        confirmButton.onclick = function() {
            showLoading();
            document.getElementById(`deleteForm-${userId}`).submit();
        };
    }
}

function toggleErrorModal(show, message = '') {
    const modal = document.getElementById('errorModal');
    modal.classList.toggle('hidden', !show);
    if (show) {
        document.getElementById('errorMessage').textContent = message;
    }
}
</script>
@endsection
