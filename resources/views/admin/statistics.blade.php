@extends('admin.layouts.app')

@section('content')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-stat-card {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
    .pulse-live {
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
        animation: pulse-blue 2s infinite;
    }
    @keyframes pulse-blue {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }
    .glass-card {
        background: rgba(18, 34, 65, 0.4);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>

<div class="space-y-8 pb-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Analyse du Trafic</h1>
            <p class="text-white/50 text-sm mt-1">Surveillance en temps réel de l'activité des visiteurs.</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.location.reload()" class="p-2.5 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition-all group" title="Actualiser">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/70 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
            <div class="px-4 py-2 rounded-xl bg-secondary/10 text-secondary text-xs font-bold border border-secondary/20 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                ACCÈS ADMINISTRATEUR
            </div>
        </div>
    </div>

    <!-- Top Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Site Views -->
        <div class="animate-stat-card glass-card rounded-3xl p-6 shadow-2xl relative overflow-hidden group" style="animation-delay: 50ms">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-colors"></div>
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <div>
                    <div class="text-white/40 text-xs font-bold uppercase tracking-wider">Vues Totales</div>
                    <div id="stat-total-views" class="text-3xl font-black text-white mt-1">{{ number_format($totalViews, 0, ',', ' ') }}</div>
                </div>
            </div>
        </div>

        <!-- Active Visitors -->
        <div class="animate-stat-card glass-card rounded-3xl p-6 shadow-2xl relative overflow-hidden group" style="animation-delay: 100ms">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-colors"></div>
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-blue-500/20 text-blue-400 flex items-center justify-center pulse-live">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-white/40 text-xs font-bold uppercase tracking-wider">Visiteurs Live</div>
                    <div id="stat-active-sessions" class="text-3xl font-black text-white mt-1">{{ $activeSessionsCount }}</div>
                </div>
            </div>
        </div>

        <!-- Connected Users (Admins) -->
        <div class="animate-stat-card glass-card rounded-3xl p-6 shadow-2xl relative overflow-hidden group" style="animation-delay: 150ms">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-500/10 rounded-full blur-3xl group-hover:bg-orange-500/20 transition-colors"></div>
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-orange-500/20 text-orange-400 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-white/40 text-xs font-bold uppercase tracking-wider">Admins Online</div>
                    <div id="stat-active-admins" class="text-3xl font-black text-white mt-1">{{ $activeAdmins->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Total Logins -->
        <div class="animate-stat-card glass-card rounded-3xl p-6 shadow-2xl relative overflow-hidden group" style="animation-delay: 200ms">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-colors"></div>
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
                <div>
                    <div class="text-white/40 text-xs font-bold uppercase tracking-wider">Logins Total</div>
                    <div id="stat-total-logins" class="text-3xl font-black text-white mt-1">{{ number_format($totalLogins, 0, ',', ' ') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Browser Chart -->
        <div class="animate-stat-card glass-card rounded-3xl p-8 shadow-2xl" style="animation-delay: 300ms">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-white tracking-tight">Appareils & Navigateurs</h3>
                <div class="text-[10px] font-bold text-white/30 uppercase tracking-widest">Analyse Visiteurs</div>
            </div>
            <div class="h-72 relative">
                <canvas id="browserChart"></canvas>
            </div>
        </div>

        <!-- Connected Admins Details -->
        <div class="animate-stat-card glass-card rounded-3xl p-8 shadow-2xl" style="animation-delay: 400ms">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-white tracking-tight">Administrateurs Connectés</h3>
                <div class="text-[10px] font-bold text-orange-400/50 uppercase tracking-widest">Sessions Staff</div>
            </div>
            <div id="admins-list" class="space-y-4 max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
                @forelse($activeAdmins as $adminSession)
                <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5 group hover:bg-orange-500/5 hover:border-orange-500/20 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-orange-500/20 flex items-center justify-center text-orange-400 font-bold">
                            {{ substr($adminSession->user->name ?? 'A', 0, 1) }}
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white">{{ $adminSession->user->name ?? 'Admin Inconnu' }}</div>
                            <div class="text-[10px] text-white/40 font-mono">{{ $adminSession->ip_address }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-tighter">En ligne</span>
                        </div>
                        <div class="text-[10px] text-white/30 mt-1">{{ \Carbon\Carbon::createFromTimestamp($adminSession->last_activity)->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-white/20 italic text-sm">Aucun administrateur connecté.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8">
        <!-- Detailed Active Visitors - Full Width -->
        <div class="animate-stat-card glass-card rounded-3xl p-8 shadow-2xl" style="animation-delay: 500ms">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h3 class="text-2xl font-black text-white tracking-tight">Visiteurs Anonymes en Direct</h3>
                    <div class="text-xs font-bold text-blue-400/60 uppercase tracking-widest mt-1">Temps réel strict (IP Uniques)</div>
                </div>
                <div class="relative group">
                    <input type="text" id="visitorSearch" placeholder="Rechercher par IP..." 
                           class="bg-white/5 border border-white/10 rounded-2xl px-6 py-3 text-sm text-white placeholder-white/20 outline-none focus:border-blue-500/50 focus:bg-white/10 focus:ring-4 focus:ring-blue-500/10 transition-all w-full sm:w-72">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute right-4 top-3.5 text-white/20 group-focus-within:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            
            <div class="overflow-hidden rounded-3xl border border-white/5 bg-white/5 shadow-inner">
                <div class="max-h-[600px] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse" id="visitorTable">
                        <thead class="text-[11px] text-white/40 uppercase font-black tracking-widest bg-[#1a2b4b] sticky top-0 z-20">
                            <tr>
                                <th class="py-5 px-8">Statut</th>
                                <th class="py-5 px-6">Adresse IP</th>
                                <th class="py-5 px-6">Appareil & Navigateur</th>
                                <th class="py-5 px-8 text-right">Dernière Activité</th>
                            </tr>
                        </thead>
                        <tbody id="visitors-list" class="text-white/70 divide-y divide-white/5">
                            @forelse($activeVisitors as $visitor)
                            <tr class="hover:bg-blue-500/5 transition-all group visitor-row">
                                <td class="py-5 px-8">
                                    <div class="flex items-center gap-2">
                                        <span class="relative flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                                        </span>
                                        <span class="text-[10px] font-bold text-blue-400/80">LIVE</span>
                                    </div>
                                </td>
                                <td class="py-5 px-6">
                                    <span class="font-mono text-base text-blue-100 font-bold visitor-ip group-hover:text-white transition-colors">{{ $visitor->ip_address }}</span>
                                </td>
                                <td class="py-5 px-6">
                                    <div class="flex flex-col max-w-md">
                                        <span class="text-sm text-white/80 font-medium truncate" title="{{ $visitor->user_agent }}">
                                            {{ $visitor->user_agent }}
                                        </span>
                                        @if(stripos($visitor->user_agent, 'Mobile') !== false)
                                            <span class="text-[10px] text-purple-400 font-bold mt-1 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                SMARTPHONE
                                            </span>
                                        @else
                                            <span class="text-[10px] text-blue-400 font-bold mt-1 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                ORDINATEUR
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-5 px-8 text-right">
                                    <span class="px-4 py-1.5 rounded-full bg-blue-500/10 text-blue-300 text-xs font-bold border border-blue-500/20 shadow-sm">
                                        {{ \Carbon\Carbon::createFromTimestamp($visitor->last_activity)->diffForHumans() }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center">
                                    <div class="flex flex-col items-center gap-3 text-white/20">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-lg font-medium italic">Aucun visiteur anonyme en ligne</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Global Chart Instance
        let browserChart;
        let currentActiveCount = {{ $activeSessionsCount }};

        const ctx = document.getElementById('browserChart').getContext('2d');
        
        // Custom plugin to draw text in the center
        const centerTextPlugin = {
            id: 'centerText',
            afterDraw: (chart) => {
                const { ctx, chartArea: { top, bottom, left, right, width, height } } = chart;
                ctx.save();
                ctx.font = 'bold 12px sans-serif';
                ctx.fillStyle = 'rgba(255, 255, 255, 0.4)';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText('TOTAL', width / 2, (height / 2) + top - 10);
                
                ctx.font = 'bold 24px sans-serif';
                ctx.fillStyle = '#ffffff';
                ctx.fillText(currentActiveCount, width / 2, (height / 2) + top + 15);
                ctx.restore();
            }
        };

        browserChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($browsers)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($browsers)) !!},
                    backgroundColor: [
                        '#3b82f6', // Chrome
                        '#f97316', // Firefox
                        '#ec4899', // Safari
                        '#06b6d4', // Edge
                        '#10b981', // Mobile
                        '#64748b'  // Desktop
                    ],
                    hoverBackgroundColor: [
                        '#60a5fa', '#fb923c', '#f472b6', '#22d3ee', '#34d399', '#94a3b8'
                    ],
                    borderWidth: 0,
                    hoverOffset: 15,
                    borderRadius: 10,
                    spacing: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '80%',
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 2000,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: 'rgba(255, 255, 255, 0.6)',
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 25,
                            font: { size: 11, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#ffffff',
                        bodyColor: '#cbd5e1',
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: true,
                        usePointStyle: true
                    }
                }
            },
            plugins: [centerTextPlugin]
        });

        // --- NEW: Real-time Data Fetching ---
        function refreshStats() {
            fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update Simple Numbers
                document.getElementById('stat-total-views').textContent = data.totalViews.toLocaleString();
                document.getElementById('stat-active-sessions').textContent = data.activeSessionsCount;
                document.getElementById('stat-active-admins').textContent = data.activeAdmins.length;
                document.getElementById('stat-total-logins').textContent = data.totalLogins.toLocaleString();
                
                // Update Center Text in Chart
                currentActiveCount = data.activeSessionsCount;

                // Update Chart Data
                browserChart.data.labels = Object.keys(data.browsers);
                browserChart.data.datasets[0].data = Object.values(data.browsers);
                browserChart.update();

                // Update Admins List
                const adminList = document.getElementById('admins-list');
                if (data.activeAdmins.length === 0) {
                    adminList.innerHTML = '<div class="text-center py-10 text-white/20 italic text-sm">Aucun administrateur connecté.</div>';
                } else {
                    adminList.innerHTML = data.activeAdmins.map(admin => `
                        <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5 group hover:bg-orange-500/5 hover:border-orange-500/20 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-orange-500/20 flex items-center justify-center text-orange-400 font-bold">
                                    ${admin.name.charAt(0)}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-white">${admin.name}</div>
                                    <div class="text-[10px] text-white/40 font-mono">${admin.ip_address}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-tighter">En ligne</span>
                                </div>
                                <div class="text-[10px] text-white/30 mt-1">${admin.last_activity_human}</div>
                            </div>
                        </div>
                    `).join('');
                }

                // Update Visitors List
                const visitorsList = document.getElementById('visitors-list');
                if (data.activeVisitors.length === 0) {
                    visitorsList.innerHTML = '<tr><td colspan="4" class="py-20 text-center"><div class="flex flex-col items-center gap-3 text-white/20"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span class="text-lg font-medium italic">Aucun visiteur anonyme en ligne</span></div></td></tr>';
                } else {
                    visitorsList.innerHTML = data.activeVisitors.map(visitor => `
                        <tr class="hover:bg-blue-500/5 transition-all group visitor-row">
                            <td class="py-5 px-8">
                                <div class="flex items-center gap-2">
                                    <span class="relative flex h-3 w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                                    </span>
                                    <span class="text-[10px] font-bold text-blue-400/80">LIVE</span>
                                </div>
                            </td>
                            <td class="py-5 px-6">
                                <span class="font-mono text-base text-blue-100 font-bold visitor-ip group-hover:text-white transition-colors">${visitor.ip_address}</span>
                            </td>
                            <td class="py-5 px-6">
                                <div class="flex flex-col max-w-md">
                                    <span class="text-sm text-white/80 font-medium truncate" title="${visitor.user_agent}">
                                        ${visitor.user_agent}
                                    </span>
                                    ${visitor.is_mobile ? `
                                        <span class="text-[10px] text-purple-400 font-bold mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            SMARTPHONE
                                        </span>
                                    ` : `
                                        <span class="text-[10px] text-blue-400 font-bold mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            ORDINATEUR
                                        </span>
                                    `}
                                </div>
                            </td>
                            <td class="py-5 px-8 text-right">
                                <span class="px-4 py-1.5 rounded-full bg-blue-500/10 text-blue-300 text-xs font-bold border border-blue-500/20 shadow-sm">
                                    ${visitor.last_activity_human}
                                </span>
                            </td>
                        </tr>
                    `).join('');
                }
            })
            .catch(error => console.error('Error refreshing stats:', error));
        }

        // Auto-refresh every 10 seconds
        setInterval(refreshStats, 10000);

        // --- NEW: Real-time Visitor Search Function ---
        const visitorSearch = document.getElementById('visitorSearch');
        const visitorRows = document.querySelectorAll('.visitor-row');

        visitorSearch.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();

            visitorRows.forEach(row => {
                const ip = row.querySelector('.visitor-ip').textContent.toLowerCase();
                if (ip.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection