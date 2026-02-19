@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-white">Téléchargements</h1>
        <a href="{{ route('admin.downloads.create') }}" class="px-4 py-2 bg-secondary text-white rounded-lg hover:bg-secondary/90 transition">
            + Ajouter un document
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="p-4 bg-green-500/10 border border-green-500/20 text-green-400 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-[#122241] rounded-xl border border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-white/70">
                <thead class="bg-white/5 uppercase font-medium text-white/50 text-xs">
                    <tr>
                        <th class="px-6 py-4">Image</th>
                        <th class="px-6 py-4">Titre</th>
                        <th class="px-6 py-4">Catégorie</th>
                        <th class="px-6 py-4">Fichier</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($downloads as $download)
                    <tr class="hover:bg-white/5 transition">
                        <td class="px-6 py-4">
                            @if($download->image_path)
                                <img src="{{ Storage::url($download->image_path) }}" class="h-10 w-10 object-cover rounded bg-white/10">
                            @else
                                <div class="h-10 w-10 bg-white/10 rounded flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-white">{{ $download->title }}</td>
                        <td class="px-6 py-4">{{ $download->category ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ Storage::url($download->file_path) }}" target="_blank" class="text-secondary hover:underline flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Voir
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            @if($download->status === 'active')
                                <span class="px-2 py-1 bg-green-500/10 text-green-400 rounded text-xs border border-green-500/20">Actif</span>
                            @else
                                <span class="px-2 py-1 bg-red-500/10 text-red-400 rounded text-xs border border-red-500/20">Inactif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                            <a href="{{ route('admin.downloads.edit', $download->id) }}" class="p-2 bg-white/5 hover:bg-white/10 rounded transition text-white/70 hover:text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                            <form action="{{ route('admin.downloads.destroy', $download->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-red-500/10 hover:bg-red-500/20 rounded transition text-red-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-white/30">
                            Aucun document trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection