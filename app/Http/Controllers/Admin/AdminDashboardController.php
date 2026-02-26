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
        // Temps limite très court pour le "Temps Réel" (2 minutes)
        // Si l'utilisateur ferme l'onglet, il disparaîtra rapidement
        $activeThreshold = time() - (2 * 60);

        // 1. Visiteurs Anonymes (SANS doublons d'IP et SANS admins)
        // On groupe par IP pour n'avoir qu'une ligne par visiteur réel
        $activeVisitors = UserSession::whereNull('user_id')
            ->where('last_activity', '>=', $activeThreshold)
            ->select('ip_address', 'user_agent', DB::raw('MAX(last_activity) as last_activity'))
            ->groupBy('ip_address', 'user_agent')
            ->orderBy('last_activity', 'desc')
            ->get();
            
        $activeSessionsCount = $activeVisitors->count();

        // 2. Administrateurs Connectés (Activité < 2 min)
        $activeAdmins = UserSession::whereNotNull('user_id')
            ->where('last_activity', '>=', $activeThreshold)
            ->with('user')
            ->select('user_id', 'ip_address', DB::raw('MAX(last_activity) as last_activity'))
            ->groupBy('user_id', 'ip_address')
            ->orderBy('last_activity', 'desc')
            ->get();

        // 3. Répartition par navigateur (uniquement sur les visiteurs filtrés)
        $browsers = ['Chrome' => 0, 'Firefox' => 0, 'Safari' => 0, 'Edge' => 0, 'Mobile' => 0, 'Desktop' => 0];
        foreach ($activeVisitors as $s) {
            $ua = $s->user_agent;
            if (stripos($ua, 'Mobile') !== false) $browsers['Mobile']++; else $browsers['Desktop']++;
            if (stripos($ua, 'Edg') !== false) $browsers['Edge']++;
            elseif (stripos($ua, 'Chrome') !== false) $browsers['Chrome']++;
            elseif (stripos($ua, 'Firefox') !== false) $browsers['Firefox']++;
            elseif (stripos($ua, 'Safari') !== false) $browsers['Safari']++;
        }

        // 4. Statistiques globales
        $totalLogins = \App\Models\User::sum('total_logins');
        $totalViews = UserSession::distinct('ip_address')->count(); 

        if (request()->ajax()) {
            return response()->json([
                'activeSessionsCount' => $activeSessionsCount,
                'totalLogins' => $totalLogins,
                'totalViews' => $totalViews,
                'browsers' => $browsers,
                'activeAdmins' => $activeAdmins->map(function($session) {
                    return [
                        'name' => $session->user->name ?? 'Admin Inconnu',
                        'ip_address' => $session->ip_address,
                        'last_activity_human' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans()
                    ];
                }),
                'activeVisitors' => $activeVisitors->map(function($visitor) {
                    return [
                        'ip_address' => $visitor->ip_address,
                        'user_agent' => $visitor->user_agent,
                        'last_activity_human' => \Carbon\Carbon::createFromTimestamp($visitor->last_activity)->diffForHumans(),
                        'is_mobile' => stripos($visitor->user_agent, 'Mobile') !== false
                    ];
                })
            ]);
        }

        return view('admin.statistics', compact(
            'activeSessionsCount', 
            'browsers', 
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