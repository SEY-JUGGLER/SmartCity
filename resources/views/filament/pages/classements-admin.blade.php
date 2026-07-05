<x-filament-panels::page>

@php
    $stats = $this->getStatsTop();
    $agentCls = \App\Services\ClassificationService::AGENT_CLASSES;
    $citCls   = \App\Services\ClassificationService::CITOYEN_CLASSES;
@endphp

<div style="display:flex;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.5rem">
    <div class="relative overflow-hidden p-4 shadow-sm" style="flex:1 1 150px;min-width:130px;border-radius:1rem;border:1px solid #dbeafe;background:#fff">
        <div class="flex items-start justify-between gap-2">
            <div>
                <p style="font-size:0.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem">Agents</p>
                <p style="font-size:1.875rem;font-weight:900;color:#3b82f6;line-height:1;margin:0">{{ $stats['total_agents'] }}</p>
                <p style="font-size:0.625rem;color:#9ca3af;margin-top:0.375rem">Total inscrits</p>
            </div>
            <div style="display:flex;align-items:center;justify-content:center;border-radius:0.75rem;background:rgba(59,130,246,0.1);width:36px;height:36px;flex-shrink:0">
                <svg style="width:16px;height:16px;color:#3b82f6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z" /></svg>
            </div>
        </div>
        <div style="position:absolute;bottom:0;left:0;right:0;height:0.125rem;background:rgba(59,130,246,0.25)"></div>
    </div>

    <div class="relative overflow-hidden p-4 shadow-sm" style="flex:1 1 150px;min-width:130px;border-radius:1rem;border:1px solid #d1fae5;background:#fff">
        <div class="flex items-start justify-between gap-2">
            <div>
                <p style="font-size:0.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem">Disponibles</p>
                <p style="font-size:1.875rem;font-weight:900;color:#10b981;line-height:1;margin:0">{{ $stats['disponibles'] }}</p>
                <p style="font-size:0.625rem;color:#9ca3af;margin-top:0.375rem">Prêts à intervenir</p>
            </div>
            <div style="display:flex;align-items:center;justify-content:center;border-radius:0.75rem;background:rgba(16,185,129,0.1);width:36px;height:36px;flex-shrink:0">
                <svg style="width:16px;height:16px;color:#10b981" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
        <div style="position:absolute;bottom:0;left:0;right:0;height:0.125rem;background:rgba(16,185,129,0.25)"></div>
    </div>

    <div class="relative overflow-hidden p-4 shadow-sm" style="flex:1 1 150px;min-width:130px;border-radius:1rem;border:1px solid #fef3c7;background:#fff">
        <div class="flex items-start justify-between gap-2">
            <div>
                <p style="font-size:0.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem">Note moyenne</p>
                <p style="font-size:1.875rem;font-weight:900;color:#f59e0b;line-height:1;margin:0">{{ number_format($stats['moy_taux'],1) }}</p>
                <p style="font-size:0.625rem;color:#9ca3af;margin-top:0.375rem">Sur 5 étoiles</p>
            </div>
            <div style="display:flex;align-items:center;justify-content:center;border-radius:0.75rem;background:rgba(245,158,11,0.1);width:36px;height:36px;flex-shrink:0">
                <svg style="width:16px;height:16px;color:#f59e0b" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
            </div>
        </div>
        <div style="position:absolute;bottom:0;left:0;right:0;height:0.125rem;background:rgba(245,158,11,0.25)"></div>
    </div>

    <div class="relative overflow-hidden p-4 shadow-sm" style="flex:1 1 150px;min-width:130px;border-radius:1rem;border:1px solid #ede9fe;background:#fff">
        <div class="flex items-start justify-between gap-2">
            <div>
                <p style="font-size:0.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem">Citoyens</p>
                <p style="font-size:1.875rem;font-weight:900;color:#8b5cf6;line-height:1;margin:0">{{ $stats['total_citoyens'] }}</p>
                <p style="font-size:0.625rem;color:#9ca3af;margin-top:0.375rem">Total inscrits</p>
            </div>
            <div style="display:flex;align-items:center;justify-content:center;border-radius:0.75rem;background:rgba(139,92,246,0.1);width:36px;height:36px;flex-shrink:0">
                <svg style="width:16px;height:16px;color:#8b5cf6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </div>
        </div>
        <div style="position:absolute;bottom:0;left:0;right:0;height:0.125rem;background:rgba(139,92,246,0.25)"></div>
    </div>

    <div class="relative overflow-hidden p-4 shadow-sm" style="flex:1 1 150px;min-width:130px;border-radius:1rem;border:1px solid #ffe4e6;background:#fff">
        <div class="flex items-start justify-between gap-2">
            <div>
                <p style="font-size:0.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem">Missions</p>
                <p style="font-size:1.875rem;font-weight:900;color:#f43f5e;line-height:1;margin:0">{{ \App\Models\Attribution::count() }}</p>
                <p style="font-size:0.625rem;color:#9ca3af;margin-top:0.375rem">Attribuées</p>
            </div>
            <div style="display:flex;align-items:center;justify-content:center;border-radius:0.75rem;background:rgba(244,63,94,0.1);width:36px;height:36px;flex-shrink:0">
                <svg style="width:16px;height:16px;color:#f43f5e" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" /></svg>
            </div>
        </div>
        <div style="position:absolute;bottom:0;left:0;right:0;height:0.125rem;background:rgba(244,63,94,0.25)"></div>
    </div>
</div>

<div style="display:flex;gap:0.25rem;margin-bottom:1.25rem;padding:0.25rem;border-radius:0.75rem;background:#f3f4f6;width:fit-content">
    <button wire:click="$set('onglet','agents')"
        style="padding:0.5rem 1rem;font-size:0.875rem;font-weight:500;border-radius:0.5rem;transition:all 0.2s;{{ $onglet==='agents' ? 'background:#fff;color:#2563eb;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1)' : 'color:#6b7280;background:transparent' }}">
        <div style="display:flex;align-items:center;gap:0.5rem">
            <svg style="width:1rem;height:1rem" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z" /></svg>
            Agents
        </div>
    </button>
    <button wire:click="$set('onglet','citoyens')"
        style="padding:0.5rem 1rem;font-size:0.875rem;font-weight:500;border-radius:0.5rem;transition:all 0.2s;{{ $onglet==='citoyens' ? 'background:#fff;color:#2563eb;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1)' : 'color:#6b7280;background:transparent' }}">
        <div style="display:flex;align-items:center;gap:0.5rem">
            <svg style="width:1rem;height:1rem" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            Citoyens
        </div>
    </button>
</div>

@if($onglet === 'agents')
    @php $agents = $this->getAgentsRanking(); @endphp

    <div style="display:flex;flex-direction:column;gap:0.75rem;margin-bottom:1rem" class="sm:flex-row sm:items-center sm:justify-between">
        <div style="position:relative;width:100%;max-width:18rem">
            <input wire:model.live.debounce.300ms="recherche" type="text" placeholder="Rechercher un agent..."
                style="width:100%;padding:0.5rem 0.75rem;font-size:0.875rem;border-radius:0.75rem;border:1px solid #e5e7eb;background:#fff;color:#111827;outline:none;transition:all 0.15s">
        </div>
        <div style="display:flex;gap:0.25rem;padding:0.25rem;border-radius:0.75rem;background:#f3f4f6">
            @foreach(['missions'=>'Missions','note'=>'Note','taux'=>'Taux','réaction'=>'Réac.'] as $v => $l)
            <button wire:click="$set('triAgent','{{ $v }}')"
                style="padding:0.375rem 0.75rem;border-radius:0.5rem;font-size:0.75rem;font-weight:500;transition:all 0.2s;{{ $triAgent===$v ? 'background:#fff;color:#2563eb;box-shadow:0 1px 3px rgba(0,0,0,0.1)' : 'color:#6b7280;background:transparent' }}">{{ $l }}</button>
            @endforeach
        </div>
    </div>

    <div style="border-radius:1rem;border:1px solid #e5e7eb;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,0.1);overflow:hidden;width:100%">
        <div style="overflow-x:auto;width:100%">
            <table style="width:100%;min-width:640px;border-collapse:collapse">
                <thead>
                    <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb">
                        <th style="padding:0.75rem 1rem;text-align:left;font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em">Rang</th>
                        <th style="padding:0.75rem 1rem;text-align:left;font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em">Agent</th>
                        <th style="padding:0.75rem 1rem;text-align:left;font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em">Classe</th>
                        <th style="padding:0.75rem 1rem;text-align:center;font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em">Missions</th>
                        <th style="padding:0.75rem 1rem;text-align:center;font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em">Taux</th>
                        <th style="padding:0.75rem 1rem;text-align:center;font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em">Note</th>
                        <th style="padding:0.75rem 1rem;text-align:center;font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em">Réaction</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agents as $i => $a)
                    @php
                        $rank = $i + 1;
                        $isPodium = $rank <= 3;
                        $podiumColors = [1 => '#f59e0b', 2 => '#64748b', 3 => '#f97316'];
                        $podiumBg = [1 => '#fbbf24', 2 => '#94a3b8', 3 => '#fb923c'];
                        $rankColor = $isPodium ? $podiumColors[$rank] : '#6b7280';
                        $rankBg = $isPodium ? $podiumBg[$rank] : '#f3f4f6';
                        $cc = $a->classification['color'] ?? 'slate';
                        $badgeColors = ['emerald'=>'#d1fae5;#047857','blue'=>'#dbeafe;#1d4ed8','amber'=>'#fef3c7;#b45309','violet'=>'#ede9fe;#6d28d9','red'=>'#fee2e2;#b91c1c','slate'=>'#f3f4f6;#6b7280'];
                        [$badgeBg,$badgeTxt] = explode(';', $badgeColors[$cc] ?? $badgeColors['slate']);
                        $tRate = $a->taux_completion;
                        $tCls = $tRate >= 70 ? '#10b981' : ($tRate >= 40 ? '#f59e0b' : '#ef4444');
                        $tGrad = $tRate >= 70 ? 'linear-gradient(to right,#10b981,#34d399)' : ($tRate >= 40 ? 'linear-gradient(to right,#f59e0b,#fbbf24)' : 'linear-gradient(to right,#ef4444,#f87171)');
                        $rowBg = $i % 2 === 0 ? '#fff' : 'rgba(249,250,251,0.5)';
                    @endphp
                    <tr style="background:{{ $rowBg }};border-bottom:1px solid #f3f4f6">
                        <td style="padding:0.75rem 1rem;text-align:center">
                            <span style="display:inline-flex;align-items:center;justify-content:center;width:1.75rem;height:1.75rem;border-radius:0.5rem;background:{{ $rankBg }};color:{{ $rankColor }};font-size:0.75rem;font-weight:{{ $isPodium ? 800 : 500 }}">{{ $rank }}</span>
                        </td>
                        <td style="padding:0.75rem 1rem">
                            <div style="display:flex;align-items:center;gap:0.75rem">
                                <div style="width:2rem;height:2rem;border-radius:9999px;background:linear-gradient(135deg,#dbeafe,#bfdbfe);display:flex;align-items:center;justify-content:center;flex-shrink:0;border:2px solid #f3f4f6">
                                    <span style="font-size:0.75rem;font-weight:700;color:#2563eb">{{ substr($a->nom, 0, 2) }}</span>
                                </div>
                                <div>
                                    <span style="font-size:0.875rem;font-weight:600;color:#111827">{{ $a->nom }}</span>
                                    <span style="font-size:0.6875rem;color:#9ca3af;margin-left:0.5rem">{{ $a->zone }}</span>
                                </div>
                            </div>
                        </td>
                        <td style="padding:0.75rem 1rem">
                            <span style="display:inline-flex;align-items:center;padding:0.125rem 0.5rem;border-radius:0.5rem;font-size:0.625rem;font-weight:600;background:{{ $badgeBg }};color:{{ $badgeTxt }}">{{ $a->classification['label'] ?? '—' }}</span>
                        </td>
                        <td style="padding:0.75rem 1rem;text-align:center">
                            <span style="font-size:0.875rem;font-weight:600;color:#111827">{{ $a->missions_terminees }}</span>
                            <span style="color:#9ca3af;font-size:0.6875rem">/{{ $a->total_missions }}</span>
                        </td>
                        <td style="padding:0.75rem 1rem;text-align:center">
                            <div style="display:flex;align-items:center;gap:0.5rem;justify-content:center">
                                <div style="width:4rem;height:0.375rem;background:#f3f4f6;border-radius:9999px;overflow:hidden;flex-shrink:0">
                                    <div style="height:100%;border-radius:9999px;background:{{ $tGrad }};width:{{ $tRate }}%"></div>
                                </div>
                                <span style="font-size:0.6875rem;font-weight:600;color:{{ $tCls }};min-width:2rem;text-align:right">{{ $tRate }}%</span>
                            </div>
                        </td>
                        <td style="padding:0.75rem 1rem;text-align:center">
                            @if($a->note_moyenne > 0)
                            @php $nCls = $a->note_moyenne >= 4 ? '#059669' : ($a->note_moyenne >= 2.5 ? '#d97706' : '#dc2626'); @endphp
                            <span style="display:inline-flex;align-items:center;gap:0.25rem;font-size:0.875rem;font-weight:700;color:{{ $nCls }}">
                                <svg style="width:0.875rem;height:0.875rem" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                {{ number_format($a->note_moyenne,1) }}
                            </span>
                            @else
                            <span style="color:#d1d5db;font-size:0.875rem">—</span>
                            @endif
                        </td>
                        <td style="padding:0.75rem 1rem;text-align:center">
                            @if($a->avg_reaction > 0)
                            @php $rGrad = $a->avg_reaction <= 4 ? '#d1fae5' : ($a->avg_reaction <= 12 ? '#fef3c7' : '#f3f4f6'); $rTxt = $a->avg_reaction <= 4 ? '#047857' : ($a->avg_reaction <= 12 ? '#b45309' : '#6b7280'); @endphp
                            <span style="display:inline-flex;align-items:center;padding:0.125rem 0.5rem;border-radius:0.5rem;font-size:0.625rem;font-weight:600;background:{{ $rGrad }};color:{{ $rTxt }}">{{ $a->avg_reaction }}h</span>
                            @else
                            <span style="color:#d1d5db;font-size:0.875rem">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="padding:4rem 0;text-align:center;color:#9ca3af;font-size:0.875rem">Aucun agent trouvé</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

