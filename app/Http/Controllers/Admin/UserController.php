<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User; // Assuming you have a User model

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $query = User::query();
        if ($q !== '') {
            $query->where(function ($qbuilder) use ($q) {
                $qbuilder->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }
        $users = $query->orderBy('id', 'asc')->get();
        
        return view('admin.users', ['users' => $users, 'q' => $q]);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:user,admin', // Assuming roles are 'user' or 'admin'
            'status' => 'nullable|string|in:actif,inactif',
        ]);

        // Create the user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
            'status' => $validatedData['status'] ?? 'actif',
        ]);

        // Redirect back with a success message
        return back()->with('success', 'Utilisateur créé avec succès!');
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|string|in:user,admin',
            'status' => 'nullable|string|in:actif,inactif',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->role = $validatedData['role'];

        // Prevent first admin (ID 1) from being set to inactif
        if ($user->id === 1 && ($validatedData['status'] ?? '') === 'inactif') {
            return back()->withErrors(['status' => 'Le statut du premier administrateur ne peut pas être modifié en inactif.']);
        }

        $user->status = $validatedData['status'] ?? $user->status;
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->save();

        return back()->with('success', 'Utilisateur mis à jour avec succès!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Request $request, User $user)
    {
        $currentAdminId = session('admin_user')['id'] ?? null;
        if ($currentAdminId && (int)$currentAdminId === (int)$user->id) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé avec succès!');
    }
}
