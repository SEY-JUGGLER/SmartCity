<x-filament-panels::page>
    {{-- Status bar compacte --}}
    <div class="flex flex-wrap items-center gap-3 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
        <div class="flex items-center gap-2 text-sm">
            <span class="text-gray-500">Statut :</span>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $disponible ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $disponible ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                {{ $disponible ? 'Disponible' : 'Indisponible' }}
            </span>
        </div>
        <div class="flex items-center gap-2 text-sm">
            <span class="text-gray-500">Pointage :</span>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $pointe ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                {{ $pointe ? 'Pointé' : 'Non pointé' }}
            </span>
        </div>
        <div class="flex items-center gap-2 ml-auto">
            @if(!$pointe)
                <button wire:click="pointer" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-medium rounded-lg transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Pointer
                </button>
            @endif
            <button wire:click="toggleDisponibilite" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg transition-all border {{ $disponible ? 'bg-red-50 text-red-700 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-300 border-red-200 dark:border-red-800' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $disponible ? 'bg-red-500' : 'bg-emerald-500' }}"></span>
                {{ $disponible ? 'Indisponible' : 'Disponible' }}
            </button>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3.5 py-3 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 leading-tight">En cours</p>
                <p class="text-lg font-bold text-amber-500">{{ $this->stats['enCours'] }}</p>
            </div>
        </div>
        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3.5 py-3 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 leading-tight">Terminées</p>
                <p class="text-lg font-bold text-emerald-500">{{ $this->stats['termines'] }}</p>
            </div>
        </div>
        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3.5 py-3 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 leading-tight">En attente</p>
                <p class="text-lg font-bold text-blue-500">{{ $this->stats['attente'] }}</p>
            </div>
        </div>
        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3.5 py-3 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gray-50 dark:bg-gray-800 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 leading-tight">Total</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $this->stats['total'] }}</p>
            </div>
        </div>
        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3.5 py-3 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-900/20 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 leading-tight">Support</p>
                <p class="text-lg font-bold text-rose-500">{{ $this->stats['supportEnAttente'] }}</p>
            </div>
        </div>
        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3.5 py-3 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-cyan-50 dark:bg-cyan-900/20 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 leading-tight">Équipements</p>
                <p class="text-lg font-bold text-cyan-500">{{ $this->stats['materiels'] }}</p>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Interventions du jour --}}
        <div class="lg:col-span-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Interventions du jour</h3>
                <span class="text-xs text-gray-500">{{ count($this->interventionsToday) }} mission(s)</span>
            </div>
            @if(count($this->interventionsToday) > 0)
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($this->interventionsToday as $intervention)
                        <div class="px-4 py-3 flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">
                            <div class="w-7 h-7 rounded flex items-center justify-center shrink-0 {{ $intervention['statut'] === 'terminer' ? 'bg-emerald-50 text-emerald-500' : ($intervention['statut'] === 'enCours' ? 'bg-amber-50 text-amber-500' : 'bg-gray-50 text-gray-400') }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $intervention['description'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ $intervention['categorie']['nom'] ?? 'Non catégorisé' }}
                                    · {{ $intervention['zone']['nomZone'] ?? 'N/A' }}
                                </p>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $intervention['statut'] === 'terminer' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : ($intervention['statut'] === 'enCours' ? 'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400') }}">
                                {{ $intervention['statut'] === 'terminer' ? 'Terminé' : ($intervention['statut'] === 'enCours' ? 'En cours' : 'Attente') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center">
                    <svg class="w-8 h-8 mx-auto text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Aucune intervention aujourd'hui</p>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            {{-- Notifications --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</h3>
                    <a href="{{ url('/agent/notifications') }}" class="text-xs text-primary-600 hover:underline">Voir tout</a>
                </div>
                @if(count($this->recentNotifications) > 0)
                    <div class="space-y-2">
                        @foreach($this->recentNotifications as $notif)
                            <div class="flex items-start gap-2 {{ $notif['read_at'] ? '' : 'border-l-2 border-l-primary-500 pl-2' }}">
                                <p class="text-xs text-gray-900 dark:text-white font-medium truncate">{{ $notif['data']['title'] ?? 'Notification' }}</p>
                                @if(isset($notif['data']['body']))
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $notif['data']['body'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center py-3">Aucune notification</p>
                @endif
            </div>

            {{-- Évolution --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Évolution (6 mois)</h3>
                <div style="height:130px">
                    <canvas id="chart-agent-evolution" data-labels='@json($this->chartData["labels"])' data-terminees='@json($this->chartData["terminees"])' data-attribuees='@json($this->chartData["attribuees"])'></canvas>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ url('/agent/mes-missions') }}" class="flex items-center gap-2 rounded-lg border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/20 px-3 py-2.5 hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-colors">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span class="text-xs font-medium text-amber-700 dark:text-amber-300">Missions</span>
                </a>
                <a href="{{ url('/agent/navigation-carte') }}" class="flex items-center gap-2 rounded-lg border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/20 px-3 py-2.5 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    <span class="text-xs font-medium text-blue-700 dark:text-blue-300">Carte</span>
                </a>
                <a href="{{ url('/agent/support-requests') }}" class="flex items-center gap-2 rounded-lg border border-rose-200 dark:border-rose-800 bg-rose-50 dark:bg-rose-900/20 px-3 py-2.5 hover:bg-rose-100 dark:hover:bg-rose-900/40 transition-colors">
                    <svg class="w-4 h-4 text-rose-600 dark:text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs font-medium text-rose-700 dark:text-rose-300">Support</span>
                </a>
                <a href="{{ url('/agent/mes-materiels') }}" class="flex items-center gap-2 rounded-lg border border-cyan-200 dark:border-cyan-800 bg-cyan-50 dark:bg-cyan-900/20 px-3 py-2.5 hover:bg-cyan-100 dark:hover:bg-cyan-900/40 transition-colors">
                    <svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                    <span class="text-xs font-medium text-cyan-700 dark:text-cyan-300">Matériels</span>
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('chart-agent-evolution');
        if (!canvas) return;

        const labels = JSON.parse(canvas.dataset.labels);
        const terminees = JSON.parse(canvas.dataset.terminees);
        const attribuees = JSON.parse(canvas.dataset.attribuees);

        if (typeof Chart === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js';
            script.onload = () => renderChart(labels, terminees, attribuees);
            document.head.appendChild(script);
        } else {
            renderChart(labels, terminees, attribuees);
        }

        function renderChart(labels, terminees, attribuees) {
            const dark = document.documentElement.classList.contains('dark');
            const ctx = canvas.getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Terminées', data: terminees, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', tension: 0.3, fill: true, pointRadius: 2, borderWidth: 1.5 },
                        { label: 'Attribuées', data: attribuees, borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.1)', tension: 0.3, fill: true, pointRadius: 2, borderWidth: 1.5 },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'bottom', labels: { color: dark ? '#94a3b8' : '#64748b', font: { size: 9 }, boxWidth: 10, padding: 8 } },
                        tooltip: { backgroundColor: dark ? '#1e293b' : '#fff', titleColor: dark ? '#e2e8f0' : '#1e293b', bodyColor: dark ? '#94a3b8' : '#64748b', borderColor: dark ? '#334155' : '#e2e8f0', borderWidth: 1, padding: 8, cornerRadius: 6 },
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: dark ? '#64748b' : '#94a3b8', font: { size: 9 } } },
                        y: { grid: { color: dark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)' }, ticks: { color: dark ? '#64748b' : '#94a3b8', font: { size: 9 }, precision: 0 }, beginAtZero: true },
                    },
                    animation: { duration: 500 },
                },
            });
        }
    });
    </script>
    @endpush
</x-filament-panels::page>
