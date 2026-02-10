<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $companyInfo = CompanyInfo::first();
        // Fetch authenticated admin user from database using session ID
        $adminUserId = session('admin_user')['id'] ?? null;
        $currentUser = $adminUserId ? User::find($adminUserId) : null;
        $notif = session('admin_settings.notifications', [
            'contact' => false,
            'order' => false,
            'stock' => false,
        ]);
        return view('admin.settings', compact('companyInfo', 'currentUser', 'notif'));
    }

    public function updateCompanyInfo(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $companyInfo = CompanyInfo::first();

        if (!$companyInfo) {
            $companyInfo = new CompanyInfo();
        }

        $companyInfo->company_name = $validated['company_name'] ?? null;
        $companyInfo->email = $validated['email'] ?? null;
        $companyInfo->phone = $validated['phone'] ?? null;
        $companyInfo->address = $validated['address'] ?? null;

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($companyInfo->logo_path && Storage::disk('public')->exists($companyInfo->logo_path)) {
                Storage::disk('public')->delete($companyInfo->logo_path);
            }

            // Store new logo
            $logoPath = $request->file('logo')->store('company', 'public');
            $companyInfo->logo_path = $logoPath;
        }

        $companyInfo->save();

        return back()->with('success', 'Informations de l\'entreprise mises à jour avec succès');
    }

    public function updateUserInfo(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . (session('admin_user')['id'] ?? null),
        ]);

        $adminUserId = session('admin_user')['id'] ?? null;
        $currentUser = $adminUserId ? User::find($adminUserId) : null;

        if (!$currentUser) {
            return back()->with('error', 'Utilisateur non trouvé.');
        }

        $currentUser->name = $validated['name'];
        $currentUser->email = $validated['email'];
        $currentUser->save();

        // Update session with new user info
        $adminUserSession = session('admin_user');
        $adminUserSession['name'] = $currentUser->name;
        $adminUserSession['email'] = $currentUser->email;
        session(['admin_user' => $adminUserSession]);

        return back()->with('success', 'Informations de l\'utilisateur mises à jour avec succès');
    }

    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'contact' => 'nullable|boolean',
            'order' => 'nullable|boolean',
            'stock' => 'nullable|boolean',
        ]);

        $prefs = [
            'contact' => (bool) ($validated['contact'] ?? false),
            'order' => (bool) ($validated['order'] ?? false),
            'stock' => (bool) ($validated['stock'] ?? false),
        ];

        session(['admin_settings.notifications' => $prefs]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'preferences' => $prefs]);
        }
        return back()->with('success', 'Notifications mises à jour.');
    }
}