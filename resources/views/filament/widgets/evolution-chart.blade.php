<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-m-chart-bar style="width:18px;height:18px" class="text-primary-500" />
                <span class="text-sm font-semibold">Évolution des signalements — 30 jours</span>
            </div>
        </x-slot>

        @php $data = $this->getData(); @endphp

        <div wire:ignore style="position:relative; height:180px;">
            <canvas id="evolutionChart-{{ $this->getId() }}" style="display:block;width:100%;height:100%;"></canvas>
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

                        var dark       = document.documentElement.classList.contains('dark');
                        var tickColor  = dark ? '#9ca3af' : '#6b7280';
                        var gridColor  = dark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.06)';
                        var tooltipBg  = dark ? 'rgba(17,24,39,0.95)'   : 'rgba(0,0,0,0.80)';

                        canvas._chartInstance = new Chart(canvas, {
                            type: 'line',
                            data: {
                                labels: @json($data['labels']),
                                datasets: [
                                    {
                                        label: 'En attente',
                                        data: @json($data['attente']),
                                        borderColor: '#f59e0b',
                                        backgroundColor: 'rgba(245,158,11,0.12)',
                                        tension: 0.3, fill: true,
                                        pointRadius: 2, pointHoverRadius: 5, borderWidth: 2
                                    },
                                    {
                                        label: 'En cours',
                                        data: @json($data['cours']),
                                        borderColor: '#3b82f6',
                                        backgroundColor: 'rgba(59,130,246,0.12)',
                                        tension: 0.3, fill: true,
                                        pointRadius: 2, pointHoverRadius: 5, borderWidth: 2
                                    },
                                    {
                                        label: 'Terminés',
                                        data: @json($data['termines']),
                                        borderColor: '#10b981',
                                        backgroundColor: 'rgba(16,185,129,0.12)',
                                        tension: 0.3, fill: true,
                                        pointRadius: 2, pointHoverRadius: 5, borderWidth: 2
                                    },
                                    {
                                        label: 'Rejetés',
                                        data: @json($data['rejetes']),
                                        borderColor: '#ef4444',
                                        backgroundColor: 'rgba(239,68,68,0.08)',
                                        tension: 0.3, fill: true,
                                        pointRadius: 2, pointHoverRadius: 5, borderWidth: 1.5
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                animation: { duration: 400 },
                                interaction: { mode: 'index', intersect: false },
                                plugins: {
                                    legend: {
                                        position: 'top',
                                        labels: {
                                            color: tickColor,
                                            boxWidth: 10, boxHeight: 10,
                                            padding: 12,
                                            usePointStyle: true,
                                            pointStyle: 'circle',
                                            font: { size: 11 }
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: tooltipBg,
                                        titleColor: '#f9fafb',
                                        bodyColor: '#d1d5db',
                                        padding: 10,
                                        cornerRadius: 6
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: { display: false },
                                        border: { color: gridColor },
                                        ticks: { color: tickColor, maxTicksLimit: 12, font: { size: 10 } }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        grid: { color: gridColor },
                                        border: { display: false },
                                        ticks: { color: tickColor, precision: 0, font: { size: 10 } }
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
