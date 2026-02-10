@extends('admin.layouts.app')
@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold">Modifier client</h1>
        <div class="text-white/60">Mettez à jour les informations du client.</div>
    </div>

    <div class="rounded-xl bg-[#122241] border border-white/10 p-4">
        {{-- تأكد أن نموذج التحديث يرسل الـ id --}}
        <!-- Update form action: use 'client' instead of 'id' -->
        <form method="POST" action="{{ route('admin.clients.update', ['client' => $client->id]) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-white/80 mb-1">Nom</label>
                    <input name="name" type="text" value="{{ old('name', $client->name) }}" required class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Email</label>
                    <input name="email" type="email" value="{{ old('email', $client->email) }}" required class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Téléphone</label>
                    <input name="phone" type="text" value="{{ old('phone', $client->phone) }}" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Société</label>
                    <input name="company" type="text" value="{{ old('company', $client->company) }}" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10">
                </div>
                <div>
                    <label class="block text-sm text-white/80 mb-1">Statut</label>
                    <select name="status" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10">
                        <option value="actif" @selected(old('status', $client->status) === 'actif')>Actif</option>
                        <option value="prospect" @selected(old('status', $client->status) === 'prospect')>Prospect</option>
                        <option value="inactif" @selected(old('status', $client->status) === 'inactif')>Inactif</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm text-white/80 mb-1">Notes</label>
                    <textarea name="notes" rows="4" class="w-full px-3 py-2 rounded-lg bg-[#0f1e34] border border-white/10">{{ old('notes', $client->notes) }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button class="px-4 py-2 rounded-lg bg-secondary border border-white/10 hover:bg-[#234161]">Enregistrer</button>
                <a href="{{ route('admin.clients') }}" class="px-4 py-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/15">Retour</a>
            </div>
        </form>
    </div>
</div>
@endsection