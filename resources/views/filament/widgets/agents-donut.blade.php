<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div style="display:flex;align-items:center;justify-content:space-between;width:100%;gap:0.75rem">
                <div style="display:flex;align-items:center;gap:0.75rem;min-width:0">
                    <div style="display:flex;align-items:center;justify-content:center;border-radius:0.75rem;background:rgba(16,185,129,0.1);flex-shrink:0;width:36px;height:36px">
                        <x-heroicon-m-user-group style="width:18px;height:18px;color:#10b981" />
                    </div>
                    <div style="min-width:0">
                        <p style="font-size:0.875rem;font-weight:700;color:#111827;line-height:1.25">Agents — Statut & Performances</p>
                        <p style="font-size:0.75rem;color:#9ca3af;line-height:1.25">Vue globale en temps réel</p>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:0.375rem;padding:0.375rem 0.75rem;border-radius:9999px;background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.2);flex-shrink:0">
                    <span style="width:0.5rem;height:0.5rem;border-radius:9999px;background:#10b981;animation:pulse 2s cubic-bezier(0.4,0,0.6,1) infinite"></span>
                    <span style="font-size:0.75rem;font-weight:600;color:#059669">Temps réel</span>
                </div>
            </div>
        </x-slot>

        @php
            $stats = $this->getAgentStats();
            $top   = $this->getTopAgents();
            $total = $stats['total'];

            $kpiCards = [
                ['label' => 'Disponibles', 'value' => $stats['disponibles'], 'sub' => 'Prêts à intervenir', 'icon' => 'heroicon-m-check-circle', 'color' => '#10b981', 'bgColor' => 'rgba(16,185,129,0.1)', 'borderColor' => 'rgba(16,185,129,0.3)', 'barColor' => 'rgba(16,185,129,0.25)'],
                ['label' => 'Occupés', 'value' => $stats['occupes'], 'sub' => 'En intervention', 'icon' => 'heroicon-m-briefcase', 'color' => '#f59e0b', 'bgColor' => 'rgba(245,158,11,0.1)', 'borderColor' => 'rgba(245,158,11,0.3)', 'barColor' => 'rgba(245,158,11,0.25)'],
                ['label' => 'Absents', 'value' => $stats['absents'], 'sub' => 'Non pointés ce jour', 'icon' => 'heroicon-m-x-circle', 'color' => '#ef4444', 'bgColor' => 'rgba(239,68,68,0.1)', 'borderColor' => 'rgba(239,68,68,0.3)', 'barColor' => 'rgba(239,68,68,0.25)'],
                ['label' => 'Inactifs', 'value' => $stats['inactifs'], 'sub' => 'Comptes désactivés', 'icon' => 'heroicon-m-moon', 'color' => '#64748b', 'bgColor' => 'rgba(100,116,139,0.1)', 'borderColor' => 'rgba(100,116,139,0.3)', 'barColor' => 'rgba(100,116,139,0.25)'],
            ];
        @endphp

        <div style="display:flex;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.25rem">
            @foreach($kpiCards as $card)
            <div style="flex:1 1 140px;min-width:120px;position:relative;overflow:hidden;border-radius:1rem;border:1px solid {{ $card['borderColor'] }};background:linear-gradient(135deg,#fff,#fafafa);padding:1rem;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.5rem">
                    <div>
                        <p style="font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem">{{ $card['label'] }}</p>
                        <p style="font-size:1.75rem;font-weight:900;color:{{ $card['color'] }};line-height:1;margin:0">{{ $card['value'] }}</p>
                        <p style="font-size:0.625rem;color:#9ca3af;margin-top:0.375rem;line-height:1.25">{{ $card['sub'] }}</p>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:center;border-radius:0.75rem;background:{{ $card['bgColor'] }};width:36px;height:36px;flex-shrink:0">
                        <x-dynamic-component :component="$card['icon']" style="width:16px;height:16px;color:{{ $card['color'] }}" />
                    </div>
                </div>
                <div style="position:absolute;bottom:0;left:0;right:0;height:0.125rem;background:{{ $card['barColor'] }}"></div>
            </div>
            @endforeach
        </div>

        <div style="display:grid;grid-template-columns:1fr;gap:1rem" class="lg:grid-cols-2">

            <div style="border-radius:1rem;border:1px solid #e5e7eb;background:#fff;padding:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
                    <div>
                        <h3 style="font-size:0.875rem;font-weight:700;color:#111827">Répartition des agents</h3>
                        <p style="font-size:0.75rem;color:#9ca3af;margin-top:0.125rem">État actuel des équipes terrain</p>
                    </div>
                    <span style="display:inline-flex;align-items:center;gap:0.375rem;padding:0.25rem 0.625rem;border-radius:9999px;background:#f3f4f6;font-size:0.75rem;font-weight:700;color:#4b5563">
                        {{ $total }} agents
                    </span>
                </div>

                <div wire:ignore style="position:relative;margin:0 auto;height:280px;max-width:280px">
                    <canvas id="chart-agents-donut-{{ $this->getId() }}"></canvas>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;margin-top:1rem">
                    @foreach([
                        ['Disponibles', $stats['disponibles'], '#10b981', '#059669'],
                        ['Occupés', $stats['occupes'], '#f59e0b', '#d97706'],
                        ['Absents', $stats['absents'], '#ef4444', '#dc2626'],
                        ['Inactifs', $stats['inactifs'], '#94a3b8', '#64748b'],
                    ] as [$lbl, $val, $dot, $valClr])
                    <div style="display:flex;align-items:center;justify-content:space-between;border-radius:0.75rem;border:1px solid #f3f4f6;background:#f9fafb;padding:0.5rem 0.75rem">
                        <div style="display:flex;align-items:center;gap:0.5rem;min-width:0">
                            <span style="width:0.5rem;height:0.5rem;border-radius:9999px;background:{{ $dot }};flex-shrink:0"></span>
                            <span style="font-size:0.75rem;font-weight:500;color:#4b5563;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $lbl }}</span>
                        </div>
                        <span style="font-size:0.875rem;font-weight:900;color:{{ $valClr }};margin-left:0.5rem;flex-shrink:0">{{ $val }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div style="border-radius:1rem;border:1px solid #e5e7eb;background:#fff;padding:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
                    <div>
                        <h3 style="font-size:0.875rem;font-weight:700;color:#111827">Top agents du mois</h3>
                        <p style="font-size:0.75rem;color:#9ca3af;margin-top:0.125rem">Classés par missions effectuées</p>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:center;border-radius:0.75rem;background:rgba(245,158,11,0.1);width:36px;height:36px;flex-shrink:0">
                        <x-heroicon-m-trophy style="width:16px;height:16px;color:#f59e0b" />
                    </div>
                </div>

                @if(count($top))
                <div wire:ignore style="position:relative;height:280px">
                    <canvas id="chart-agents-bar-{{ $this->getId() }}"></canvas>
                </div>

                <div style="margin-top:1rem;display:flex;flex-direction:column;gap:0.5rem">
                    @foreach($top as $index => $agent)
                    @php
                        $rankGrads = ['linear-gradient(135deg,#f59e0b,#d97706)', 'linear-gradient(135deg,#94a3b8,#64748b)', 'linear-gradient(135deg,#f97316,#ea580c)', '#3b82f6', '#06b6d4'];
                        $rankGrad = $rankGrads[$index] ?? '#6b7280';
                    @endphp
                    <div style="display:flex;align-items:center;gap:0.75rem;padding:0.625rem 0.75rem;border-radius:0.75rem;border:1px solid #f3f4f6;background:rgba(249,250,251,0.7);transition:all 0.15s">
                        <div style="display:flex;align-items:center;justify-content:center;border-radius:0.5rem;flex-shrink:0;color:#fff;font-size:0.75rem;font-weight:900;width:1.625rem;height:1.625rem;background:{{ $rankGrad }}">
                            {{ $index + 1 }}
                        </div>
                        <div style="min-width:0;flex:1">
                            <p style="font-size:0.8125rem;font-weight:600;color:#111827;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $agent['nom'] }}</p>
                            <p style="font-size:0.625rem;color:#9ca3af">Agent terrain</p>
                        </div>
                        <div style="text-align:right;flex-shrink:0">
                            <p style="font-size:0.875rem;font-weight:900;color:#3b82f6;line-height:1">{{ $agent['missions'] }}</p>
                            <p style="font-size:0.625rem;color:#9ca3af">missions</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:0.75rem;padding:4rem 0">
                    <div style="display:flex;align-items:center;justify-content:center;border-radius:1rem;background:#f3f4f6;width:56px;height:56px">
                        <x-heroicon-o-inbox style="width:24px;height:24px;color:#9ca3af" />
                    </div>
                    <p style="font-size:0.875rem;color:#9ca3af;font-weight:500">Aucune mission ce mois</p>
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
                                        hoverOffset: 12,
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    cutout: '72%',
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
                                        ctx.font = '900 40px Inter, sans-serif';
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
                                            'rgba(245,158,11,0.85)',
                                            'rgba(148,163,184,0.85)',
                                            'rgba(249,115,22,0.85)',
                                            'rgba(59,130,246,0.85)',
                                            'rgba(6,182,212,0.85)'
                                        ],
                                        borderRadius: 8,
                                        borderSkipped: false,
                                        barThickness: 20,
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
