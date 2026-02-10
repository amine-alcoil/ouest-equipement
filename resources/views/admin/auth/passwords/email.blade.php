@extends('admin.layouts.auth')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-[#0f1e34]">
    <div class="px-8 py-6 mt-4 text-left bg-[#122241] shadow-lg rounded-xl border border-white/10">
        <h3 class="text-2xl font-bold text-center text-white">Réinitialiser le mot de passe</h3>
        <p class="text-center text-white/70 mt-2">Entrez votre adresse e-mail pour recevoir le lien de réinitialisation.</p>

        @if (session('status'))
            <div class="bg-green-500 text-white p-3 rounded mt-4">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('admin.password.email') }}" method="POST" class="mt-4">
            @csrf
            <div class="mt-4">
                <label class="block text-white/80" for="email">Adresse E-mail</label>
                <input type="email" name="email" id="email" placeholder="Votre adresse e-mail"
                    class="w-full px-4 py-2 mt-2 border rounded-md bg-[#0f1e34] border-white/10 focus:outline-none focus:ring-1 focus:ring-blue-600 @error('email') border-red-500 @enderror"
                    value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex items-baseline justify-end mt-4">
                <button type="submit" class="px-6 py-2 text-white bg-secondary rounded-lg hover:bg-secondary_2 focus:outline-none">
                    Envoyer le lien de réinitialisation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection