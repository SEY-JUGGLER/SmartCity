<x-filament-widgets::widget>
    <x-filament::section
        class="overflow-hidden rounded-3xl border border-gray-200/60 dark:border-gray-800 bg-gradient-to-br from-white via-white to-gray-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-950 shadow-xl"
    >
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-500/10 flex items-center justify-center">
                        <x-heroicon-m-user-group class="w-6 h-6 text-emerald-500"/>
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
            $total = array_sum($stats);
        @endphp

        {{-- KPI Cards --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-7">

            <div class="group rounded-3xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-5 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Disponibles</p>

                        <h2 class="text-4xl font-black text-emerald-500">
                            {{ $stats['disponibles'] }}
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center">
                        <x-heroicon-m-check-circle class="w-7 h-7 text-emerald-500"/>
                    </div>
                </div>
            </div>

            <div class="group rounded-3xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-5 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Occupés</p>

                        <h2 class="text-4xl font-black text-amber-500">
                            {{ $stats['occupes'] }}
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center">
                        <x-heroicon-m-briefcase class="w-7 h-7 text-amber-500"/>
                    </div>
                </div>
            </div>

            <div class="group rounded-3xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-5 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Absents</p>

                        <h2 class="text-4xl font-black text-red-500">
                            {{ $stats['absents'] }}
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-red-500/10 flex items-center justify-center">
                        <x-heroicon-m-x-circle class="w-7 h-7 text-red-500"/>
                    </div>
                </div>
            </div>

            <div class="group rounded-3xl bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-5 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Inactifs</p>

                        <h2 class="text-4xl font-black text-slate-500">
                            {{ $stats['inactifs'] }}
                        </h2>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-slate-500/10 flex items-center justify-center">
                        <x-heroicon-m-moon class="w-7 h-7 text-slate-500"/>
                    </div>
                </div>
            </div>

        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 2xl:grid-cols-2 gap-6 items-start">

            {{-- Donut --}}
            <div class="rounded-3xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm hover:shadow-xl transition-all duration-300">

                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            Répartition des agents
                        </h3>

                        <p class="text-sm text-gray-500">
                            État actuel des équipes terrain
                        </p>
                    </div>

                    <div class="px-3 py-1 rounded-full bg-gray-100 dark:bg-gray-800 text-xs font-semibold text-gray-500">
                        {{ $total }} agents
                    </div>
                </div>

                <div class="relative h-72">
                    <canvas id="chart-agents-donut"></canvas>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-6">

                    @foreach([
                        ['Disponibles', $stats['disponibles'], 'bg-emerald-500'],
                        ['Occupés', $stats['occupes'], 'bg-amber-500'],
                        ['Absents', $stats['absents'], 'bg-red-500'],
                        ['Inactifs', $stats['inactifs'], 'bg-slate-500'],
                    ] as [$label, $value, $color])

                        <div class="flex items-center justify-between rounded-2xl bg-gray-50 dark:bg-gray-800/60 px-4 py-3">

                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full {{ $color }}"></span>

                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $label }}
                                </span>
                            </div>

                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ $value }}
                            </span>

                        </div>

                    @endforeach

                </div>

            </div>

            {{-- Top Agents --}}
            <div class="rounded-3xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 shadow-sm hover:shadow-xl transition-all duration-300">

                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            Top agents du mois
                        </h3>

                        <p class="text-sm text-gray-500">
                            Classement basé sur les missions effectuées
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-2xl bg-primary-500/10 flex items-center justify-center">
                        <x-heroicon-m-trophy class="w-6 h-6 text-primary-500"/>
                    </div>
                </div>

                <div class="relative h-72 mb-6">
                    <canvas id="chart-agents-bar"></canvas>
                </div>

                <div class="space-y-3">

                    @foreach($top as $index => $agent)

                        <div class="group flex items-center justify-between p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/60 hover:bg-primary-50 dark:hover:bg-primary-500/10 transition-all duration-300">

                            <div class="flex items-center gap-4">

                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 text-white flex items-center justify-center text-sm font-black shadow-lg">
                                    {{ $index + 1 }}
                                </div>

                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ $agent['nom'] }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        Agent terrain
                                    </p>
                                </div>

                            </div>

                            <div class="text-right">

                                <p class="text-xl font-black text-primary-500">
                                    {{ $agent['missions'] }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    missions
                                </p>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const dark = document.documentElement.classList.contains('dark');

                const tick = dark ? '#94a3b8' : '#64748b';
                const grid = dark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

                const cardBg = dark ? '#111827' : '#ffffff';

                const tip = {
                    backgroundColor: cardBg,
                    titleColor: dark ? '#fff' : '#0f172a',
                    bodyColor: dark ? '#cbd5e1' : '#64748b',
                    borderColor: dark ? '#1e293b' : '#e2e8f0',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 14,
                    displayColors: true,
                };

                // DONUT
                new Chart(document.getElementById('chart-agents-donut'), {
                    type: 'doughnut',

                    data: {
                        labels: ['Disponibles', 'Occupés', 'Absents', 'Inactifs'],

                        datasets: [{
                            data: [
                                {{ $stats['disponibles'] }},
                                {{ $stats['occupes'] }},
                                {{ $stats['absents'] }},
                                {{ $stats['inactifs'] }}
                            ],

                            backgroundColor: [
                                'rgba(16,185,129,0.95)',
                                'rgba(245,158,11,0.95)',
                                'rgba(239,68,68,0.95)',
                                'rgba(100,116,139,0.95)',
                            ],

                            borderWidth: 0,
                            hoverOffset: 10,
                        }]
                    },

                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '82%',

                        plugins: {
                            legend: {
                                display: false
                            },

                            tooltip: tip,
                        },

                        animation: {
                            animateRotate: true,
                            duration: 1000,
                            easing: 'easeOutQuart',
                        },
                    },

                    plugins: [{
                        id: 'centerText',

                        beforeDraw(chart) {

                            const { width, height, ctx } = chart;

                            ctx.save();

                            const centerX = width / 2;
                            const centerY = height / 2;

                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';

                            ctx.font = '800 38px Inter';
                            ctx.fillStyle = dark ? '#fff' : '#0f172a';

                            ctx.fillText('{{ $total }}', centerX, centerY - 10);

                            ctx.font = '500 13px Inter';
                            ctx.fillStyle = dark ? '#94a3b8' : '#64748b';

                            ctx.fillText('Agents actifs', centerX, centerY + 20);

                            ctx.restore();
                        }
                    }]
                });

                // BAR CHART
                const top = @json($top);

                if (top.length) {

                    new Chart(document.getElementById('chart-agents-bar'), {

                        type: 'bar',

                        data: {
                            labels: top.map(a => a.nom),

                            datasets: [{
                                label: 'Missions',

                                data: top.map(a => a.missions),

                                backgroundColor: [
                                    'rgba(59,130,246,0.95)',
                                    'rgba(16,185,129,0.95)',
                                    'rgba(245,158,11,0.95)',
                                    'rgba(139,92,246,0.95)',
                                    'rgba(6,182,212,0.95)',
                                ],

                                borderRadius: 12,
                                borderSkipped: false,
                                barThickness: 24,
                            }]
                        },

                        options: {

                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: 'y',

                            plugins: {
                                legend: {
                                    display: false
                                },

                                tooltip: tip,
                            },

                            scales: {

                                x: {
                                    beginAtZero: true,

                                    grid: {
                                        color: grid,
                                        drawBorder: false,
                                    },

                                    ticks: {
                                        color: tick,
                                        precision: 0,
                                        font: {
                                            size: 11
                                        }
                                    },

                                    border: {
                                        display: false
                                    },
                                },

                                y: {

                                    grid: {
                                        display: false
                                    },

                                    ticks: {
                                        color: tick,

                                        font: {
                                            size: 12,
                                            weight: '600'
                                        }
                                    },

                                    border: {
                                        display: false
                                    },
                                },
                            },

                            animation: {
                                duration: 1000,
                                easing: 'easeOutQuart',
                            },
                        },
                    });
                }
            });
        </script>
    </x-filament::section>
</x-filament-widgets::widget>