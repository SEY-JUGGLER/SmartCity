<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full gap-3">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="flex items-center justify-center rounded-lg bg-emerald-500/10 flex-shrink-0" style="width:28px;height:28px">
                        <x-heroicon-m-user-group style="width:14px;height:14px" class="text-emerald-500" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white leading-tight truncate">Agents — Statut & Performances</p>
                        <p class="text-[10px] text-gray-400 leading-tight">Vue globale en temps réel</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex-shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[10px] font-medium text-emerald-600 dark:text-emerald-400">Temps réel</span>
                </div>
            </div>
        </x-slot>

        @php
            $stats = $this->getAgentStats();
            $top   = $this->getTopAgents();
            $total = $stats['total'];
        @endphp

        {{-- KPI Cards --}}
        <div class="grid grid-cols-4 gap-2 mb-4">

            @foreach([
                ['Disponibles', $stats['disponibles'], 'text-emerald-500', 'bg-emerald-500/10', 'heroicon-m-check-circle'],
                ['Occupés',     $stats['occupes'],     'text-amber-500',   'bg-amber-500/10',   'heroicon-m-briefcase'],
                ['Absents',     $stats['absents'],     'text-red-500',     'bg-red-500/10',     'heroicon-m-x-circle'],
                ['Inactifs',    $stats['inactifs'],    'text-slate-400',   'bg-slate-500/10',   'heroicon-m-moon'],
            ] as [$label, $value, $textColor, $iconBg, $icon])
            <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-2.5">
                <div class="flex items-center justify-between gap-1">
                    <div class="min-w-0">
                        <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider truncate">{{ $label }}</p>
                        <p class="text-xl font-black {{ $textColor }} leading-none mt-0.5">{{ $value }}</p>
                    </div>
                    <div class="flex items-center justify-center rounded-lg {{ $iconBg }} flex-shrink-0" style="width:26px;height:26px">
                        <x-dynamic-component :component="$icon" style="width:12px;height:12px" class="{{ $textColor }}" />
                    </div>
                </div>
            </div>
            @endforeach

        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 2xl:grid-cols-2 gap-4">

            {{-- Donut --}}
            <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-xs font-bold text-gray-900 dark:text-white">Répartition des agents</h3>
                        <p class="text-[10px] text-gray-400">État actuel des équipes terrain</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-800 text-[10px] font-semibold text-gray-500">{{ $total }} agents</span>
                </div>

                <div wire:ignore class="relative" style="height:220px;">
                    <canvas id="chart-agents-donut"></canvas>
                </div>

                <div class="grid grid-cols-2 gap-1.5 mt-3">
                    @foreach([
                        ['Disponibles', $stats['disponibles'], 'bg-emerald-500'],
                        ['Occupés',     $stats['occupes'],     'bg-amber-500'],
                        ['Absents',     $stats['absents'],     'bg-red-500'],
                        ['Inactifs',    $stats['inactifs'],    'bg-slate-400'],
                    ] as [$label, $value, $color])
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 dark:bg-gray-800/60 px-2.5 py-1.5">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <span class="w-2 h-2 rounded-full {{ $color }} flex-shrink-0"></span>
                            <span class="text-[10px] font-medium text-gray-600 dark:text-gray-300 truncate">{{ $label }}</span>
                        </div>
                        <span class="text-[10px] font-bold text-gray-900 dark:text-white ml-2 flex-shrink-0">{{ $value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Top Agents --}}
            <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-xs font-bold text-gray-900 dark:text-white">Top agents du mois</h3>
                        <p class="text-[10px] text-gray-400">Classé par missions effectuées</p>
                    </div>
                    <div class="flex items-center justify-center rounded-lg bg-primary-500/10 flex-shrink-0" style="width:26px;height:26px">
                        <x-heroicon-m-trophy style="width:12px;height:12px" class="text-primary-500" />
                    </div>
                </div>

                <div wire:ignore class="relative" style="height:220px;">
                    <canvas id="chart-agents-bar"></canvas>
                </div>

                @if(count($top))
                <div class="space-y-1.5 mt-3">
                    @foreach($top as $index => $agent)
                    <div class="flex items-center justify-between p-2 rounded-lg border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50 hover:bg-primary-50 dark:hover:bg-primary-500/10 transition-colors duration-150">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="flex items-center justify-center rounded-lg bg-gradient-to-br from-primary-500 to-primary-600 text-white text-[10px] font-black flex-shrink-0" style="width:22px;height:22px">
                                {{ $index + 1 }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $agent['nom'] }}</p>
                                <p class="text-[9px] text-gray-400">Agent terrain</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-2">
                            <p class="text-sm font-black text-primary-500 leading-none">{{ $agent['missions'] }}</p>
                            <p class="text-[9px] text-gray-400">missions</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex flex-col items-center gap-1.5 py-6">
                    <x-heroicon-o-inbox style="width:20px;height:20px" class="text-gray-300 dark:text-gray-600" />
                    <p class="text-[10px] text-gray-400">Aucune mission ce mois</p>
                </div>
                @endif
            </div>

        </div>

        <div wire:ignore>
            <script>
                (function () {
                    var _retries = 0;

                    function initAgentsCharts() {
                        if (typeof Chart === 'undefined') {
                            if (_retries++ < 80) { setTimeout(initAgentsCharts, 100); return; }
                            return;
                        }

                        var dark   = document.documentElement.classList.contains('dark');
                        var tick   = dark ? '#94a3b8' : '#64748b';
                        var grid   = dark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
                        var cardBg = dark ? '#111827' : '#ffffff';

                        var tip = {
                            backgroundColor: cardBg,
                            titleColor: dark ? '#fff' : '#0f172a',
                            bodyColor: dark ? '#cbd5e1' : '#64748b',
                            borderColor: dark ? '#1e293b' : '#e2e8f0',
                            borderWidth: 1,
                            padding: 10,
                            cornerRadius: 12,
                            displayColors: true,
                        };

                        var donutCanvas = document.getElementById('chart-agents-donut');
                        if (donutCanvas && !donutCanvas._chartInstance) {
                            donutCanvas._chartInstance = new Chart(donutCanvas, {
                                type: 'doughnut',
                                data: {
                                    labels: ['Disponibles', 'Occupés', 'Absents', 'Inactifs'],
                                    datasets: [{
                                        data: [{{ $stats['disponibles'] }}, {{ $stats['occupes'] }}, {{ $stats['absents'] }}, {{ $stats['inactifs'] }}],
                                        backgroundColor: ['rgba(16,185,129,0.95)', 'rgba(245,158,11,0.95)', 'rgba(239,68,68,0.95)', 'rgba(148,163,184,0.95)'],
                                        borderWidth: 0,
                                        hoverOffset: 8,
                                    }]
                                },
                                options: {
                                    responsive: true, maintainAspectRatio: false, cutout: '80%',
                                    plugins: { legend: { display: false }, tooltip: tip },
                                    animation: { animateRotate: true, duration: 900, easing: 'easeOutQuart' },
                                },
                                plugins: [{
                                    id: 'centerText',
                                    beforeDraw(chart) {
                                        var ctx = chart.ctx;
                                        ctx.save();
                                        var cx = chart.width / 2, cy = chart.height / 2;
                                        ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                                        ctx.font = '800 34px Inter, sans-serif';
                                        ctx.fillStyle = dark ? '#fff' : '#0f172a';
                                        ctx.fillText('{{ $total }}', cx, cy - 9);
                                        ctx.font = '500 11px Inter, sans-serif';
                                        ctx.fillStyle = dark ? '#94a3b8' : '#64748b';
                                        ctx.fillText('agents actifs', cx, cy + 16);
                                        ctx.restore();
                                    }
                                }]
                            });
                        }

                        var barCanvas = document.getElementById('chart-agents-bar');
                        var top = @json($top);
                        if (barCanvas && !barCanvas._chartInstance && top.length) {
                            barCanvas._chartInstance = new Chart(barCanvas, {
                                type: 'bar',
                                data: {
                                    labels: top.map(function(a) { return a.nom; }),
                                    datasets: [{
                                        label: 'Missions',
                                        data: top.map(function(a) { return a.missions; }),
                                        backgroundColor: ['rgba(59,130,246,0.9)', 'rgba(16,185,129,0.9)', 'rgba(245,158,11,0.9)', 'rgba(139,92,246,0.9)', 'rgba(6,182,212,0.9)'],
                                        borderRadius: 8, borderSkipped: false, barThickness: 20,
                                    }]
                                },
                                options: {
                                    responsive: true, maintainAspectRatio: false, indexAxis: 'y',
                                    plugins: { legend: { display: false }, tooltip: tip },
                                    scales: {
                                        x: { beginAtZero: true, grid: { color: grid, drawBorder: false }, ticks: { color: tick, precision: 0, font: { size: 10 } }, border: { display: false } },
                                        y: { grid: { display: false }, ticks: { color: tick, font: { size: 11, weight: '600' } }, border: { display: false } },
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
