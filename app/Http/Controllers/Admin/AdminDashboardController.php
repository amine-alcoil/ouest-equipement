<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Devis;
use App\Models\Client;
use App\Models\Product;
use App\Models\Task;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Devis Stats
        $totalDevis = Devis::count();
        $devisByStatus = Devis::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        $devisStats = [
            'total' => $totalDevis,
            'status' => array_merge([
                'nouveau' => 0,
                'en_cours' => 0,
                'envoye' => 0,
                'confirme' => 0,
                'annule' => 0,
            ], $devisByStatus)
        ];

        // Product Stats
        $productStats = [
            'total' => Product::count()
        ];

        // Client Stats
        $clientStats = [
            'total' => Client::count()
        ];

        // Recent Data
        $recentDevis = Devis::latest()->take(5)->get();
        $recentClients = Client::latest()->take(10)->get();
        $recentProducts = Product::latest()->take(5)->get();
        $recentActivities = Activity::with('user')->latest()->take(10)->get();

        // Category Stats
        $categoryStats = Product::select('category', DB::raw('count(*) as count'))
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        // Tasks (user-specific)
        $userId = session('admin_user.id') ?? optional(auth()->guard('admin')->user())->id;
        $tasks = Task::where('user_id', $userId)->latest()->take(5)->get();
        $allTasks = Task::where('user_id', $userId)->latest()->get();

        return view('admin.dashboard', compact(
            'devisStats',
            'productStats',
            'clientStats',
            'recentDevis',
            'recentClients',
            'recentProducts',
            'recentActivities',
            'categoryStats',
            'tasks',
            'allTasks'
        ));
    }
}