<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full gap-3">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="flex items-center justify-center rounded-xl bg-primary-500/10 flex-shrink-0" style="width:36px;height:36px">
                        <x-heroicon-m-chart-bar style="width:18px;height:18px" class="text-primary-500" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight truncate">Évolution des signalements</p>
                        <p class="text-xs text-gray-400 leading-tight">30 derniers jours</p>
                    </div>
                </div>
                <div class="hidden sm:flex items-center gap-3 flex-shrink-0">
                    @foreach([
                        ['En attente', '#f59e0b'],
                        ['En cours', '#3b82f6'],
                        ['Terminés', '#10b981'],
                        ['Rejetés', '#ef4444'],
                    ] as [$label, $color])
                    <div class="flex items-center gap-1">
                        <span class="inline-block rounded-full flex-shrink-0" style="width:8px;height:8px;background:{{ $color }}"></span>
                        <span class="text-[10px] text-gray-500 whitespace-nowrap">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </x-slot>

        @php $data = $this->getData(); @endphp

        <div wire:ignore class="relative rounded-xl bg-gray-50/50 dark:bg-gray-900/30 overflow-hidden" style="height:280px;">
            <canvas id="evolutionChart-{{ $this->getId() }}" style="display:block;width:100%;height:100%;"></canvas>
        </div>

        {{-- Légende mobile --}}
        <div class="flex sm:hidden items-center gap-3 mt-2 flex-wrap">
            @foreach([
                ['En attente', '#f59e0b'],
                ['En cours', '#3b82f6'],
                ['Terminés', '#10b981'],
                ['Rejetés', '#ef4444'],
            ] as [$label, $color])
            <div class="flex items-center gap-1">
                <span class="inline-block rounded-full flex-shrink-0" style="width:8px;height:8px;background:{{ $color }}"></span>
                <span class="text-[10px] text-gray-500">{{ $label }}</span>
            </div>
            @endforeach
        </div>

        <div wire:ignore>
            <script>
                (function () {
                    var _evo_retries = 0;

                    function initEvoChart() {
                        if (typeof Chart === 'undefined') {
                            if (_evo_retries++ < 80) setTimeout(initEvoChart, 100);
                            return;
                        }
                        var canvas = document.getElementById('evolutionChart-{{ $this->getId() }}');
                        if (!canvas) return;
                        if (canvas._chartInstance) { canvas._chartInstance.destroy(); canvas._chartInstance = null; }

                        var dark      = document.documentElement.classList.contains('dark');
                        var tickColor = dark ? '#9ca3af' : '#6b7280';
                        var gridColor = dark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';
                        var tipBg     = dark ? 'rgba(17,24,39,0.97)' : 'rgba(255,255,255,0.97)';
                        var tipTitle  = dark ? '#f9fafb' : '#0f172a';
                        var tipBody   = dark ? '#d1d5db' : '#64748b';
                        var tipBorder = dark ? '#1e293b' : '#e2e8f0';

                        canvas._chartInstance = new Chart(canvas, {
                            type: 'line',
                            data: {
                                labels: @json($data['labels']),
                                datasets: [
                                    {
                                        label: 'En attente',
                                        data: @json($data['attente']),
                                        borderColor: '#f59e0b',
                                        backgroundColor: 'rgba(245,158,11,0.08)',
                                        tension: 0.4, fill: true,
                                        pointRadius: 0, pointHoverRadius: 4, borderWidth: 2
                                    },
                                    {
                                        label: 'En cours',
                                        data: @json($data['cours']),
                                        borderColor: '#3b82f6',
                                        backgroundColor: 'rgba(59,130,246,0.08)',
                                        tension: 0.4, fill: true,
                                        pointRadius: 0, pointHoverRadius: 4, borderWidth: 2
                                    },
                                    {
                                        label: 'Terminés',
                                        data: @json($data['termines']),
                                        borderColor: '#10b981',
                                        backgroundColor: 'rgba(16,185,129,0.08)',
                                        tension: 0.4, fill: true,
                                        pointRadius: 0, pointHoverRadius: 4, borderWidth: 2
                                    },
                                    {
                                        label: 'Rejetés',
                                        data: @json($data['rejetes']),
                                        borderColor: '#ef4444',
                                        backgroundColor: 'rgba(239,68,68,0.05)',
                                        tension: 0.4, fill: true,
                                        pointRadius: 0, pointHoverRadius: 4, borderWidth: 1.5
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                animation: { duration: 600, easing: 'easeOutQuart' },
                                interaction: { mode: 'index', intersect: false },
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: tipBg,
                                        titleColor: tipTitle,
                                        bodyColor: tipBody,
                                        borderColor: tipBorder,
                                        borderWidth: 1,
                                        padding: 10,
                                        cornerRadius: 10,
                                        displayColors: true,
                                        boxWidth: 8,
                                        boxHeight: 8,
                                        usePointStyle: true,
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: { display: false },
                                        border: { display: false },
                                        ticks: { color: tickColor, maxTicksLimit: 8, font: { size: 10 } }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        grid: { color: gridColor, drawBorder: false },
                                        border: { display: false },
                                        ticks: { color: tickColor, precision: 0, font: { size: 10 }, maxTicksLimit: 5 }
                                    }
                                }
                            }
                        });
                    }

                    document.addEventListener('DOMContentLoaded', initEvoChart);
                    document.addEventListener('livewire:navigated', function () {
                        _evo_retries = 0;
                        initEvoChart();
                    });
                })();
            </script>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
