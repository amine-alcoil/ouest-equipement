<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DevisController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\ContactMessageController; // Added
use App\Http\Controllers\Admin\SettingsController;      // Added
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\TaskController;

// Public pages
Route::get('/', function () {
    $products = \App\Models\Product::query()
        ->where('status', '!=', 'inactif')
        ->orderBy('created_at','desc')
        ->take(3)
        ->get()
        ->map(function($p){
        $images = is_array($p->images) ? $p->images : [];
        $img = $images[0] ?? '/images/no_image.png' ;
        return [
            'name' => $p->name,
            'desc' => $p->description ?? '',
            'img'  => $img,
            'url'  => route('produit.details', ['id' => $p->id]),
        ];
    })->toArray();

    $partners = \App\Models\Client::query()
        ->whereNotNull('logo')
        ->where('logo', '!=', '')
        ->where('status', '!=', 'inactif')
        ->orderBy('id')
        ->get(['logo', 'siteweb'])
        ->toArray();

    return view('home', compact('products', 'partners'));
});

Route::get('/a-propos', function () {
    return view('apropos');
})->name('apropos');

// Legal pages
Route::get('/mentions-legales', function () { return view('layouts.legal'); })->name('legal');
Route::get('/confidentialite', function () { return view('layouts.privacy'); })->name('privacy');
Route::get('/cookies', function () { return view('layouts.cookies'); })->name('cookies');

Route::get('/produits', function () {
    $products = \App\Models\Product::query()
        ->where('status', '!=', 'inactif')
        ->orderBy('id')
        ->get()
        ->map(function($p){
        $images = is_array($p->images) ? $p->images : [];
        $image = $images[0] ?? '/images/no_image.png';
    $ratings = is_array($p->ratings) ? $p->ratings : ['counts'=>[]];
    $counts = $ratings['counts'] ?? [];
    $totalVotes = array_sum(array_map('intval', $counts));
    $weighted = 0; foreach ($counts as $stars => $n) { $weighted += ((int)$stars) * ((int)$n); }
    $avgRating = $totalVotes > 0 ? round($weighted / $totalVotes, 1) : 0;
        return [
            'id' => $p->id,
            'name' => $p->name,
            'description' => $p->description ?? '',
            'image' => $image,
            'category' => $p->category,
            'price' => (float) ($p->price ?? 0),
            'available' => (int)($p->stock ?? 0) > 0,
            'tags' => $p->tags ?? [],
            'rating' => $avgRating,
            'ratingCount' => $totalVotes,
            'status' => $p->status ?? 'actif',
            'createdAt' => optional($p->created_at)->format('Y-m-d'),
        ];
    })->toArray();
    $categories = \App\Models\Category::query()->orderBy('name')->pluck('name')->toArray();
    $tags = \App\Models\Tag::query()->orderBy('name')->pluck('name')->toArray();
    return view('produits', compact('products', 'categories', 'tags'));
})->name('produits');

Route::get('/produits/{id}', function ($id) {
    $p = \App\Models\Product::find($id);
    if (!$p) {
        return view('admin.layouts.product_details', ['product' => null]);
    }
    $images = is_array($p->images) ? $p->images : [];
    $image = $images[0] ?? '/images/no_image.png';
    $ratings = is_array($p->ratings) ? $p->ratings : ['counts'=>[]];
    $counts = $ratings['counts'] ?? [];
    $totalVotes = array_sum(array_map('intval', $counts));
    $weighted = 0; foreach ($counts as $stars => $n) { $weighted += ((int)$stars) * ((int)$n); }
    $avgRating = $totalVotes > 0 ? round($weighted / $totalVotes, 1) : 0;
    $product = [
        'id' => $p->id,
        'name' => $p->name,
        'description' => $p->description ?? '',
        'image' => $image,
        'images' => $images,
        'category' => $p->category,
        'price' => (float) ($p->price ?? 0),
        'available' => (int)($p->stock ?? 0) > 0,
        'tags' => $p->tags ?? [],
        'pdf' => $p->pdf ?? null,
        'rating' => $avgRating,
        'ratingCount' => $totalVotes,
        'createdAt' => optional($p->created_at)->format('Y-m-d'),
    ];
    return view('admin.layouts.product_details', compact('product'));
})->name('produit.details');

Route::post('/produits/{id}/rate', function (Request $request, $id) {
    try {
        $p = \App\Models\Product::find($id);
        if (!$p) { return response()->json(['ok'=>false,'message'=>'Produit introuvable'],404); }
        $stars = (int) $request->input('stars');
        if ($stars < 1 || $stars > 5) { return response()->json(['ok'=>false,'message'=>'Note invalide'],422); }

        $rated = [];
        try { $rated = json_decode((string) $request->cookie('alc_rated_products','[]'), true) ?: []; } catch (\Throwable $e) { $rated = []; }
        
        if (in_array($id, $rated, true)) {
            $ratings = is_array($p->ratings) ? $p->ratings : ['counts'=>[]];
            $counts = $ratings['counts'] ?? [];
            $totalVotes = array_sum(array_map('intval', $counts));
            $weighted = 0; foreach ($counts as $s => $n) { $weighted += ((int)$s) * ((int)$n); }
            $avgRating = $totalVotes > 0 ? round($weighted / $totalVotes, 1) : 0;
            return response()->json(['ok'=>false,'message'=>'Vous avez déjà voté pour ce produit','rating'=>$avgRating,'count'=>$totalVotes], 409);
        }

        $ratings = is_array($p->ratings) ? $p->ratings : ['counts'=>[]];
        $counts = $ratings['counts'] ?? [];
        $counts[(string)$stars] = ((int)($counts[(string)$stars] ?? 0)) + 1;
        $p->ratings = ['counts' => $counts];
        $p->save();

        $rated[] = $id;
        $minutes = 365*24*60;
        $totalVotes = array_sum(array_map('intval', $counts));
        $weighted = 0; foreach ($counts as $s => $n) { $weighted += ((int)$s) * ((int)$n); }
        $avgRating = $totalVotes > 0 ? round($weighted / $totalVotes, 1) : 0;

        return response()->json(['ok'=>true,'rating'=>$avgRating,'count'=>$totalVotes])
            ->cookie('alc_rated_products', json_encode($rated), $minutes);
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Rating error: ' . $e->getMessage());
        return response()->json(['ok'=>false, 'message'=>'Erreur serveur lors du vote.'], 500);
    }
})->name('produit.rate');

Route::get('/test', function () {
    return view('home');
});

// Contact page
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Contact form submit (simple log + success flash)
Route::post('/contact', function (Request $request) {
    $validated = $request->validate([
        'name'    => 'required|string|max:120',
        'email'   => 'required|email',
        'phone'   => 'nullable|string|max:40',
        'message' => 'required|string|max:2000',
    ]);

    \App\Models\ContactMessage::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'message' => $validated['message'],
        'status' => 'nouveau',
    ]);

    return back()->with('success', 'Merci ! Votre message a été enregistré.');
})->name('contact.submit');

// Devis page
Route::get('/devis', function () {
    return view('devis');
})->name('devis');

// Devis standard submit
Route::post('/devis/standard', function (Request $request) {
    $validated = $request->validate([
        'name'    => 'required|string|max:120',
        'email'   => 'required|email',
        'phone'   => 'nullable|string|max:40',
        'company' => 'nullable|string|max:120',
        'product' => 'nullable|string|max:200',
        'quantity'=> 'nullable|integer|min:1',
        'message' => 'required|string|max:3000',
    ]);

    $last = \App\Models\Devis::orderBy('ref_id','desc')->first();
    $n = 1;
    if ($last && preg_match('/^ALC-DEV-(\d{5})$/', $last->ref_id, $m)) { $n = ((int)$m[1]) + 1; }
    $refId = 'ALC-DEV-'.str_pad((string)$n, 5, '0', STR_PAD_LEFT);

    \App\Models\Devis::create([
        'ref_id' => $refId,
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'company' => $validated['company'] ?? null,
        'type' => 'standard',
        'product' => $validated['product'] ?? null,
        'quantity' => $validated['quantity'] ?? 1,
        'message' => $validated['message'],
        'status' => 'nouveau',
        'date' => now(),
    ]);

    return back()->with('success', 'Votre demande de devis standard a été enregistrée.');
})->name('devis.standard');

