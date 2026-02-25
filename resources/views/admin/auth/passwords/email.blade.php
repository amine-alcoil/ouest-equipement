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

        <form action="{{ route('admin.password.email') }}" method="POST" class="mt-4" id="resetPasswordForm">
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
            <div class="flex flex-col items-end gap-3 mt-6">
                <button type="submit" id="submitBtn" class="w-full sm:w-auto px-6 py-2.5 text-white bg-secondary rounded-lg hover:bg-secondary_2 focus:outline-none transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    Envoyer le lien de réinitialisation
                </button>
                <div id="timerContainer" class="hidden text-sm text-white/50 italic">
                    Vous pourrez renvoyer un lien dans <span id="countdown" class="font-bold text-white">60</span>s
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('resetPasswordForm');
        const submitBtn = document.getElementById('submitBtn');
        const timerContainer = document.getElementById('timerContainer');
        const countdownEl = document.getElementById('countdown');
        
        // Check if we should start a timer (e.g., after success status)
        @if (session('status'))
            startTimer(60);
        @endif

        form.addEventListener('submit', (e) => {
            if (submitBtn.disabled) {
                e.preventDefault();
                return;
            }
            // Temporarily disable to prevent double clicks during submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="flex items-center gap-2"><svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Envoi en cours...</span>';
        });

        function startTimer(seconds) {
            submitBtn.disabled = true;
            timerContainer.classList.remove('hidden');
            let timeLeft = seconds;

            const interval = setInterval(() => {
                timeLeft--;
                countdownEl.textContent = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(interval);
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Renvoyer le lien de réinitialisation';
                    timerContainer.classList.add('hidden');
                }
            }, 1000);
        }
    });
</script>
@endsection