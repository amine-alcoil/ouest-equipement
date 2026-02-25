<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Devis;
use App\Models\Client;
use App\Models\Product;
use App\Models\Task;
use App\Models\Activity;
use App\Models\Session as UserSession;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function visitorStats()
    {
        // 1. Sessions actives (Visiteurs Anonymes uniquement)
        $activeVisitors = UserSession::whereNull('user_id')
            ->orderBy('last_activity', 'desc')
            ->get();
        $activeSessionsCount = $activeVisitors->count();

        // 2. Utilisateurs Connectés (Admin/Staff)
        $activeAdmins = UserSession::whereNotNull('user_id')
            ->with('user')
            ->orderBy('last_activity', 'desc')
            ->get();
        
        // 3. Répartition par navigateur et appareil (Visiteurs uniquement)
        $browsers = [
            'Chrome' => 0, 'Firefox' => 0, 'Safari' => 0, 'Edge' => 0, 'Mobile' => 0, 'Desktop' => 0,
        ];

        foreach ($activeVisitors as $s) {
            $ua = $s->user_agent;
            if (stripos($ua, 'Mobile') !== false || stripos($ua, 'Android') !== false || stripos($ua, 'iPhone') !== false) {
                $browsers['Mobile']++;
            } else {
                $browsers['Desktop']++;
            }
            if (stripos($ua, 'Edg') !== false) $browsers['Edge']++;
            elseif (stripos($ua, 'Chrome') !== false) $browsers['Chrome']++;
            elseif (stripos($ua, 'Firefox') !== false) $browsers['Firefox']++;
            elseif (stripos($ua, 'Safari') !== false) $browsers['Safari']++;
        }

        // 4. Top IP Addresses (Historique)
        $topIps = UserSession::select('ip_address', DB::raw('count(*) as count'))
            ->groupBy('ip_address')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        // 5. Statistiques globales
        $totalLogins = \App\Models\User::sum('total_logins');
        
        // Simuler ou récupérer les vues totales (à stocker en DB idéalement)
        // Ici on utilise le nombre total de sessions créées comme indicateur de vues uniques totales
        $totalViews = UserSession::count(); 

        return view('admin.statistics', compact(
            'activeSessionsCount', 
            'browsers', 
            'topIps', 
            'totalLogins', 
            'activeVisitors', 
            'activeAdmins',
            'totalViews'
        ));
    }

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