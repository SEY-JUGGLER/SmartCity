<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex items-center justify-center rounded-xl bg-emerald-500/10 flex-shrink-0" style="width:36px;height:36px">
                        <x-heroicon-m-user-group style="width:18px;height:18px" class="text-emerald-500" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Agents — Statut & Performances</p>
                        <p class="text-xs text-gray-400 leading-tight">Vue globale en temps réel</p>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex-shrink-0">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Temps réel</span>
                </div>
            </div>
        </x-slot>

        @php
            $stats = $this->getAgentStats();
            $top   = $this->getTopAgents();
            $total = $stats['total'];

            $kpiCards = [
                [
                    'label'   => 'Disponibles',
                    'value'   => $stats['disponibles'],
                    'sub'     => 'Prêts à intervenir',
                    'icon'    => 'heroicon-m-check-circle',
                    'wrapCls' => 'border-emerald-100 dark:border-emerald-900/30 bg-white dark:bg-gray-900',
                    'numCls'  => 'text-emerald-500',
                    'iconCls' => 'bg-emerald-500/10',
                    'iconCol' => 'text-emerald-500',
                    'bar'     => 'bg-emerald-400/40',
                ],
                [
                    'label'   => 'Occupés',
                    'value'   => $stats['occupes'],
                    'sub'     => 'En intervention',
                    'icon'    => 'heroicon-m-briefcase',
                    'wrapCls' => 'border-amber-100 dark:border-amber-900/30 bg-white dark:bg-gray-900',
                    'numCls'  => 'text-amber-500',
                    'iconCls' => 'bg-amber-500/10',
                    'iconCol' => 'text-amber-500',
                    'bar'     => 'bg-amber-400/40',
                ],
                [
                    'label'   => 'Absents',
                    'value'   => $stats['absents'],
                    'sub'     => 'Non pointés ce jour',
                    'icon'    => 'heroicon-m-x-circle',
                    'wrapCls' => 'border-red-100 dark:border-red-900/30 bg-white dark:bg-gray-900',
                    'numCls'  => 'text-red-500',
                    'iconCls' => 'bg-red-500/10',
                    'iconCol' => 'text-red-500',
                    'bar'     => 'bg-red-400/40',
                ],
                [
                    'label'   => 'Inactifs',
                    'value'   => $stats['inactifs'],
                    'sub'     => 'Comptes désactivés',
                    'icon'    => 'heroicon-m-moon',
                    'wrapCls' => 'border-slate-200 dark:border-slate-700/40 bg-white dark:bg-gray-900',
                    'numCls'  => 'text-slate-500',
                    'iconCls' => 'bg-slate-500/10',
                    'iconCol' => 'text-slate-500',
                    'bar'     => 'bg-slate-400/40',
                ],
            ];
        @endphp

        {{-- KPI Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
            @foreach($kpiCards as $card)
            <div class="relative overflow-hidden rounded-2xl border {{ $card['wrapCls'] }} p-4 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ $card['label'] }}</p>
                        <p class="text-3xl font-black {{ $card['numCls'] }} leading-none">{{ $card['value'] }}</p>
                        <p class="text-[10px] text-gray-400 mt-1.5 leading-tight">{{ $card['sub'] }}</p>
                    </div>
                    <div class="flex items-center justify-center rounded-xl {{ $card['iconCls'] }} flex-shrink-0" style="width:36px;height:36px">
                        <x-dynamic-component :component="$card['icon']" style="width:16px;height:16px" class="{{ $card['iconCol'] }}" />
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-0.5 {{ $card['bar'] }}"></div>
            </div>
            @endforeach
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            {{-- Donut --}}
            <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Répartition des agents</h3>
                        <p class="text-xs text-gray-400 mt-0.5">État actuel des équipes terrain</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gray-100 dark:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-300">
                        {{ $total }} agents
                    </span>
                </div>

                <div wire:ignore class="relative mx-auto" style="height:300px;max-width:300px;">
                    <canvas id="chart-agents-donut-{{ $this->getId() }}"></canvas>
                </div>

                <div class="grid grid-cols-2 gap-2 mt-4">
                    @foreach([
                        ['Disponibles', $stats['disponibles'], 'bg-emerald-500', 'text-emerald-600 dark:text-emerald-400'],
                        ['Occupés',     $stats['occupes'],     'bg-amber-500',   'text-amber-600 dark:text-amber-400'],
                        ['Absents',     $stats['absents'],     'bg-red-500',     'text-red-600 dark:text-red-400'],
                        ['Inactifs',    $stats['inactifs'],    'bg-slate-400',   'text-slate-500 dark:text-slate-400'],
                    ] as [$lbl, $val, $dot, $valCls])
                    <div class="flex items-center justify-between rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/60 px-3 py-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full {{ $dot }} flex-shrink-0"></span>
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-300 truncate">{{ $lbl }}</span>
                        </div>
                        <span class="text-sm font-black {{ $valCls }} ml-2 flex-shrink-0">{{ $val }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Top Agents --}}
            <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Top agents du mois</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Classés par missions effectuées</p>
                    </div>
                    <div class="flex items-center justify-center rounded-xl bg-amber-500/10 flex-shrink-0" style="width:36px;height:36px">
                        <x-heroicon-m-trophy style="width:16px;height:16px" class="text-amber-500" />
                    </div>
                </div>

                @if(count($top))
                <div wire:ignore class="relative" style="height:300px;">
                    <canvas id="chart-agents-bar-{{ $this->getId() }}"></canvas>
                </div>

                <div class="space-y-2 mt-4">
                    @foreach($top as $index => $agent)
                    @php
                        $rankColors = ['#3b82f6','#10b981','#f59e0b','#8b5cf6','#06b6d4'];
                        $rankColor  = $rankColors[$index] ?? '#6b7280';
                    @endphp
                    <div class="flex items-center gap-3 p-2.5 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-800/50 hover:bg-primary-50 dark:hover:bg-primary-500/10 transition-colors duration-150">
                        <div class="flex items-center justify-center rounded-lg flex-shrink-0 text-white text-xs font-black"
                             style="width:26px;height:26px;background:{{ $rankColor }}">
                            {{ $index + 1 }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $agent['nom'] }}</p>
                            <p class="text-[10px] text-gray-400">Agent terrain</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-black text-primary-500 leading-none">{{ $agent['missions'] }}</p>
                            <p class="text-[10px] text-gray-400">missions</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex flex-col items-center justify-center gap-3 py-16">
                    <div class="flex items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-800" style="width:56px;height:56px">
                        <x-heroicon-o-inbox style="width:24px;height:24px" class="text-gray-300 dark:text-gray-600" />
                    </div>
                    <p class="text-sm text-gray-400 font-medium">Aucune mission ce mois</p>
                </div>
                @endif
            </div>

        </div>

        <div wire:ignore>
            <script>
                (function () {
                    var _retries = 0;
                    var _widgetId = '{{ $this->getId() }}';

                    function initAgentsCharts() {
                        if (typeof Chart === 'undefined') {
                            if (_retries++ < 80) { setTimeout(initAgentsCharts, 100); return; }
                            return;
                        }

                        var dark   = document.documentElement.classList.contains('dark');
                        var tick   = dark ? '#94a3b8' : '#64748b';
                        var grid   = dark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.04)';
                        var cardBg = dark ? '#111827' : '#ffffff';

                        var tip = {
                            backgroundColor: cardBg,
                            titleColor: dark ? '#f9fafb' : '#0f172a',
                            bodyColor: dark ? '#cbd5e1' : '#64748b',
                            borderColor: dark ? '#1e293b' : '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: true,
                        };

                        var donutCanvas = document.getElementById('chart-agents-donut-' + _widgetId);
                        if (donutCanvas && !donutCanvas._chartInstance) {
                            donutCanvas._chartInstance = new Chart(donutCanvas, {
                                type: 'doughnut',
                                data: {
                                    labels: ['Disponibles', 'Occupés', 'Absents', 'Inactifs'],
                                    datasets: [{
                                        data: [{{ $stats['disponibles'] }}, {{ $stats['occupes'] }}, {{ $stats['absents'] }}, {{ $stats['inactifs'] }}],
                                        backgroundColor: [
                                            'rgba(16,185,129,0.9)',
                                            'rgba(245,158,11,0.9)',
                                            'rgba(239,68,68,0.9)',
                                            'rgba(148,163,184,0.9)'
                                        ],
                                        borderWidth: 3,
                                        borderColor: cardBg,
                                        hoverOffset: 10,
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    cutout: '75%',
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: tip
                                    },
                                    animation: { animateRotate: true, duration: 1000, easing: 'easeOutQuart' },
                                },
                                plugins: [{
                                    id: 'centerText',
                                    beforeDraw(chart) {
                                        var ctx = chart.ctx;
                                        ctx.save();
                                        var cx = chart.width / 2, cy = chart.height / 2;
                                        ctx.textAlign = 'center';
                                        ctx.textBaseline = 'middle';
                                        ctx.font = '900 42px Inter, sans-serif';
                                        ctx.fillStyle = dark ? '#f9fafb' : '#0f172a';
                                        ctx.fillText('{{ $total }}', cx, cy - 12);
                                        ctx.font = '500 12px Inter, sans-serif';
                                        ctx.fillStyle = dark ? '#94a3b8' : '#64748b';
                                        ctx.fillText('agents actifs', cx, cy + 18);
                                        ctx.restore();
                                    }
                                }]
                            });
                        }

                        var barCanvas = document.getElementById('chart-agents-bar-' + _widgetId);
                        var top = @json($top);
                        if (barCanvas && !barCanvas._chartInstance && top.length) {
                            barCanvas._chartInstance = new Chart(barCanvas, {
                                type: 'bar',
                                data: {
                                    labels: top.map(function(a) { return a.nom.split(' ')[0]; }),
                                    datasets: [{
                                        label: 'Missions',
                                        data: top.map(function(a) { return a.missions; }),
                                        backgroundColor: [
                                            'rgba(59,130,246,0.85)',
                                            'rgba(16,185,129,0.85)',
                                            'rgba(245,158,11,0.85)',
                                            'rgba(139,92,246,0.85)',
                                            'rgba(6,182,212,0.85)'
                                        ],
                                        borderRadius: 10,
                                        borderSkipped: false,
                                        barThickness: 22,
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    indexAxis: 'y',
                                    plugins: { legend: { display: false }, tooltip: tip },
                                    scales: {
                                        x: {
                                            beginAtZero: true,
                                            grid: { color: grid, drawBorder: false },
                                            ticks: { color: tick, precision: 0, font: { size: 11 } },
                                            border: { display: false }
                                        },
                                        y: {
                                            grid: { display: false },
                                            ticks: { color: tick, font: { size: 11, weight: '600' } },
                                            border: { display: false }
                                        },
                                    },
                                    animation: { duration: 900, easing: 'easeOutQuart' },
                                },
                            });
                        }
                    }

                    document.addEventListener('DOMContentLoaded', initAgentsCharts);
                    document.addEventListener('livewire:navigated', function () { _retries = 0; initAgentsCharts(); });
                })();
            </script>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
