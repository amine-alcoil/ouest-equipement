@extends('admin.layouts.app')

@section('title', 'Messages de Contact')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Messages de Contact</h1>
        <div class="text-sm">
            Total: {{ $messages->count() }} messages
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/30 text-green-300 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(isset($error))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
            {{ $error }}
        </div>
    @endif
    <div id="inlineSuccess" class="hidden bg-green-500/10 border border-green-500/30 text-green-300 px-4 py-3 rounded mb-4">
        Message supprimé avec succès.
    </div>

    <div class="rounded-xl bg-[#122241] border border-white/10 overflow-hidden ">
        <!-- Loading Overlay -->
        <div id="tableLoading" class="hidden absolute inset-0 z-50 flex items-center justify-center bg-[#122241]/50 backdrop-blur-[2px] rounded-xl transition-all duration-300">
            <div class="relative">
                <div class="w-12 h-12 rounded-full border-4 border-white/10"></div>
                <div class="absolute top-0 left-0 w-12 h-12 rounded-full border-4 border-secondary border-t-transparent animate-spin"></div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Nom
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Téléphone
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Message
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($messages as $message)
                        <tr class="bg-[#122241]">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium">
                                    {{ $message->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">{{ $message->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">{{ $message->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm line-clamp-2">
                                    {{ \Illuminate\Support\Str::limit($message->message, 100) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($message->status === 'nouveau') bg-blue-100 text-blue-800
                                    @elseif($message->status === 'en_cours') bg-yellow-100 text-yellow-800
                                    @elseif($message->status === 'traite') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $message->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ url('/admin/contact-messages/' . $message->id) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ url('/admin/contact-messages/' . $message->id) }}" 
                                          method="POST" class="inline js-delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="text-red-600 hover:text-red-900 js-delete-trigger" data-message="Supprimer le message de {{ $message->name }} ?">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center">
                                Aucun message de contact trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
            <div id="confirmMessage" class="px-4 py-4 text-white/80">Êtes-vous sûr de vouloir supprimer cet élément ?</div>
            <div class="px-4 py-3 flex items-center justify-end gap-2">
                <button id="confirmCancel" type="button" class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15">Annuler</button>
                <button id="confirmOk" type="button" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">Supprimer</button>
            </div>
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
            msg.textContent=m?m:'Êtes-vous sûr de vouloir supprimer ce message ?';
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
                var row=currentForm.closest('tr');
                if(row){ row.remove(); }
                var success=document.getElementById('inlineSuccess');
                if(success){ success.classList.remove('hidden'); success.textContent='Message supprimé avec succès.'; }
            })
            .catch(function(){ hide(); })
            .finally(function(){
                if (tableLoading) {
                    setTimeout(() => tableLoading.classList.add('hidden'), 300);
                }
            });
    });
    btnCancel.addEventListener('click',function(){ hide(); });
    overlay.addEventListener('click',function(){ hide(); });
})();
</script>
@endsection
