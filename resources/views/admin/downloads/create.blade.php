@extends('admin.layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.downloads.index') }}" class="p-2 bg-white/5 rounded-lg hover:bg-white/10 text-white/70 hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        </a>
        <h1 class="text-2xl font-semibold text-white">Ajouter un document</h1>
    </div>

    @if($errors->any())
        <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.downloads.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-[#122241] p-6 rounded-xl border border-white/5">
        @csrf

        <!-- Title -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-white/70">Titre</label>
            <input type="text" name="title" value="{{ old('title') }}" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-secondary transition">
        </div>

        <!-- Category -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-white/70">Catégorie</label>
            <select name="category" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-secondary transition">
                <option class="bg-[#122241] text-white" value="Brochure">Brochure</option>
                <option class="bg-[#122241] text-white" value="Catalogue">Catalogue</option>
                <option class="bg-[#122241] text-white" value="Fiche Technique">Fiche Technique</option>
                <option class="bg-[#122241] text-white" value="Guide">Guide</option>
                <option class="bg-[#122241] text-white" value="Autre">Autre</option>
            </select>
        </div>

        <!-- Description -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-white/70">Description</label>
            <textarea name="description" rows="3" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-secondary transition">{{ old('description') }}</textarea>
        </div>

        <!-- File -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-white/70">Fichier (PDF, DOC, XLS, ZIP - Max 20MB)</label>
            <input type="file" name="file" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-secondary file:text-white hover:file:bg-secondary/90 transition">
        </div>

        <!-- Image -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-white/70">Image de couverture (Optionnel)</label>
            <input type="file" name="image" accept="image/*" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-secondary file:text-white hover:file:bg-secondary/90 transition">
        </div>

        <!-- Status -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-white/70">Statut</label>
            <select name="status" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-secondary transition">
                <option class="bg-[#122241] text-white" value="active" selected>Actif</option>
                <option class="bg-[#122241] text-white" value="inactive">Inactif</option>
            </select>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="px-6 py-2 bg-secondary text-white rounded-lg hover:bg-secondary/90 transition shadow-lg shadow-secondary/20">
                Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection