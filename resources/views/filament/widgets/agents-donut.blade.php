<x-filament-widgets::widget>
    <x-filament::section
        class="overflow-hidden rounded-3xl border border-gray-200/60 dark:border-gray-800 bg-gradient-to-br from-white via-white to-gray-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-950 shadow-xl"
    >
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-3">
                    <div style="width:32px;height:32px" class="rounded-2xl bg-emerald-500/10 flex items-center justify-center">
                        <x-heroicon-m-user-group style="width:16px;height:16px" class="text-emerald-500"/>
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                            Agents — Statut & Performances
                        </h2>

                        <p class="text-sm text-gray-500">
                            Vue globale en temps réel des agents terrain
                        </p>
                    </div>
                </div>

                <div class="hidden md:flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>

                    <span class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                        Temps réel
                    </span>
                </div>
            </div>
        </x-slot>

        @php
            $stats = $this->getAgentStats();
            $top   = $this->getTopAgents();
            $total = $stats['total'];
        @endphp

        {{-- KPI Cards --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-2 mb-4">

            <div class="group rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-2.5 transition-all duration-150 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Disponibles</p>
                        <p class="text-xl font-extrabold text-emerald-500">{{ $stats['disponibles'] }}</p>
                    </div>
                    <div style="width:28px;height:28px" class="rounded-lg bg-emerald-500/10 flex items-center justify-center">
                        <x-heroicon-m-check-circle style="width:13px;height:13px" class="text-emerald-500" />
                    </div>
                </div>
            </div>

            <div class="group rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-2.5 transition-all duration-150 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Occupés</p>
                        <p class="text-xl font-extrabold text-amber-500">{{ $stats['occupes'] }}</p>
                    </div>
                    <div style="width:28px;height:28px" class="rounded-lg bg-amber-500/10 flex items-center justify-center">
                        <x-heroicon-m-briefcase style="width:13px;height:13px" class="text-amber-500" />
                    </div>
                </div>
            </div>

            <div class="group rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-2.5 transition-all duration-150 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Absents</p>
                        <p class="text-xl font-extrabold text-red-500">{{ $stats['absents'] }}</p>
                    </div>
                    <div style="width:28px;height:28px" class="rounded-lg bg-red-500/10 flex items-center justify-center">
                        <x-heroicon-m-x-circle style="width:13px;height:13px" class="text-red-500" />
                    </div>
                </div>
            </div>

            <div class="group rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-2.5 transition-all duration-150 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Inactifs</p>
                        <p class="text-xl font-extrabold text-slate-500">{{ $stats['inactifs'] }}</p>
                    </div>
                    <div style="width:28px;height:28px" class="rounded-lg bg-slate-500/10 flex items-center justify-center">
                        <x-heroicon-m-moon style="width:13px;height:13px" class="text-slate-500" />
                    </div>
                </div>
            </div>

        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 2xl:grid-cols-2 gap-4 items-start">

            {{-- Donut --}}
            <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 shadow-sm">

                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Répartition des agents</h3>
                        <p class="text-xs text-gray-500">État actuel des équipes terrain</p>
                    </div>
                    <div class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-800 text-[11px] font-semibold text-gray-500">{{ $total }} agents</div>
                </div>

                <div wire:ignore class="relative h-60">
                    <canvas id="chart-agents-donut"></canvas>
                </div>

                <div class="grid grid-cols-2 gap-2 mt-4">

                    @foreach([
                        ['Disponibles', $stats['disponibles'], 'bg-emerald-500'],
                        ['Occupés', $stats['occupes'], 'bg-amber-500'],
                        ['Absents', $stats['absents'], 'bg-red-500'],
                        ['Inactifs', $stats['inactifs'], 'bg-slate-500'],
                    ] as [$label, $value, $color])

                        <div class="flex items-center justify-between rounded-xl bg-gray-50 dark:bg-gray-800/60 px-3 py-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $color }}"></span>
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $label }}</span>
                            </div>
                            <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $value }}</span>
                        </div>

                    @endforeach

                </div>

            </div>

            {{-- Top Agents --}}
            <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 shadow-sm">

                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Top agents du mois</h3>
                        <p class="text-xs text-gray-500">Classement basé sur les missions effectuées</p>
                    </div>
                    <div style="width:30px;height:30px" class="rounded-xl bg-primary-500/10 flex items-center justify-center">
                        <x-heroicon-m-trophy style="width:14px;height:14px" class="text-primary-500"/>
                    </div>
                </div>

                <div wire:ignore class="relative h-60 mb-4">
                    <canvas id="chart-agents-bar"></canvas>
                </div>

                <div class="space-y-2">

                    @foreach($top as $index => $agent)

                        <div class="group flex items-center justify-between p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/60 hover:bg-primary-50 dark:hover:bg-primary-500/10 transition-all duration-200">
                            <div class="flex items-center gap-3">
                                <div style="width:30px;height:30px" class="rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 text-white flex items-center justify-center text-xs font-black shadow">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $agent['nom'] }}</p>
                                    <p class="text-[11px] text-gray-500">Agent terrain</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-black text-primary-500">{{ $agent['missions'] }}</p>
                                <p class="text-[11px] text-gray-500">missions</p>
                            </div>
                        </div>

                    @endforeach

                </div>

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

                        var dark = document.documentElement.classList.contains('dark');
                        var tick = dark ? '#94a3b8' : '#64748b';
                        var grid = dark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
                        var cardBg = dark ? '#111827' : '#ffffff';

                        var tip = {
                            backgroundColor: cardBg,
                            titleColor: dark ? '#fff' : '#0f172a',
                            bodyColor: dark ? '#cbd5e1' : '#64748b',
                            borderColor: dark ? '#1e293b' : '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 14,
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
                                        backgroundColor: ['rgba(16,185,129,0.95)', 'rgba(245,158,11,0.95)', 'rgba(239,68,68,0.95)', 'rgba(100,116,139,0.95)'],
                                        borderWidth: 0,
                                        hoverOffset: 10,
                                    }]
                                },
                                options: {
                                    responsive: true, maintainAspectRatio: false, cutout: '82%',
                                    plugins: { legend: { display: false }, tooltip: tip },
                                    animation: { animateRotate: true, duration: 1000, easing: 'easeOutQuart' },
                                },
                                plugins: [{
                                    id: 'centerText',
                                    beforeDraw(chart) {
                                        var ctx = chart.ctx;
                                        ctx.save();
                                        var cx = chart.width / 2, cy = chart.height / 2;
                                        ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                                        ctx.font = '800 38px Inter';
                                        ctx.fillStyle = dark ? '#fff' : '#0f172a';
                                        ctx.fillText('{{ $total }}', cx, cy - 10);
                                        ctx.font = '500 13px Inter';
                                        ctx.fillStyle = dark ? '#94a3b8' : '#64748b';
                                        ctx.fillText('Agents actifs', cx, cy + 20);
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
                                        backgroundColor: ['rgba(59,130,246,0.95)', 'rgba(16,185,129,0.95)', 'rgba(245,158,11,0.95)', 'rgba(139,92,246,0.95)', 'rgba(6,182,212,0.95)'],
                                        borderRadius: 12, borderSkipped: false, barThickness: 24,
                                    }]
                                },
                                options: {
                                    responsive: true, maintainAspectRatio: false, indexAxis: 'y',
                                    plugins: { legend: { display: false }, tooltip: tip },
                                    scales: {
                                        x: { beginAtZero: true, grid: { color: grid, drawBorder: false }, ticks: { color: tick, precision: 0, font: { size: 11 } }, border: { display: false } },
                                        y: { grid: { display: false }, ticks: { color: tick, font: { size: 12, weight: '600' } }, border: { display: false } },
                                    },
                                    animation: { duration: 1000, easing: 'easeOutQuart' },
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
