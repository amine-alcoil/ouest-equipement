@extends('admin.layouts.auth')

@section('content')
<form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4" id="loginForm">
    @csrf

    @if ($errors->any())
        <div class="rounded-lg bg-red-500/15 border border-red-500/30 text-red-100 px-3 py-2 text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <div>
        <label class="block text-sm text-white/70 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
               class="w-full rounded-xl bg-[#162744] border border-white/10 focus:border-secondary text-white px-4 py-2.5 outline-none transition placeholder-white/40"
               placeholder="admin@example.com">
    </div>

    <div>
        <label class="block text-sm text-white/70 mb-1">Mot de passe</label>
        <input type="password" name="password" required
               class="w-full rounded-xl bg-[#162744] border border-white/10 focus:border-secondary text-white px-4 py-2.5 outline-none transition placeholder-white/40"
               placeholder="••••••••">
    </div>

    <button type="submit" id="submitBtn"
            class="w-full rounded-xl bg-secondary/90 hover:bg-secondary_2 text-white font-semibold px-4 py-2.5 transition flex items-center justify-center gap-2">
        <span id="btnText">Se connecter</span>
        <div id="btnLoader" class="hidden">
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </button>

    <div class="text-center">
        <a href="{{ route('admin.password.request') }}" class="text-sm text-white/50 hover:text-white hover:underline transition">Mot de passe oublié ?</a>
    </div>
</form>

<script>
document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    const text = document.getElementById('btnText');
    const loader = document.getElementById('btnLoader');
    
    btn.disabled = true;
    btn.classList.add('opacity-80', 'cursor-not-allowed');
    text.textContent = 'Connexion...';
    loader.classList.remove('hidden');
});
</script>
@endsection