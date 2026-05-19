<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-m-chart-bar class="w-5 h-5 text-blue-500"/>
                Évolution des signalements — 30 derniers jours
            </div>
        </x-slot>
        <x-slot name="headerEnd">
            <span class="flex items-center gap-1.5 text-xs text-gray-400">
                <span class="live-dot"></span> Live
            </span>
        </x-slot>

        @php $d = $this->getData(); @endphp

        <div style="position:relative; height:300px;">
            <canvas id="chart-evolution"></canvas>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dark = document.documentElement.classList.contains('dark');
            const grid = dark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
            const tick = dark ? '#8b949e' : '#94a3b8';
            const cardBg = dark ? '#161b22' : '#ffffff';
            const tip = {
                backgroundColor: cardBg,
                titleColor: dark ? '#e6edf3' : '#1e293b',
                bodyColor: dark ? '#8b949e' : '#64748b',
                borderColor: dark ? '#30363d' : '#e2e8f0',
                borderWidth: 1,
                padding: 12,
                cornerRadius: 10,
                boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                titleFont: { weight: '600', size: 13 },
                bodyFont: { size: 12 },
            };
            const ctx = document.getElementById('chart-evolution').getContext('2d');

            function hexToRgba(hex, alpha) {
                const r = parseInt(hex.slice(1,3), 16), g = parseInt(hex.slice(3,5), 16), b = parseInt(hex.slice(5,7), 16);
                return `rgba(${r},${g},${b},${alpha})`;
            }
            function grad(hex, a1 = 0.25, a2 = 0.01) {
                const g = ctx.createLinearGradient(0, 0, 0, 280);
                g.addColorStop(0, hexToRgba(hex, a1));
                g.addColorStop(1, hexToRgba(hex, a2));
                return g;
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($d['labels']),
                    datasets: [
                        { label:'En attente', data: @json($d['attente']), borderColor:'#f59e0b', backgroundColor:grad('#f59e0b'), borderWidth:2.5, tension:0.35, fill:true, pointRadius:0, pointHoverRadius:6, pointHoverBackgroundColor:'#f59e0b', pointHoverBorderColor:'#fff', pointHoverBorderWidth:2 },
                        { label:'En cours',   data: @json($d['cours']),   borderColor:'#06b6d4', backgroundColor:grad('#06b6d4'), borderWidth:2.5, tension:0.35, fill:true, pointRadius:0, pointHoverRadius:6, pointHoverBackgroundColor:'#06b6d4', pointHoverBorderColor:'#fff', pointHoverBorderWidth:2 },
                        { label:'Terminés',   data: @json($d['termines']),borderColor:'#10b981', backgroundColor:grad('#10b981'), borderWidth:2.5, tension:0.35, fill:true, pointRadius:0, pointHoverRadius:6, pointHoverBackgroundColor:'#10b981', pointHoverBorderColor:'#fff', pointHoverBorderWidth:2 },
                        { label:'Rejetés',    data: @json($d['rejetes']), borderColor:'#f43f5e', backgroundColor:grad('#f43f5e'), borderWidth:2.5, tension:0.35, fill:true, pointRadius:0, pointHoverRadius:6, pointHoverBackgroundColor:'#f43f5e', pointHoverBorderColor:'#fff', pointHoverBorderWidth:2 },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'nearest', axis: 'x', intersect: false },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'center',
                            labels: {
                                color: tick,
                                font: { size: 11, weight: '500' },
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 20,
                                boxWidth: 8,
                                boxHeight: 8,
                            },
                        },
                        tooltip: tip,
                    },
                    scales: {
                        x: {
                            grid: { color: grid, drawBorder: false },
                            ticks: { color: tick, font: { size: 10 }, maxTicksLimit: 12, maxRotation: 0 },
                            border: { display: false },
                        },
                        y: {
                            grid: { color: grid, drawBorder: false },
                            ticks: { color: tick, precision: 0, font: { size: 10 } },
                            beginAtZero: true,
                            border: { display: false },
                        },
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart',
                    },
                },
            });
        });
        </script>
    </x-filament::section>
</x-filament-widgets::widget>
