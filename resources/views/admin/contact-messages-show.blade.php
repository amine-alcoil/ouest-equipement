@extends('admin.layouts.app')

@section('title', 'Détails du Message')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-white">Détails du Message</h1>
            <a href="{{ url('/admin/contact-messages') }}" 
               class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg transition-colors">
                Retour à la liste
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 text-green-300 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        <div id="inlineSuccess" class="hidden bg-green-500/10 border border-green-500/30 text-green-300 px-4 py-3 rounded mb-4">
            Message supprimé avec succès.
        </div>

        <div id="messageCard" class="bg-[#122241] rounded-lg shadow-md overflow-hidden relative">
            <!-- Loading Overlay -->
            <div id="tableLoading" class="hidden absolute inset-0 z-50 flex items-center justify-center bg-[#122241]/50 backdrop-blur-[2px] rounded-lg transition-all duration-300">
                <div class="relative">
                    <div class="w-12 h-12 rounded-full border-4 border-white/10"></div>
                    <div class="absolute top-0 left-0 w-12 h-12 rounded-full border-4 border-secondary border-t-transparent animate-spin"></div>
                </div>
            </div>
            <div class="px-6 py-4 border-b border-slate-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-semibold text-white">{{ $message->name }}</h2>
                        <p class="text-white/80">{{ $message->email }}</p>
                        @if($message->phone)
                            <p class="text-white/80">{{ $message->phone }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-white/70">Reçu le</p>
                        <p class="text-sm font-medium text-white/80">{{ $message->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-medium text-white mb-2">Message</h3>
                <div class="text-white/80 whitespace-pre-wrap">{{ $message->message }}</div>
            </div>

            <div class="px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-sm font-medium text-white mb-2">Statut actuel</h4>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full border
                            @if($message->status === 'nouveau') bg-blue-500/15 text-blue-300 border-blue-500/30
                            @elseif($message->status === 'en_cours') bg-yellow-400/15 text-yellow-200 border-yellow-400/30
                            @elseif($message->status === 'traite') bg-green-500/15 text-green-300 border-green-500/30
                            @else bg-white/10 text-white/80 border-white/15
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $message->status)) }}
                        </span>
                    </div>
                    
                    <form action="{{ url('/admin/contact-messages/' . $message->id . '/status') }}" 
                          method="POST" class="flex items-center space-x-2" onsubmit="document.getElementById('tableLoading').classList.remove('hidden')">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="border border-white/20 bg-[#122241] text-white rounded-lg px-3 py-2 text-sm"
                                onchange="this.form.submit()">
                            <option value="nouveau" {{ $message->status === 'nouveau' ? 'selected' : '' }}>
                                Nouveau
                            </option>
                            <option value="en_cours" {{ $message->status === 'en_cours' ? 'selected' : '' }}>
                                En cours
                            </option>
                            <option value="traite" {{ $message->status === 'traite' ? 'selected' : '' }}>
                                Traité
                            </option>
                            <option value="ferme" {{ $message->status === 'ferme' ? 'selected' : '' }}>
                                Fermé
                            </option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <form action="{{ url('/admin/contact-messages/' . $message->id) }}" 
                  method="POST" class="js-delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 js-delete-trigger" data-message="Supprimer le message de {{ $message->name }} ?">
                    Supprimer le message
                </button>
            </form>
        </div>
    </div>
</div>

<div id="confirmModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div id="confirmOverlay" class="absolute inset-0 bg-black/60"></div>
    <div class="relative mx-auto max-w-sm w-[92%]">
        <div class="rounded-xl bg-[#122241] border border-white/10 shadow-2xl">
            <div class="px-4 py-3 border-b border-white/10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-600/20 text-red-300 flex items-center justify-center">!</div>
                <div class="text-white font-semibold">Confirmer la suppression</div>
            </div>
            <div id="confirmMessage" class="px-4 py-4 text-white/80">Cette action est irréversible. Voulez-vous supprimer ce message ?</div>
            <div class="px-4 py-3 flex items-center justify-end gap-2">
                <button id="confirmCancel" type="button" class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15">Annuler</button>
                <button id="confirmOk" type="button" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">Supprimer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function(){
  var modal=document.getElementById('confirmModal');
  var overlay=document.getElementById('confirmOverlay');
  var msg=document.getElementById('confirmMessage');
  var btnOk=document.getElementById('confirmOk');
  var btnCancel=document.getElementById('confirmCancel');
  var currentForm=null;
  function show(){ modal.classList.remove('hidden'); }
  function hide(){ modal.classList.add('hidden'); currentForm=null; }
  Array.prototype.forEach.call(document.querySelectorAll('.js-delete-trigger'),function(btn){
    btn.addEventListener('click',function(e){
      e.preventDefault();
      currentForm=btn.closest('form');
      var m=btn.getAttribute('data-message');
      msg.textContent=m?m:'Voulez-vous supprimer ce message ?';
      show();
    });
  });
  var tableLoading = document.getElementById('tableLoading');
  btnOk.addEventListener('click',function(){
    if(!currentForm){ hide(); return; }
    if (tableLoading) tableLoading.classList.remove('hidden');
    var url=currentForm.action;
    var token=currentForm.querySelector('input[name=_token]')?.value || '';
    var data=new URLSearchParams();
    data.append('_token', token);
    data.append('_method', 'DELETE');
    fetch(url,{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest'},body:data})
      .then(function(){
        hide();
        // Redirect to list page with success notification
        window.location.href = "{{ url('/admin/contact-messages') }}?success=Message supprimé avec succès.";
      })
      .catch(function(){ 
        hide();
        if (tableLoading) tableLoading.classList.add('hidden');
      });
  });
  btnCancel.addEventListener('click',function(){ hide(); });
  overlay.addEventListener('click',function(){ hide(); });
})();
</script>
@endsection