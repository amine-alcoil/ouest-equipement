@extends('admin.layouts.app')

@section('content')
<!-- Loading state -->
<div id="loadingState" class="hidden py-20 flex flex-col justify-center items-center gap-3">
    <div class="relative">
        <div class="w-10 h-10 rounded-full border-4 border-white/10"></div>
        <div class="absolute top-0 left-0 w-10 h-10 rounded-full border-4 border-secondary border-t-transparent animate-spin"></div>
    </div>
    <span class="text-white/60 text-xs">Chargement des données...</span>
</div>

<div id="dashboardContent">
    <!-- Top KPIs -->
<div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4">
    <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
        <div class="text-white/70 text-sm">Total Devis</div>
        <div class="mt-2 text-3xl font-bold text-secondary" data-stat="total">{{ $devisStats['total'] ?? 0 }}</div>
        <div class="mt-3 h-2 rounded bg-white/10 overflow-hidden">
            <div class="h-full w-full bg-secondary_2"></div>
        </div>
    </div>
    <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
        <div class="text-white/70 text-sm">Devis Nouveaux</div>
        <div class="mt-2 text-3xl font-bold text-blue-400" data-stat="nouveau">{{ $devisStats['status']['nouveau'] ?? 0 }}</div>
        <div class="mt-3 h-2 rounded bg-white/10 overflow-hidden">
            <div class="h-full bg-blue-400" style="width: {{ min(100, ($devisStats['status']['nouveau'] ?? 0) * 10) }}%"></div>
        </div>
    </div>
    <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
        <div class="text-white/70 text-sm">Devis En Cours</div>
        <div class="mt-2 text-3xl font-bold text-orange-400" data-stat="en_cours">{{ $devisStats['status']['en_cours'] ?? 0 }}</div>
        <div class="mt-3 h-2 rounded bg-white/10 overflow-hidden">
            <div class="h-full bg-orange-400" style="width: {{ min(100, ($devisStats['status']['en_cours'] ?? 0) * 10) }}%"></div>
        </div>
    </div>
    <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
        <div class="text-white/70 text-sm">Devis Confirmés</div>
        <div class="mt-2 text-3xl font-bold text-emerald-400" data-stat="confirme">{{ $devisStats['status']['confirme'] ?? 0 }}</div>
        <div class="mt-3 h-2 rounded bg-white/10 overflow-hidden">
            <div class="h-full bg-emerald-400" style="width: {{ min(100, ($devisStats['status']['confirme'] ?? 0) * 10) }}%"></div>
        </div>
    </div>
</div>

<!-- Devis Status Overview -->
<div class="mt-6 grid sm:grid-cols-2 xl:grid-cols-4 gap-4">
    <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
        <div class="text-white/70 text-sm">Devis Envoyés</div>
        <div class="mt-2 text-3xl font-bold text-indigo-400" data-stat="envoye">{{ $devisStats['status']['envoye'] ?? 0 }}</div>
        <div class="mt-3 h-2 rounded bg-white/10 overflow-hidden">
            <div class="h-full bg-indigo-400" style="width: {{ min(100, ($devisStats['status']['envoye'] ?? 0) * 10) }}%"></div>
        </div>
    </div>
    <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
        <div class="text-white/70 text-sm">Devis Annulés</div>
        <div class="mt-2 text-3xl font-bold text-red-400" data-stat="annule">{{ $devisStats['status']['annule'] ?? 0 }}</div>
        <div class="mt-3 h-2 rounded bg-white/10 overflow-hidden">
            <div class="h-full bg-red-400" style="width: {{ min(100, ($devisStats['status']['annule'] ?? 0) * 10) }}%"></div>
        </div>
    </div>
    <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
        <div class="text-white/70 text-sm">Total Produits</div>
        <div class="mt-2 text-3xl font-bold text-secondary" data-count-to="{{ $productStats['total'] ?? 128 }}">0</div>
        <div class="mt-3 h-2 rounded bg-white/10 overflow-hidden">
            <div class="h-full w-2/3 bg-secondary_2"></div>
        </div>
    </div>
    <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
        <div class="text-white/70 text-sm">Total Clients</div>
        <div class="mt-2 text-3xl font-bold text-secondary" data-count-to="{{ $clientStats['total'] ?? 42 }}">0</div>
        <div class="mt-3 h-2 rounded bg-white/10 overflow-hidden">
            <div class="h-full w-3/4 bg-secondary_2"></div>
        </div>
    </div>
</div>

<!-- Charts + Activity -->
<div class="mt-6 grid lg:grid-cols-3 gap-4">
    <!-- Recent Clients Table -->
    <div class="lg:col-span-2 rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-lg text-white">Clients Récents</h3>
            <a href="{{ route('admin.clients') }}" class="rounded-lg bg-white/10 border border-white/15 px-3 py-1.5 hover:bg-white/15 text-sm">Voir tout</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-white/50 uppercase border-b border-white/10">
                    <tr>
                        <th class="py-3 px-2">Client</th>
                        <th class="py-3 px-2">Contact</th>
                        <th class="py-3 px-2">Ville</th>
                        <th class="py-3 px-2">Statut</th>
                        <th class="py-3 px-2 text-right">Inscrit</th>
                    </tr>
                </thead>
                <tbody class="text-white/80 divide-y divide-white/5">
                    @forelse($recentClients ?? [] as $client)
                    <tr class="hover:bg-white/5 transition">
                        <td class="py-3 px-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-xs border border-blue-500/30">
                                    {{ substr($client->name, 0, 2) }}
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $client->name }}</div>
                                    <div class="text-xs text-white/50">{{ $client->company ?? 'Particulier' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-2">
                            <div class="text-xs">{{ $client->email }}</div>
                            <div class="text-xs text-white/50">{{ $client->phone ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-2">
                            {{ $client->city ?? '-' }}
                        </td>
                        <td class="py-3 px-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium {{ ($client->status ?? 'actif') == 'actif' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                                {{ ucfirst($client->status ?? 'Actif') }}
                            </span>
                        </td>
                        <td class="py-3 px-2 text-right text-xs text-white/50">
                            {{ $client->created_at->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-white/40">Aucun client récent</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Products -->
    <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20 flex flex-col gap-4">
        <div class="flex items-center justify-between mb-2">
            <h3 class="font-semibold text-lg text-white">Produits récents</h3>
            <a href="{{ route('admin.products') }}" class="rounded-lg bg-white/10 border border-white/15 px-3 py-1.5 hover:bg-white/15 text-sm">Voir tout</a>
        </div>
        <div class="space-y-2">
            @forelse($recentProducts ?? [] as $product)
                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition group">
                    @if(!empty($product->images) && is_array($product->images) && count($product->images) > 0)
                        <img src="{{ asset($product->images[0]) }}" class="w-10 h-10 rounded-lg object-cover bg-black/20 shadow-sm" alt="{{ $product->name }}">
                    @else
                        <img src="{{ asset('images/no_image.png') }}" class="w-10 h-10 rounded-lg object-cover bg-white/5 opacity-60" alt="No Image">
                    @endif
                    
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-white/90 truncate group-hover:text-white transition-colors">{{ $product->name }}</div>
                        <div class="text-xs text-white/50 truncate">{{ $product->category }}</div>
                    </div>

                    <div class="text-right whitespace-nowrap pl-2">
                        <div class="text-sm font-bold text-emerald-400">{{ number_format($product->price ?? 0, 0, ',', ' ') }} <span class="text-[10px] font-normal opacity-80">DA</span></div>
                    </div>
                </div>
            @empty
                <div class="text-xs text-white/40 text-center py-4">Aucun produit</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Categories Pie + Activity Table -->
<div class="mt-6 grid lg:grid-cols-3 gap-4">
    <div class="rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
        <h3 class="font-semibold">Répartition par catégories</h3>
        <div class="mt-4">
<canvas id="categoriesChart" height="80"></canvas>
        </div>
    </div>

    <div class="lg:col-span-2 rounded-2xl bg-[#122241] border border-white/10 p-4 shadow-lg shadow-black/20">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold">Activité récente des devis</h3>
            <a href="{{ route('admin.devis') }}" class="rounded-lg bg-white/10 border border-white/15 px-3 py-1.5 hover:bg-white/15 text-sm">Voir tout</a>
        </div>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-white/60">
                        <th class="text-left py-2 pr-4">Devis</th>
                        <th class="text-left py-2 pr-4">Entreprise</th>
                        <th class="text-left py-2 pr-4">Produit</th>
                        <th class="text-left py-2 pr-4">Statut</th>
                        <th class="text-left py-2">Date</th>
                    </tr>
                </thead>
                <tbody class="text-white/80">
                    @forelse($recentDevis ?? [] as $devis)
                    <tr class="border-t border-white/10">
                        <td class="py-2 pr-4">
                            <div class="font-medium">Devis #{{ $devis['id'] }}</div>
                            <div class="text-xs text-white/60">{{ $devis['name'] }}</div>
                        </td>
                        <td class="py-2 pr-4">{{ $devis['company'] }}</td>
                        <td class="py-2 pr-4">
                            @if(isset($devis['product']))
                                {{ $devis['product'] }}
                            @else
                                Devis Spécifique
                            @endif
                        </td>
                        <td class="py-2 pr-4">
                            @php
                                $statusClass = [
                                    'nouveau' => 'bg-blue-500/20 text-blue-300',
                                    'en_cours' => 'bg-orange-500/20 text-orange-300',
                                    'envoye' => 'bg-indigo-500/20 text-indigo-300',
                                    'confirme' => 'bg-emerald-500/20 text-emerald-300',
                                    'annule' => 'bg-red-500/20 text-red-300'
                                ][$devis['status']] ?? 'bg-white/20 text-white';
                            @endphp
                            <span class="px-2 py-1 rounded text-xs {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $devis['status'])) }}
                            </span>
                        </td>
                        <td class="py-2 text-xs text-white/60">
                            {{ \Carbon\Carbon::parse($devis['created_at'])->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-white/60">
                            Aucune activité récente des devis
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>




<!-- Quick Actions + Recent User Activities (Admin only) -->
<div class="mt-6 grid {{ ((session('admin_user')['role'] ?? 'user') === 'admin') ? 'lg:grid-cols-2' : 'lg:grid-cols-1' }} gap-4">
    <div class="rounded-2xl bg-[#122241] border border-white/10 p-6 shadow-lg shadow-black/20">
        <h3 class="font-semibold text-lg text-white mb-4">Actions rapides</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.products') }}" class="group flex flex-col items-center justify-center gap-2 rounded-xl bg-white/5 border border-white/10 p-4 hover:bg-white/10 hover:border-white/20 transition-all hover:-translate-y-1">
                <div class="w-10 h-10 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center group-hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="1.5" d="M3 7l9-4 9 4-9 4-9-4zM3 10l9 4 9-4M3 13l9 4 9-4"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-white/80 group-hover:text-white">Produits</span>
            </a>
            <a href="{{ route('admin.devis.create') }}" class="group flex flex-col items-center justify-center gap-2 rounded-xl bg-white/5 border border-white/10 p-4 hover:bg-white/10 hover:border-white/20 transition-all hover:-translate-y-1">
                <div class="w-10 h-10 rounded-full bg-orange-500/20 text-orange-400 flex items-center justify-center group-hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-white/80 group-hover:text-white">Nouveau Devis</span>
            </a>
            <a href="{{ route('admin.clients') }}" class="group flex flex-col items-center justify-center gap-2 rounded-xl bg-white/5 border border-white/10 p-4 hover:bg-white/10 hover:border-white/20 transition-all hover:-translate-y-1">
                <div class="w-10 h-10 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center group-hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="1.5" d="M16 11c1.657 0 3-1.567 3-3.5S17.657 4 16 4s-3 1.567-3 3.5 1.343 3.5 3 3.5zM8 11c1.657 0 3-1.567 3-3.5S9.657 4 8 4 5 5.567 5 7.5 6.343 11 8 11zm8 2c-2.21 0-4.2 1.343-5 3.25C10.2 14.343 8.21 13 6 13c-2.761 0-5 2.239-5 5v2h22v-2c0-2.761-2.239-5-5-5z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-white/80 group-hover:text-white">Clients</span>
            </a>
            <a href="{{ route('admin.devis.export', ['type' => 'all']) }}" class="group flex flex-col items-center justify-center gap-2 rounded-xl bg-white/5 border border-white/10 p-4 hover:bg-white/10 hover:border-white/20 transition-all hover:-translate-y-1">
                <div class="w-10 h-10 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center group-hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-white/80 group-hover:text-white">Rapports</span>
            </a>
            <a href="{{ route('admin.settings') }}" class="group flex flex-col items-center justify-center gap-2 rounded-xl bg-white/5 border border-white/10 p-4 hover:bg-white/10 hover:border-white/20 transition-all hover:-translate-y-1">
                <div class="w-10 h-10 rounded-full bg-slate-500/20 text-slate-400 flex items-center justify-center group-hover:scale-110 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-white/80 group-hover:text-white">Paramètres</span>
            </a>
            <a href="{{ route('admin.contact-messages') }}" class="group flex flex-col items-center justify-center gap-2 rounded-xl bg-white/5 border border-white/10 p-4 hover:bg-white/10 hover:border-white/20 transition-all hover:-translate-y-1">
                <div class="w-10 h-10 rounded-full bg-pink-500/20 text-pink-400 flex items-center justify-center group-hover:scale-110 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-white/80 group-hover:text-white">Support</span>
            </a>
        </div>
    </div>

    @if((session('admin_user')['role'] ?? 'user') === 'admin')
    <div class="rounded-2xl bg-[#122241] border border-white/10 p-6 shadow-lg shadow-black/20">
        <h3 class="font-semibold text-lg text-white mb-4">Activités récentes des utilisateurs</h3>
        @php $grouped = collect($recentActivities ?? [])->groupBy(fn($a) => \Carbon\Carbon::parse($a->created_at)->format('Y-m-d')); @endphp
        <div class="max-h-80 overflow-y-auto pr-2 space-y-4">
            @forelse($grouped->sortKeysDesc() as $date => $items)
                @php 
                    $d = \Carbon\Carbon::parse($date);
                    $label = $d->isToday() ? "Aujourd'hui" : ($d->isYesterday() ? 'Hier' : $d->format('d/m/Y'));
                @endphp
                <div>
                    <div class="text-xs text-white/50 mb-2">{{ $label }}</div>
                    <div class="space-y-2">
                        @foreach($items as $activity)
                            <div class="flex items-center gap-3 p-2 rounded-lg bg-white/5 border border-white/5">
                                <div class="w-8 h-8 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-xs border border-purple-500/30">
                                    {{ substr($activity->user->name ?? 'U', 0, 2) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm text-white/80 truncate">{{ $activity->user->name ?? 'Utilisateur inconnu' }}</div>
                                    <div class="text-xs text-white/60 truncate">{{ $activity->description }}</div>
                                </div>
                                <div class="text-right text-xs text-white/50 shrink-0">{{ $activity->created_at->format('H:i') }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-white/40">Aucune activité récente</div>
            @endforelse
        </div>
    </div>
    @endif
</div>







<!-- Chart.js + Animations -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Count-up animation
    document.querySelectorAll('[data-count-to]').forEach(el => {
        const target = Number(el.getAttribute('data-count-to') || 0);
        const duration = 1000;
        const start = performance.now();
        function step(now) {
            const p = Math.min((now - start) / duration, 1);
            const val = Math.floor(target * p);
            el.textContent = el.getAttribute('data-count-to') === '3100000'
                ? (val.toLocaleString('fr-DZ') + ' DA')
                : val.toLocaleString('fr-DZ');
            if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    });

    // Initial animations for top KPIs and status overview
    document.querySelectorAll('[data-stat]').forEach(el => {
        const target = parseInt((el.textContent || '0').replace(/[^\d]/g, '')) || 0;
        el.textContent = '0';
        animateNumber(el, target);
    });

    // Animate width for status bars (Envoyés/Annulés)
    document.querySelectorAll('.rounded-2xl .mt-3 .h-full[style]').forEach(el => {
        const targetWidth = el.style.width || '0%';
        el.style.transition = 'width 800ms ease-out';
        el.style.width = '0%';
        requestAnimationFrame(() => { el.style.width = targetWidth; });
    });

    // Real-time devis stats update
    function updateDevisStats() {
        fetch('{{ route("admin.devis.stats") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.stats) {
                // Update total devis
                const totalEl = document.querySelector('[data-stat="total"]');
                if (totalEl) animateNumber(totalEl, data.stats.totalDevis || 0);
                
                // Update status counts
                const statuses = ['nouveau', 'en_cours', 'envoye', 'confirme', 'annule'];
                statuses.forEach(status => {
                    const el = document.querySelector(`[data-stat="${status}"]`);
                    if (el) animateNumber(el, data.stats.status?.[status] || 0);
                });
                
                // Update standard and specific counts
                const standardEl = document.querySelector('[data-stat="standard"]');
                if (standardEl) animateNumber(standardEl, data.stats.standard || 0);
                
                const specificEl = document.querySelector('[data-stat="specific"]');
                if (specificEl) animateNumber(specificEl, data.stats.specific || 0);
            }
        })
        .catch(() => {}); // Silent fail
    }

    // Smooth number animation function
    function animateNumber(element, target) {
        const start = parseInt(element.textContent.replace(/[^\d]/g, '')) || 0;
        const duration = 800;
        const startTime = performance.now();
        
        function step(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const value = Math.round(start + (target - start) * easeOut);
            
            element.textContent = value.toLocaleString('fr-DZ');
            
            if (progress < 1) {
                requestAnimationFrame(step);
            }
        }
        requestAnimationFrame(step);
    }

    // Auto-refresh devis stats every 5 seconds
    setInterval(updateDevisStats, 5000);
    
    // Initial load
    setTimeout(updateDevisStats, 1000);





    // Categories Pie
    const categoryStats = @json($categoryStats ?? []);
    const catLabels = Object.keys(categoryStats);
    const catData = Object.values(categoryStats);
    
    // Generate colors
    const colors = ['#f59e0b', '#10b981', '#3b82f6', '#ef4444', '#8b5cf6', '#ec4899', '#6366f1'];
    const bgColors = catLabels.map((_, i) => colors[i % colors.length]);

    const ctx = document.getElementById('categoriesChart');
    const totalCat = (catData.length ? catData : [1]).reduce((a,b)=>a+b,0);

    const centerLabelPlugin = {
        id: 'centerLabel',
        afterDraw(chart) {
            const {ctx, chartArea} = chart;
            const x = (chartArea.left + chartArea.right) / 2;
            const y = (chartArea.top + chartArea.bottom) / 2;
            ctx.save();
            ctx.fillStyle = '#fff';
            ctx.textAlign = 'center';
            ctx.font = '600 16px system-ui';
            ctx.fillText('Total', x, y - 6);
            ctx.font = '700 18px system-ui';
            ctx.fillText(totalCat.toLocaleString('fr-DZ'), x, y + 16);
            ctx.restore();
        }
    };

    const chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: catLabels.length ? catLabels : ['Aucune donnée'],
            datasets: [{
                data: catData.length ? catData : [1],
                backgroundColor: catData.length ? bgColors : ['#334155'],
                borderWidth: 1,
                hoverOffset: 5
            }]
        },
        options: {
            cutout: '70%',
            animation: { animateRotate: true, animateScale: true, duration: 800 },
            
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(17,24,39,0.9)',
                    borderColor: 'rgba(255,255,255,0.15)',
                    borderWidth: 1,
                    padding: 10,
                    callbacks: {
                        label: (item) => {
                            const v = item.parsed; const p = totalCat ? Math.round(v/totalCat*100) : 0;
                            return `${item.label}: ${v} (${p}%)`;
                        }
                    }
                }
            }
        },
        plugins: [centerLabelPlugin]
    });

    const legend = document.createElement('div');
    legend.className = 'mt-4 grid grid-cols-2 gap-2';
    const labels = catLabels.length ? catLabels : ['Aucune donnée'];
    const data = catData.length ? catData : [1];
    labels.forEach((label, i) => {
        const p = totalCat ? Math.round(data[i]/totalCat*100) : 0;
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2 text-xs text-white/80';
        const dot = document.createElement('span');
        dot.className = 'inline-block w-2.5 h-2.5 rounded-full';
        dot.style.backgroundColor = (bgColors[i % bgColors.length] || '#64748b');
        const text = document.createElement('span');
        text.textContent = `${label} — ${data[i]} (${p}%)`;
        row.append(dot, text);
        legend.appendChild(row);
    });
    ctx.parentElement.appendChild(legend);

    document.addEventListener('DOMContentLoaded', function() {
        const loading = document.getElementById('loadingState');
        const content = document.getElementById('dashboardContent');

        // Simuler un chargement au début (ou à utiliser lors d'appels AJAX)
        function toggleLoading(show) {
            if (show) {
                loading.classList.remove('hidden');
                content.classList.add('hidden');
            } else {
                loading.classList.add('hidden');
                content.classList.remove('hidden');
            }
        }

        // Exemple d'utilisation : masquer après le chargement initial
        // toggleLoading(true); 
        // setTimeout(() => toggleLoading(false), 500);
    });
</script>
@endsection