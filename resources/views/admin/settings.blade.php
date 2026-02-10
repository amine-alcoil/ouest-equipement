@extends('admin.layouts.app')

@section('title', 'Paramètres')

@section('content')

<div class="container mx-auto px-4 py-6">
    
    
     
     <div class="mb-8 flex flex-col items-center text-center gap-2">
        <img src="{{ ($companyInfo && $companyInfo->logo_path) ? Storage::url($companyInfo->logo_path) : (session('admin_user')['avatar'] ?? '/images/Logo_ALCOIL_without_txt_white@3x.png') }}" alt="Avatar" class="h-24 w-24 rounded-full border border-white/10 object-cover bg-white">
        <div class="text-3xl font-bold text-white">{{ (session('admin_user')['name'] ?? 'Admin') }}</div>
        <div class="text-white/70">{{ (session('admin_user')['email'] ?? '') }}</div>
        <div class="text-white/50 text-sm">
            {{ session('admin_user')['role'] === 'admin' ? 'Administrateur' : 'Utilisateur' }}
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <!-- Company Info Card -->
        <div class="rounded-xl bg-[#122241] border border-white/10 p-6 xl:col-span-1">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-white">Informations de l'entreprise</h2>
                @if($companyInfo && (($currentUser->role ?? 'user') === 'admin'))
                <button id="editBtn" onclick="toggleEditMode()" class="px-4 py-2 rounded-lg bg-secondary hover:bg-secondary_2 text-white transition">
                    <span id="editBtnText">Modifier</span>
                </button>
                @endif
            </div>
            <div id="companyInfoAlerts" class="mb-4"></div>
            
            <form id="companyForm" method="POST" action="{{ route('admin.settings.update-company-info') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-white/70 mb-1">Nom de l'entreprise</label>
                        <input 
                            id="companyName" 
                            name="company_name" 
                            type="text" 
                            value="{{ $companyInfo->company_name ?? '' }}" 
                            class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white {{ $companyInfo ? 'disabled:opacity-50 disabled:cursor-not-allowed' : '' }}" 
                            placeholder="Nom de l'entreprise"
                            {{ $companyInfo ? 'disabled' : '' }}
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm text-white/70 mb-1">Email</label>
                        <input 
                            id="companyEmail" 
                            name="email" 
                            type="email" 
                            value="{{ $companyInfo->email ?? '' }}" 
                            class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white {{ $companyInfo ? 'disabled:opacity-50 disabled:cursor-not-allowed' : '' }}" 
                            placeholder="contact@exemple.com"
                            {{ $companyInfo ? 'disabled' : '' }}
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm text-white/70 mb-1">Téléphone</label>
                        <input 
                            id="companyPhone" 
                            name="phone" 
                            type="text" 
                            value="{{ $companyInfo->phone ?? '' }}" 
                            class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white {{ $companyInfo ? 'disabled:opacity-50 disabled:cursor-not-allowed' : '' }}" 
                            placeholder="+213 ..."
                            {{ $companyInfo ? 'disabled' : '' }}
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm text-white/70 mb-1">Logo</label>
                        @if($companyInfo && $companyInfo->logo_path)
                        <div class="mb-2">
                            <img src="{{ Storage::url($companyInfo->logo_path) }}" alt="Logo" class="h-16 w-auto rounded border border-white/10 bg-white">
                        </div>
                        @endif
                        <input 
                            id="companyLogo" 
                            name="logo" 
                            type="file" 
                            accept="image/*"
                            class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-white/10 file:text-white file:hover:bg-white/15 {{ $companyInfo ? 'hidden disabled:opacity-50 disabled:cursor-not-allowed' : '' }}"
                            {{ $companyInfo ? 'disabled' : '' }}
                        >
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm text-white/70 mb-1">Adresse</label>
                        <textarea 
                            id="companyAddress" 
                            name="address" 
                            class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white {{ $companyInfo ? 'disabled:opacity-50 disabled:cursor-not-allowed' : '' }}" 
                            rows="2" 
                            placeholder="Adresse complète"
                            {{ $companyInfo ? 'disabled' : '' }}
                        >{{ $companyInfo->address ?? '' }}</textarea>
                    </div>
                </div>
                
                <div class="mt-4 flex gap-3" id="formActions">
                    @if($companyInfo) 
                    <button type="submit" id="saveBtn" class="hidden px-4 py-2 rounded-lg bg-secondary hover:bg-secondary_2 text-white transition">
                        Enregistrer
                    </button>
                    <button type="button" id="cancelBtn" onclick="toggleEditMode()" class="hidden px-4 py-2 rounded-lg bg-white/10 border border-white/10 text-white hover:bg-white/15 transition">
                        Annuler
                    </button>
                    @else
                    <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition">
                        Créer
                    </button>
                    @endif
                </div>
            </form>
        </div>

        <div class="rounded-xl bg-[#122241] border border-white/10 p-6 xl:col-span-1">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-white">Informations de l'utilisateur</h2>
                @if(($currentUser->role ?? 'user') === 'admin')
                <button id="editUserBtn" onclick="toggleEditUser()" class="px-4 py-2 rounded-lg bg-secondary hover:bg-secondary_2 text-white transition">
                    <span id="editUserBtnText">Modifier</span>
                </button>
                @endif
            </div>
            <form id="userForm" method="POST" action="{{ route('admin.settings.update-user-info') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-white/70 mb-1">Nom</label>
                        <input id="userName" name="name" type="text" value="{{ $currentUser->name ?? '' }}" class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    </div>
                    <div>
                        <label class="block text-sm text-white/70 mb-1">Email</label>
                        <input id="userEmail" name="email" type="email" value="{{ $currentUser->email ?? '' }}" class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm text-white/70 mb-1">Rôle</label>
                        <input id="userRole" name="role" type="text" value="{{ $currentUser->role ?? 'user' }}" class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    </div>
                </div>
                <div class="mt-4 flex gap-3" id="userFormActions">
                    <button type="submit" id="saveUserBtn" class="hidden px-4 py-2 rounded-lg bg-secondary hover:bg-secondary_2 text-white transition">Enregistrer</button>
                    <button type="button" id="cancelUserBtn" onclick="toggleEditUser()" class="hidden px-4 py-2 rounded-lg bg-white/10 border border-white/10 text-white hover:bg-white/15 transition">Annuler</button>
                </div>
            </form>
        </div>
         <div class="rounded-xl bg-[#122241] border border-white/10 p-6">
            <h2 class="text-xl font-semibold text-white mb-4">Sécurité</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-white/70 mb-1">Mot de passe actuel</label>
                    <input id="pwdCurrent" type="password" class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white">
                </div>
                <div>
                    <label class="block text-sm text-white/70 mb-1">Nouveau mot de passe</label>
                    <input id="pwdNew" type="password" class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-white/70 mb-1">Confirmer le mot de passe</label>
                    <input id="pwdConfirm" type="password" class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white">
                </div>
            </div>
            <div class="mt-4">
                <button id="saveSecurity" class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 text-white hover:bg-white/15">Mettre à jour</button>
            </div>
        </div>

        <div class="rounded-xl bg-[#122241] border border-white/10 p-6">
            <h2 class="text-xl font-semibold text-white mb-4">Notifications</h2>
            <div class="space-y-3">
                <label class="flex items-center justify-between">
                    <span class="text-white/80">Notification email des nouveaux messages de contact</span>
                    <input id="notifContact" type="checkbox" class="w-5 h-5">
                </label>
                <label class="flex items-center justify-between">
                    <span class="text-white/80">Notification email des nouvelles commandes</span>
                    <input id="notifOrder" type="checkbox" class="w-5 h-5">
                </label>
                <label class="flex items-center justify-between">
                    <span class="text-white/80">Alerte stock faible</span>
                    <input id="notifStock" type="checkbox" class="w-5 h-5">
                </label>
            </div>
            <div class="mt-4">
                <button id="saveNotif" class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 text-white hover:bg-white/15">Enregistrer</button>
            </div>
        </div>
       

        @if(($currentUser->role ?? 'user') === 'admin')
        <div class="rounded-xl bg-[#122241] border border-white/10 p-6 xl:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-white">Ajouter un utilisateur</h2>
            </div>
            <form id="createUserForm" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-white/70 mb-1">Nom *</label>
                        <input name="name" type="text" required class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white" placeholder="Nom complet">
                    </div>
                    <div>
                        <label class="block text-sm text-white/70 mb-1">Email *</label>
                        <input name="email" type="email" required class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white" placeholder="exemple@domaine.com">
                    </div>
                    <div>
                        <label class="block text-sm text-white/70 mb-1">Mot de passe *</label>
                        <div class="relative flex items-center gap-2">
                            <input id="newUserPassword" name="password" type="password" required minlength="8" autocomplete="new-password" class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white" placeholder="Au moins 8 caractères">
                            <button type="button" id="toggleNewUserPassword" class="px-3 py-2 rounded-lg bg-white/10 border border-white/10 text-white hover:bg-white/15">Afficher</button>
                            <button type="button" id="generateNewUserPassword" class="px-3 py-2 rounded-lg bg-secondary hover:bg-secondary_2 text-white">Générer</button>
                        </div>
                        <p id="newUserPwdHint" class="text-white/60 text-xs mt-1">8 caractères minimum requis.</p>
                        <p id="newUserPwdError" class="hidden text-red-400 text-xs mt-1">Le mot de passe doit contenir au moins 8 caractères.</p>
                    </div>
                    <div>
                        <label class="block text-sm text-white/70 mb-1">Rôle</label>
                        <select name="role" class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white">
                            <option value="user" class="bg-[#122241] text-white">Utilisateur</option>
                            <option value="admin" class="bg-[#122241] text-white">Admin</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit" class="px-3 py-2 rounded-lg bg-secondary hover:bg-secondary_2 text-white">
                            Créer l'utilisateur
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endif

        

        
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleEditMode() {
    const inputs = ['companyName', 'companyEmail', 'companyPhone', 'companyLogo', 'companyAddress'];
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const editBtnText = document.getElementById('editBtnText');
    
    const isDisabled = document.getElementById('companyName').disabled;
    
    inputs.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.disabled = !isDisabled;
        }
    });

    const logo = document.getElementById('companyLogo');
    if (logo) {
        if (isDisabled) {
            logo.classList.remove('hidden');
        } else {
            logo.classList.add('hidden');
            try { logo.value = ''; } catch(e) {}
        }
    }
    
    if (isDisabled) {
        // Enable edit mode
        editBtn.classList.add('hidden');
        saveBtn.classList.remove('hidden');
        cancelBtn.classList.remove('hidden');
    } else {
        // Disable edit mode
        editBtn.classList.remove('hidden');
        saveBtn.classList.add('hidden');
        cancelBtn.classList.add('hidden');
        
        // Reset form to original values
        document.getElementById('companyForm').reset();
    }
}

function toggleEditUser(){
    const inputs=['userName','userEmail'];
    const editBtn=document.getElementById('editUserBtn');
    const saveBtn=document.getElementById('saveUserBtn');
    const cancelBtn=document.getElementById('cancelUserBtn');
    const isDisabled=document.getElementById('userName').disabled;
    inputs.forEach(id=>{var el=document.getElementById(id); if(el){ el.disabled=!isDisabled; }});
    if(isDisabled){ editBtn.classList.add('hidden'); saveBtn.classList.remove('hidden'); cancelBtn.classList.remove('hidden'); }
    else{ editBtn.classList.remove('hidden'); saveBtn.classList.add('hidden'); cancelBtn.classList.add('hidden'); document.getElementById('userForm').reset(); }
}

(function(){
    document.getElementById('saveNotif').addEventListener('click', async function(){ 
        var c = document.getElementById('notifContact').checked ? 1 : 0; 
        var o = document.getElementById('notifOrder').checked ? 1 : 0; 
        var s = document.getElementById('notifStock').checked ? 1 : 0; 
        try {
            const res = await fetch("{{ route('admin.settings.update-notifications') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ contact: c, order: o, stock: s })
            });
            const data = await res.json();
            if (data && data.ok) {
                localStorage.setItem('settings:notifContact', String(c));
                localStorage.setItem('settings:notifOrder', String(o));
                localStorage.setItem('settings:notifStock', String(s));
                showAlert('success', 'Paramètres enregistrés');
            } else {
                showAlert('error', 'Échec de l\'enregistrement');
            }
        } catch (e) {
            showAlert('error', 'Erreur réseau');
        }
    });

    document.getElementById('saveSecurity').addEventListener('click',function(){ 
        var n=document.getElementById('pwdNew').value||''; 
        var c=document.getElementById('pwdConfirm').value||''; 
        if(!n||n!==c){ 
            showAlert('error', 'Les mots de passe ne correspondent pas'); 
            return; 
        } 
        showAlert('success', 'Mot de passe mis à jour'); 
    });

    var serverNotif = @json($notif ?? ['contact'=>false,'order'=>false,'stock'=>false]);
    var notifContact = localStorage.getItem('settings:notifContact');
    var notifOrder = localStorage.getItem('settings:notifOrder');
    var notifStock = localStorage.getItem('settings:notifStock');

    var c = (notifContact !== null) ? (notifContact === '1') : !!serverNotif.contact;
    var o = (notifOrder !== null) ? (notifOrder === '1') : !!serverNotif.order;
    var s = (notifStock !== null) ? (notifStock === '1') : !!serverNotif.stock;

    document.getElementById('notifContact').checked = c;
    document.getElementById('notifOrder').checked = o;
    document.getElementById('notifStock').checked = s;

    var companyForm = document.getElementById('companyForm');
    if(companyForm){
        companyForm.addEventListener('submit', async function(e){
            e.preventDefault();
            var fd = new FormData(companyForm);
            try{
                var res = await fetch(companyForm.action, {
                    method: companyForm.method || 'POST',
                    body: fd,
                    credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if(res.ok){
                    showAlert('success','Données entreprise mises à jour');
                    toggleEditMode();
                }else{
                    showAlert('error','Échec de la mise à jour des données entreprise');
                }
            }catch(err){
                showAlert('error','Erreur réseau');
            }
        });
    }

    var userForm = document.getElementById('userForm');
    if(userForm){
        userForm.addEventListener('submit', async function(e){
            e.preventDefault();
            var fdUser = new FormData(userForm);
            try{
                var resUser = await fetch(userForm.action, {
                    method: userForm.method || 'POST',
                    body: fdUser,
                    credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if(resUser.ok){
                    showAlert('success','Données utilisateur mises à jour');
                    toggleEditUser();
                }else{
                    showAlert('error','Échec de la mise à jour des données utilisateur');
                }
            }catch(errUser){
                showAlert('error','Erreur réseau');
            }
        });
    }
})();

function generatePassword(len){
    var chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%^&*';
    var out='';
    for(var i=0;i<len;i++){ out += chars[Math.floor(Math.random()*chars.length)]; }
    return out;
}

(function(){
    var pwdInput=document.getElementById('newUserPassword');
    var toggleBtn=document.getElementById('toggleNewUserPassword');
    var genBtn=document.getElementById('generateNewUserPassword');
    var form=document.getElementById('createUserForm');
    if(toggleBtn && pwdInput){
        toggleBtn.addEventListener('click', function(){
            var isPwd = pwdInput.type === 'password';
            pwdInput.type = isPwd ? 'text' : 'password';
            toggleBtn.textContent = isPwd ? 'Masquer' : 'Afficher';
        });
    }
    if(genBtn && pwdInput){
        genBtn.addEventListener('click', function(){
            var p = generatePassword(12);
            pwdInput.value = p;
        });
    }
    if(form && pwdInput){
        form.addEventListener('submit', async function(e){
            e.preventDefault();
            var val = pwdInput.value || '';
            var err = document.getElementById('newUserPwdError');
            if(val.length < 8){
                if(err){ err.classList.remove('hidden'); }
                return;
            }
            if(err){ err.classList.add('hidden'); }
            var fd = new FormData(form);
            try{
                var res = await fetch(form.action, {
                    method: form.method || 'POST',
                    body: fd,
                    credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if(res.ok){
                    showAlert('success','Utilisateur créé');
                    form.reset();
                    pwdInput.value='';
                }else{
                    showAlert('error','Échec de la création de l utilisateur');
                }
            }catch(errFetch){
                showAlert('error','Erreur réseau');
            }
        });
    }
})();
</script>
@endsection