// Devis spécifique submit
Route::post('/devis/specific', function (Request $request) {
    $requirements = $request->input('requirements');
    if (is_array($requirements)) { $request->merge(['requirements' => implode("\n", $requirements)]); }
    $validated = $request->validate([
        'name'         => 'required|string|max:120',
        'email'        => 'required|email',
        'phone'        => 'nullable|string|max:40',
        'company'      => 'nullable|string|max:120',
        'requirements' => 'required|string|max:5000',
        'type_exchangeur'     => 'nullable|string|max:200',
        'cuivre_diametre'     => 'nullable|numeric|min:0',
        'pas_ailette'         => 'nullable|numeric|min:0',
        'hauteur_mm'          => 'nullable|numeric|min:0',
        'largeur_mm'          => 'nullable|numeric|min:0',
        'longueur_mm'         => 'nullable|numeric|min:0',
        'longueur_totale_mm'  => 'nullable|numeric|min:0',
        'collecteur1_mm'      => 'nullable|numeric|min:0',
        'collecteur1_diametre'=> 'nullable|numeric|min:0',
        'collecteur2_mm'      => 'nullable|numeric|min:0',
        'collecteur2_diametre'=> 'nullable|numeric|min:0',
        'nombre_tubes'        => 'nullable|integer|min:1',
        'geometrie_x_mm'      => 'nullable|numeric|min:0',
        'geometrie_y_mm'      => 'nullable|numeric|min:0',
        'collecteur1_nb'      => 'nullable|integer|min:0',
        'collecteur2_nb'      => 'nullable|integer|min:0',
        'files'               => 'nullable',
        'files.*'             => 'file|mimes:jpg,jpeg,png,gif,webp,pdf,dwg,dxf|max:8192',
    ]);

    $last = \App\Models\Devis::orderBy('ref_id','desc')->first();
    $n = 1;
    if ($last && preg_match('/^ALC-DEV-(\d{5})$/', $last->ref_id, $m)) { $n = ((int)$m[1]) + 1; }
    $refId = 'ALC-DEV-'.str_pad((string)$n, 5, '0', STR_PAD_LEFT);

    $attachments = [];
    if ($request->hasFile('files')) {
        foreach ($request->file('files') as $file) {
            if (!$file->isValid()) continue;
            $path = $file->store('devis', 'public');
            $attachments[] = \Illuminate\Support\Facades\Storage::url($path);
        }
    }

    \App\Models\Devis::create([
        'ref_id' => $refId,
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'company' => $validated['company'] ?? null,
        'type' => 'specific',
        'requirements' => $validated['requirements'],
        'type_exchangeur'      => $validated['type_exchangeur'] ?? null,
        'cuivre_diametre'      => $validated['cuivre_diametre'] ?? null,
        'pas_ailette'          => $validated['pas_ailette'] ?? null,
        'hauteur_mm'           => $validated['hauteur_mm'] ?? null,
        'largeur_mm'           => $validated['largeur_mm'] ?? null,
        'longueur_mm'          => $validated['longueur_mm'] ?? null,
        'longueur_totale_mm'   => $validated['longueur_totale_mm'] ?? null,
        'collecteur1_mm'       => $validated['collecteur1_mm'] ?? null,
        'collecteur1_diametre' => $validated['collecteur1_diametre'] ?? null,
        'collecteur2_mm'       => $validated['collecteur2_mm'] ?? null,
        'collecteur2_diametre' => $validated['collecteur2_diametre'] ?? null,
        'nombre_tubes'         => $validated['nombre_tubes'] ?? null,
        'geometrie_x_mm'       => $validated['geometrie_x_mm'] ?? null,
        'geometrie_y_mm'       => $validated['geometrie_y_mm'] ?? null,
        'collecteur1_nb'       => $validated['collecteur1_nb'] ?? null,
        'collecteur2_nb'       => $validated['collecteur2_nb'] ?? null,
        'attachments'          => $attachments,
        'status' => 'nouveau',
        'date' => now(),
    ]);

    return back()->with('success', 'Votre demande de devis spécifique a été enregistrée.');
})->name('devis.specific');

// Admin routes (auth + dashboard + produits + clients)
Route::prefix('admin')->group(function () {
    // Login
    Route::get('/login', [AdminAuthController::class, 'showLogin'])
        ->middleware(\App\Http\Middleware\RedirectIfAdminAuthenticated::class)
        ->name('admin.login');

    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('admin.login.post');

    // Logout
    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('admin.logout');

    // Admin Password Reset (non-protected)
    Route::get('/password/request', [AdminAuthController::class, 'showLinkRequestForm'])
        ->name('admin.password.request');
    Route::post('/password/email', [AdminAuthController::class, 'sendResetLinkEmail'])
        ->name('admin.password.email');
    Route::get('/password/reset/{token}', [AdminAuthController::class, 'showResetForm'])
        ->name('admin.password.reset');
    Route::post('/password/reset', [AdminAuthController::class, 'resetPassword'])
        ->name('admin.password.reset.post');

    // Protected area (use middleware class directly, no alias)
    Route::middleware(\App\Http\Middleware\AdminSession::class)->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        // Produits
        Route::get('/produits', [ProductController::class, 'index'])
            ->name('admin.products');
        Route::post('/produits', [ProductController::class, 'store'])
            ->name('admin.products.store');
        // add edit/update/delete endpoints with {product} param
        Route::get('/produits/{product}/edit', [ProductController::class, 'edit'])
            ->name('admin.products.edit');
        Route::put('/produits/{product}', [ProductController::class, 'update'])
            ->name('admin.products.update');
        Route::delete('/produits/{product}', [ProductController::class, 'destroy'])
            ->name('admin.products.destroy');
        Route::delete('/produits/{product}/image', [ProductController::class, 'deleteImage'])
            ->name('admin.products.delete-image');
        Route::delete('/produits/{product}/image', [ProductController::class, 'deleteImage'])
            ->name('admin.products.delete-image');

        // Clients
        Route::get('/clients', [ClientController::class, 'index'])
            ->name('admin.clients');
        Route::post('/clients', [ClientController::class, 'store'])
            ->name('admin.clients.store');
        Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])
            ->name('admin.clients.edit');
        Route::put('/clients/{client}', [ClientController::class, 'update'])
            ->name('admin.clients.update');
        Route::patch('/clients/{client}/status', [ClientController::class, 'updateStatus'])
            ->name('admin.clients.update-status');
        Route::delete('/clients/{client}', [ClientController::class, 'destroy'])
            ->name('admin.clients.destroy');

        // Catégories
        Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
        Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

        // Tags
        Route::get('/tags', [TagController::class, 'index'])->name('admin.tags');
        Route::post('/tags', [TagController::class, 'store'])->name('admin.tags.store');
        Route::get('/tags/{tag}/edit', [TagController::class, 'edit'])->name('admin.tags.edit');
        Route::put('/tags/{tag}', [TagController::class, 'update'])->name('admin.tags.update');
        Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('admin.tags.destroy');

        // Devis - Complete CRUD
        Route::get('/devis', [DevisController::class, 'index'])->name('admin.devis');
        Route::get('/devis/create', [DevisController::class, 'create'])->name('admin.devis.create');
        Route::post('/devis', [DevisController::class, 'store'])->name('admin.devis.store');
        Route::get('/devis/{id}/edit', [DevisController::class, 'edit'])->name('admin.devis.edit');
        Route::put('/devis/{id}', [DevisController::class, 'update'])->name('admin.devis.update');
        Route::delete('/devis/{id}', [DevisController::class, 'destroy'])->name('admin.devis.destroy');
        Route::delete('/devis/{id}/attachment', [DevisController::class, 'deleteAttachment'])->name('admin.devis.delete-attachment');
        Route::patch('/devis/{id}/status', [DevisController::class, 'updateStatus'])->name('admin.devis.update-status');
        Route::post('/devis/send-email', [DevisController::class, 'sendEmail'])->name('admin.devis.send-email');
        Route::get('/devis/export', [DevisController::class, 'export'])->name('admin.devis.export');
    Route::get('/devis/{id}/pdf', [DevisController::class, 'exportPdf'])->name('admin.devis.export-pdf');
    Route::get('/devis/stats', [DevisController::class, 'stats'])->name('admin.devis.stats');

        // Contact Messages
        Route::get('/contact-messages', [ContactMessageController::class, 'index'])
            ->name('admin.contact-messages');
        Route::get('/contact-messages/{message}', [ContactMessageController::class, 'show'])
            ->name('admin.contact-messages.show');
        Route::patch('/contact-messages/{message}/status', [ContactMessageController::class, 'updateStatus'])
            ->name('admin.contact-messages.update-status');
        Route::delete('/contact-messages/{message}', [ContactMessageController::class, 'destroy'])
            ->name('admin.contact-messages.destroy');

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])
            ->name('admin.settings');
        Route::post('/settings/update-company-info', [SettingsController::class, 'updateCompanyInfo'])
            ->name('admin.settings.update-company-info');
        Route::post('/settings/update-user-info', [SettingsController::class, 'updateUserInfo'])
            ->name('admin.settings.update-user-info');
        Route::post('/settings/update-notifications', [SettingsController::class, 'updateNotifications'])
            ->name('admin.settings.update-notifications');

        // Users (Admin role only)
        Route::middleware(\App\Http\Middleware\AdminRole::class)->group(function () {
            Route::get('/users', [UserController::class, 'index'])->name('admin.users');
            Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        });

        // Tasks
        Route::post('/tasks', [TaskController::class, 'store'])->name('admin.tasks.store');
        Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('admin.tasks.update');
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('admin.tasks.destroy');
        Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('admin.tasks.toggle');

        // Default /admin -> redirect to dashboard
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });
    });
});