@if($onglet === 'citoyens')
    @php $cits = $this->getCitoyensRanking(); @endphp

    <div style="display:flex;flex-direction:column;gap:0.75rem;margin-bottom:1rem" class="sm:flex-row sm:items-center sm:justify-between">
        <div style="position:relative;width:100%;max-width:18rem">
            <input wire:model.live.debounce.300ms="recherche" type="text" placeholder="Rechercher un citoyen..."
                style="width:100%;padding:0.5rem 0.75rem;font-size:0.875rem;border-radius:0.75rem;border:1px solid #e5e7eb;background:#fff;color:#111827;outline:none;transition:all 0.15s">
        </div>
        <div style="display:flex;gap:0.25rem;padding:0.25rem;border-radius:0.75rem;background:#f3f4f6">
            @foreach(['signalements'=>'Signalements','evaluations'=>'Évals','taux'=>'Taux','engagement'=>'Eng.'] as $v => $l)
            <button wire:click="$set('triCitoyen','{{ $v }}')"
                style="padding:0.375rem 0.75rem;border-radius:0.5rem;font-size:0.75rem;font-weight:500;transition:all 0.2s;{{ $triCitoyen===$v ? 'background:#fff;color:#2563eb;box-shadow:0 1px 3px rgba(0,0,0,0.1)' : 'color:#6b7280;background:transparent' }}">{{ $l }}</button>
            @endforeach
        </div>
    </div>

    <div style="border-radius:1rem;border:1px solid #e5e7eb;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,0.1);overflow:hidden;width:100%">
        <div style="overflow-x:auto;width:100%">
            <table style="width:100%;min-width:700px;border-collapse:collapse">
                <thead>
                    <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb">
                        <th style="padding:0.75rem 1rem;text-align:left;font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em">Rang</th>
                        <th style="padding:0.75rem 1rem;text-align:left;font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em">Citoyen</th>
                        <th style="padding:0.75rem 1rem;text-align:left;font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em">Classe</th>
                        <th style="padding:0.75rem 1rem;text-align:center;font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em">Signalements</th>
                        <th style="padding:0.75rem 1rem;text-align:center;font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em">Résolus</th>
                        <th style="padding:0.75rem 1rem;text-align:center;font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em">Rejetés</th>
                        <th style="padding:0.75rem 1rem;text-align:center;font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em">Taux</th>
                        <th style="padding:0.75rem 1rem;text-align:center;font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em">Évals</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cits as $i => $u)
                    @php
                        $rank = $i + 1;
                        $isPodium = $rank <= 3;
                        $podiumColors = [1 => '#f59e0b', 2 => '#64748b', 3 => '#f97316'];
                        $podiumBg = [1 => '#fbbf24', 2 => '#94a3b8', 3 => '#fb923c'];
                        $rankColor = $isPodium ? $podiumColors[$rank] : '#6b7280';
                        $rankBg = $isPodium ? $podiumBg[$rank] : '#f3f4f6';
                        $cc = $u->classification['color'] ?? 'slate';
                        $badgeColors = ['emerald'=>'#d1fae5;#047857','blue'=>'#dbeafe;#1d4ed8','amber'=>'#fef3c7;#b45309','violet'=>'#ede9fe;#6d28d9','red'=>'#fee2e2;#b91c1c','slate'=>'#f3f4f6;#6b7280'];
                        [$badgeBg,$badgeTxt] = explode(';', $badgeColors[$cc] ?? $badgeColors['slate']);
                        $tRate = $u->taux_validation;
                        $tCls = $tRate >= 70 ? '#10b981' : ($tRate >= 40 ? '#f59e0b' : '#ef4444');
                        $tGrad = $tRate >= 70 ? 'linear-gradient(to right,#10b981,#34d399)' : ($tRate >= 40 ? 'linear-gradient(to right,#f59e0b,#fbbf24)' : 'linear-gradient(to right,#ef4444,#f87171)');
                        $rowBg = $i % 2 === 0 ? '#fff' : 'rgba(249,250,251,0.5)';
                    @endphp
                    <tr style="background:{{ $rowBg }};border-bottom:1px solid #f3f4f6">
                        <td style="padding:0.75rem 1rem;text-align:center">
                            <span style="display:inline-flex;align-items:center;justify-content:center;width:1.75rem;height:1.75rem;border-radius:0.5rem;background:{{ $rankBg }};color:{{ $rankColor }};font-size:0.75rem;font-weight:{{ $isPodium ? 800 : 500 }}">{{ $rank }}</span>
                        </td>
                        <td style="padding:0.75rem 1rem">
                            <div style="display:flex;align-items:center;gap:0.75rem">
                                <div style="width:2rem;height:2rem;border-radius:9999px;background:linear-gradient(135deg,#ede9fe,#ddd6fe);display:flex;align-items:center;justify-content:center;flex-shrink:0;border:2px solid #f3f4f6">
                                    <span style="font-size:0.75rem;font-weight:700;color:#7c3aed">{{ substr($u->nom, 0, 2) }}</span>
                                </div>
                                <div>
                                    <span style="font-size:0.875rem;font-weight:600;color:#111827">{{ $u->nom }}</span>
                                    <span style="font-size:0.6875rem;color:#9ca3af;margin-left:0.5rem">{{ $u->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td style="padding:0.75rem 1rem">
                            <span style="display:inline-flex;align-items:center;padding:0.125rem 0.5rem;border-radius:0.5rem;font-size:0.625rem;font-weight:600;background:{{ $badgeBg }};color:{{ $badgeTxt }}">{{ $u->classification['label'] ?? '—' }}</span>
                        </td>
                        <td style="padding:0.75rem 1rem;text-align:center;font-size:0.875rem;font-weight:600;color:#111827">{{ $u->signalements }}</td>
                        <td style="padding:0.75rem 1rem;text-align:center">
                            <span style="display:inline-flex;align-items:center;font-size:0.875rem;font-weight:600;color:#059669">
                                <svg style="width:0.875rem;height:0.875rem;margin-right:0.125rem" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                {{ $u->termines }}
                            </span>
                        </td>
                        <td style="padding:0.75rem 1rem;text-align:center">
                            @if($u->rejetes > 0)
                            <span style="display:inline-flex;align-items:center;font-size:0.875rem;font-weight:600;color:#dc2626">
                                <svg style="width:0.875rem;height:0.875rem;margin-right:0.125rem" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                {{ $u->rejetes }}
                            </span>
                            @else
                            <span style="color:#d1d5db;font-size:0.875rem">—</span>
                            @endif
                        </td>
                        <td style="padding:0.75rem 1rem;text-align:center">
                            <div style="display:flex;align-items:center;gap:0.5rem;justify-content:center">
                                <div style="width:4rem;height:0.375rem;background:#f3f4f6;border-radius:9999px;overflow:hidden;flex-shrink:0">
                                    <div style="height:100%;border-radius:9999px;background:{{ $tGrad }};width:{{ $tRate }}%"></div>
                                </div>
                                <span style="font-size:0.6875rem;font-weight:600;color:{{ $tCls }};min-width:2rem;text-align:right">{{ $tRate }}%</span>
                            </div>
                        </td>
                        <td style="padding:0.75rem 1rem;text-align:center">
                            @if($u->evaluations > 0)
                            <span style="display:inline-flex;align-items:center;padding:0.125rem 0.5rem;border-radius:0.5rem;font-size:0.625rem;font-weight:600;background:#ede9fe;color:#6d28d9">{{ $u->evaluations }}</span>
                            @else
                            <span style="color:#d1d5db;font-size:0.875rem">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" style="padding:4rem 0;text-align:center;color:#9ca3af;font-size:0.875rem">Aucun citoyen trouvé</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

</x-filament-panels::page>
