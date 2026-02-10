<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        
        $query = Client::query();
        
        if ($q !== '') {
            $query->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('phone', 'like', "%{$q}%")
                      ->orWhere('company', 'like', "%{$q}%")
                      ->orWhere('status', 'like', "%{$q}%");
            });
        }

        $clients = $query->orderBy('id')->get();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'clients' => $clients,
                'count' => $clients->count(),
            ]);
        }

        return view('admin.clients', [
            'clients' => $clients,
            'q' => $q,
        ]);
    }

    public function store(Request $request)
    {
        if ($request->has('status')) {
            $request->merge(['status' => strtolower((string) $request->input('status'))]);
        }
        $validated = $request->validate([
            'name'    => 'required|string|max:120',
            'email'   => 'nullable|email|max:160',
            'phone'   => 'nullable|string|max:40',
            'company' => 'nullable|string|max:160',
            'address' => 'nullable|string|max:200',
            'city'    => 'nullable|string|max:120',
            'website' => 'nullable|url|max:200',
            'type'    => 'nullable|string|in:Client,Partenaire',
            'status'  => 'nullable|string|in:actif,prospect,inactif',
            'notes'   => 'nullable|string|max:2000',
            'logo_file' => 'nullable|image|max:2048',
        ]);

        $logoUrl = null;
        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('clients', 'public');
            $logoUrl = Storage::url($path);
        }

        $client = Client::create([
            'name'    => $validated['name'],
            'email'   => $validated['email'] ?? null,
            'phone'   => $validated['phone'] ?? null,
            'company' => $validated['company'] ?? null,
            'address' => $validated['address'] ?? null,
            'city'    => $validated['city'] ?? null,
            'siteweb' => $validated['website'] ?? null,
            'logo'    => $logoUrl,
            'type'    => $validated['type'] ?? 'Client',
            'status'  => $validated['status'] ?? 'actif',
            'notes'   => $validated['notes'] ?? null,
        ]);

        $seq = Client::count();
        $client->ref_id = 'ALC-' . str_pad((string)$seq, 4, '0', STR_PAD_LEFT);
        $client->save();

        Log::info('Client created (database)', $client->toArray());

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'client' => $client,
                'message' => 'Client ajouté avec succès.',
            ]);
        }

        return redirect()->route('admin.clients')->with('success', 'Client ajouté avec succès.');
    }

    public function edit(Request $request, string $id)
    {
        $client = Client::find($id);
        
        if (!$client) {
            abort(404, 'Client introuvable');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'client' => $client,
            ]);
        }

        return view('admin.clients.edit', ['client' => $client]);
    }

    public function update(Request $request, string $id)
    {
        $client = Client::find($id);
        
        if (!$client) {
            abort(404, 'Client introuvable');
        }

        if ($request->has('status')) {
            $request->merge(['status' => strtolower((string) $request->input('status'))]);
        }
        $validated = $request->validate([
            'name'    => 'required|string|max:120',
            'email'   => 'nullable|email|max:160',
            'phone'   => 'nullable|string|max:40',
            'company' => 'nullable|string|max:160',
            'address' => 'nullable|string|max:200',
            'city'    => 'nullable|string|max:120',
            'website' => 'nullable|url|max:200',
            'type'    => 'nullable|string|in:Client,Partenaire',
            'status'  => 'nullable|string|in:actif,prospect,inactif',
            'notes'   => 'nullable|string|max:2000',
            'logo_file' => 'nullable|image|max:2048',
        ]);

        $client->name = $validated['name'];
        $client->email = $validated['email'] ?? null;
        $client->phone = $validated['phone'] ?? null;
        $client->company = $validated['company'] ?? null;
        $client->address = $validated['address'] ?? null;
        $client->city = $validated['city'] ?? null;
        $client->siteweb = $validated['website'] ?? null;
        $client->type = $validated['type'] ?? 'Client';
        $client->status = $validated['status'] ?? 'actif';
        $client->notes = $validated['notes'] ?? null;
        
        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('clients', 'public');
            $client->logo = Storage::url($path);
        }
        
        $client->save();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'client' => $client,
                'message' => 'Client mis à jour.',
            ]);
        }

        return redirect()->route('admin.clients')->with('success', 'Client mis à jour.');
    }

    public function updateStatus(Request $request, string $id)
    {
        $client = Client::find($id);
        if (!$client) {
            return response()->json(['ok' => false, 'message' => 'Client introuvable.'], 404);
        }

        $validated = $request->validate([
            'status' => 'required|string|in:actif,prospect,inactif',
        ]);

        $client->status = $validated['status'];
        $client->save();

        return response()->json([
            'ok' => true,
            'client' => $client,
            'message' => 'Statut mis à jour.',
        ]);
    }

    public function destroy(Request $request, string $id)
    {
        $client = Client::find($id);
        
        if (!$client) {
            return redirect()->route('admin.clients')->with('success', 'Client déjà supprimé.');
        }

        $client->delete();

        $all = Client::orderBy('id')->get();
        $i = 1;
        foreach ($all as $c) {
            $c->ref_id = 'ALC-' . str_pad((string)$i, 4, '0', STR_PAD_LEFT);
            $c->save();
            $i++;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'id' => $id,
                'message' => 'Client supprimé.',
            ]);
        }

        return redirect()->route('admin.clients')->with('success', 'Client supprimé.');
    }
}