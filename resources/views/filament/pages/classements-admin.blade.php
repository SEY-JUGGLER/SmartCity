<x-filament-panels::page>
<style>
    .cl-tab-active { background:#fff; box-shadow:0 1px 6px rgba(0,0,0,.1); }
    .dark .cl-tab-active { background:#374151; }
    .cl-sort-active { background:#6366f1; color:#fff; }
    .cl-row:hover { background:rgba(0,0,0,.025); }
    .dark .cl-row:hover { background:rgba(255,255,255,.04); }
    .cl-bar { height:6px; border-radius:3px; background:#f3f4f6; overflow:hidden; }
    .dark .cl-bar { background:#374151; }
    .cl-bar-fill { height:100%; border-radius:3px; transition:width .5s ease; }
    .cl-podium-gold   { background:linear-gradient(180deg,#fef9c3,#fef08a); border-top:2px solid #fbbf24; }
    .dark .cl-podium-gold { background:linear-gradient(180deg,rgba(254,243,99,.12),rgba(253,224,71,.06)); border-top-color:#d97706; }
    .cl-podium-silver { background:linear-gradient(180deg,#f1f5f9,#e2e8f0); border-top:2px solid #94a3b8; }
    .dark .cl-podium-silver { background:linear-gradient(180deg,rgba(100,116,139,.15),rgba(100,116,139,.07)); border-top-color:#64748b; }
    .cl-podium-bronze { background:linear-gradient(180deg,#fef3c7,#fde68a); border-top:2px solid #d97706; }
    .dark .cl-podium-bronze { background:linear-gradient(180deg,rgba(180,83,9,.12),rgba(180,83,9,.06)); border-top-color:#b45309; }
    .cl-stat-card { transition:box-shadow .15s; }
    .cl-stat-card:hover { box-shadow:0 4px 14px rgba(0,0,0,.08); }
</style>

@php
    $stats      = $this->getStatsTop();
    $agentCls   = \App\Services\ClassificationService::AGENT_CLASSES;
    $citCls     = \App\Services\ClassificationService::CITOYEN_CLASSES;

    $badgeMap = [
        'emerald' => 'background:#d1fae5;color:#065f46',
        'blue'    => 'background:#dbeafe;color:#1e40af',
        'amber'   => 'background:#fef3c7;color:#92400e',
        'violet'  => 'background:#ede9fe;color:#5b21b6',
        'red'     => 'background:#fee2e2;color:#991b1b',
        'slate'   => 'background:#f1f5f9;color:#475569',
    ];
    $badgeDark = [
        'emerald' => 'color:#6ee7b7',
        'blue'    => 'color:#93c5fd',
        'amber'   => 'color:#fcd34d',
        'violet'  => 'color:#c4b5fd',
        'red'     => 'color:#fca5a5',
        'slate'   => 'color:#94a3b8',
    ];
@endphp

{{-- ── Stats ── --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
    @php
        $cards = [
            ['l'=>'Total agents',   'v'=>$stats['total_agents'],   's'=>$stats['actifs'].' actifs',  'dot'=>'#3b82f6'],
            ['l'=>'Disponibles',    'v'=>$stats['disponibles'],    's'=>'sur '.$stats['actifs'],     'dot'=>'#10b981'],
            ['l'=>'Note moyenne',   'v'=>number_format($stats['moy_taux'],1), 's'=>'/ 5.0',         'dot'=>'#f59e0b'],
            ['l'=>'Citoyens',       'v'=>$stats['total_citoyens'], 's'=>'inscrits',                  'dot'=>'#8b5cf6'],
            ['l'=>'Missions',       'v'=>\App\Models\Attribution::count(), 's'=>'attributions',      'dot'=>'#06b6d4'],
        ];
    @endphp
    @foreach($cards as $c)
        <div class="cl-stat-card rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 px-4 py-3">
            <div class="flex items-center gap-2 mb-1">
                <span style="width:8px;height:8px;border-radius:50%;background:{{ $c['dot'] }};display:inline-block;flex-shrink:0"></span>
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ $c['l'] }}</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $c['v'] }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $c['s'] }}</p>
        </div>
    @endforeach
</div>

{{-- ── Tabs ── --}}
<div class="flex items-center gap-1 mb-6 bg-gray-100 dark:bg-gray-800 rounded-xl p-1 w-fit">
    <button wire:click="$set('onglet','agents')"
        class="px-5 py-2 text-sm font-semibold rounded-lg transition-all {{ $onglet==='agents' ? 'cl-tab-active text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
        Agents
    </button>
    <button wire:click="$set('onglet','citoyens')"
        class="px-5 py-2 text-sm font-semibold rounded-lg transition-all {{ $onglet==='citoyens' ? 'cl-tab-active text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
        Citoyens
    </button>
</div>

{{-- ═══════ AGENTS ═══════ --}}
@if($onglet === 'agents')
    @php
        $agents     = $this->getAgentsRanking();
        $clStats    = $this->getAgentClassStats();
        $total      = max(1, array_sum($clStats));
        $top3       = $agents->take(3);
        $barColors  = ['emerald'=>'#10b981','blue'=>'#3b82f6','amber'=>'#f59e0b','violet'=>'#8b5cf6','red'=>'#ef4444','slate'=>'#94a3b8'];
    @endphp

    {{-- Répartition classes --}}
    <div class="mb-6">
        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Répartition classes</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
            @foreach($agentCls as $key => $cls)
                @php $cnt=$clStats[$key]??0; $pct=round($cnt/$total*100); $bc=$barColors[$cls['color']]??'#94a3b8'; @endphp
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-3 text-center">
                    <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 leading-tight">{{ $cls['label'] }}</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $cnt }}</p>
                    <div class="cl-bar mt-2">
                        <div class="cl-bar-fill" style="width:{{ $pct }}%;background:{{ $bc }}"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">{{ $pct }}%</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Podium --}}
    @if($top3->count() >= 3)
        <div class="mb-6">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Podium</p>
            <div class="flex items-end justify-center gap-3">
                @php
                    $podOrder = [
                        ['a'=>$top3[1],'rank'=>2,'h'=>'84px', 'cls'=>'cl-podium-silver','medal'=>'#94a3b8','ring'=>'#cbd5e1','txt'=>'2ème'],
                        ['a'=>$top3[0],'rank'=>1,'h'=>'110px','cls'=>'cl-podium-gold',  'medal'=>'#f59e0b','ring'=>'#fde68a','txt'=>'1er'],
                        ['a'=>$top3[2],'rank'=>3,'h'=>'68px', 'cls'=>'cl-podium-bronze','medal'=>'#d97706','ring'=>'#fcd34d','txt'=>'3ème'],
                    ];
                @endphp
                @foreach($podOrder as $item)
                    @php $a=$item['a']; $c=$a->classification; $bc=$badgeMap[$c['color']]??$badgeMap['slate']; @endphp
                    <div class="flex flex-col items-center gap-1.5 flex-1" style="max-width:180px">
                        {{-- avatar --}}
                        <div style="width:52px;height:52px;border-radius:50%;border:3px solid {{ $item['ring'] }};overflow:hidden;background:#e5e7eb;display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;font-weight:700;box-shadow:0 2px 10px rgba(0,0,0,.12)">
                            @if($a->photo)<img src="{{ asset('storage/'.$a->photo) }}" style="width:100%;height:100%;object-fit:cover" alt="">
                            @else{{ strtoupper(substr($a->nom,0,1)) }}@endif
                        </div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white text-center truncate w-full px-1">{{ $a->nom }}</p>
                        <p class="text-xs text-gray-500 text-center truncate w-full px-1">{{ $a->zone }}</p>
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold" style="{{ $bc }}">{{ $c['label'] }}</span>
                        {{-- podium bar --}}
                        <div class="{{ $item['cls'] }} w-full rounded-t-lg flex flex-col items-center pt-2 pb-2 gap-1" style="height:{{ $item['h'] }}">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold text-white" style="background:{{ $item['medal'] }}">{{ $item['rank'] }}</span>
                            <div class="flex gap-2 text-xs text-center">
                                <div><p class="font-bold text-gray-800 dark:text-gray-200">{{ $a->missions_terminees }}</p><p class="text-gray-500" style="font-size:10px">Miss.</p></div>
                                <div><p class="font-bold {{ $a->note_moyenne>=4?'text-emerald-600':'text-gray-800 dark:text-gray-200' }}">{{ $a->note_moyenne>0?$a->note_moyenne:'-' }}</p><p class="text-gray-500" style="font-size:10px">Note</p></div>
                                <div><p class="font-bold text-gray-800 dark:text-gray-200">{{ $a->taux_completion }}%</p><p class="text-gray-500" style="font-size:10px">Taux</p></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="flex flex-wrap items-center gap-2 mb-3">
        <div class="relative flex-1" style="max-width:240px">
            <input wire:model.live.debounce.300ms="recherche" type="text" placeholder="Rechercher un agent..."
                class="w-full pl-7 pr-3 py-1.5 text-xs rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-all">
            <svg style="width:11px;height:11px;position:absolute;left:9px;top:50%;transform:translateY(-50%);color:#9ca3af" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
            @foreach(['missions'=>'Missions','note'=>'Note','taux'=>'Taux','reaction'=>'Réactivité'] as $v=>$l)
                <button wire:click="$set('triAgent','{{ $v }}')"
                    class="px-3 py-1.5 rounded-md text-xs font-semibold transition-all {{ $triAgent===$v ? 'cl-sort-active' : 'text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700' }}">{{ $l }}</button>
            @endforeach
        </div>
    </div>

    {{-- Table --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-3 py-2.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase w-10">#</th>
                        <th class="px-3 py-2.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Agent</th>
                        <th class="px-3 py-2.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Classe</th>
                        <th class="px-3 py-2.5 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Missions</th>
                        <th class="px-3 py-2.5 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase" style="min-width:110px">Taux</th>
                        <th class="px-3 py-2.5 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Note</th>
                        <th class="px-3 py-2.5 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Réact.</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($agents as $i => $a)
                        @php $c=$a->classification; $rank=$i+1; $bc=$badgeMap[$c['color']]??$badgeMap['slate'];
                            $medalBg = $rank===1?'#f59e0b':($rank===2?'#94a3b8':($rank===3?'#d97706':'#f3f4f6'));
                            $medalTx = $rank<=3?'#fff':'#6b7280';
                        @endphp
                        <tr class="cl-row">
                            <td class="px-3 py-2.5 text-center">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;background:{{ $medalBg }};color:{{ $medalTx }};font-size:11px;font-weight:700">{{ $rank }}</span>
                            </td>
                            <td class="px-3 py-2.5">
                                <div class="flex items-center gap-2.5">
                                    <div style="width:32px;height:32px;border-radius:8px;background:#e5e7eb;flex-shrink:0;overflow:hidden;display:flex;align-items:center;justify-content:center;color:#6b7280;font-size:12px;font-weight:700">
                                        @if($a->photo)<img src="{{ asset('storage/'.$a->photo) }}" style="width:100%;height:100%;object-fit:cover" alt="">@else{{ strtoupper(substr($a->nom,0,1)) }}@endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $a->nom }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $a->zone }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-2.5">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold" style="{{ $bc }}">{{ $c['label'] }}</span>
                            </td>
                            <td class="px-3 py-2.5 text-center">
                                <span class="font-bold text-gray-900 dark:text-white">{{ $a->missions_terminees }}</span>
                                <span class="text-xs text-gray-400">/{{ $a->total_missions }}</span>
                            </td>
                            <td class="px-3 py-2.5">
                                <div class="flex items-center gap-2 justify-center">
                                    <div class="cl-bar" style="width:64px">
                                        <div class="cl-bar-fill" style="width:{{ $a->taux_completion }}%;background:{{ $a->taux_completion>=70?'#10b981':($a->taux_completion>=40?'#f59e0b':'#ef4444') }}"></div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300" style="min-width:30px;text-align:right">{{ $a->taux_completion }}%</span>
                                </div>
                            </td>
                            <td class="px-3 py-2.5 text-center">
                                @if($a->note_moyenne>0)
                                    <span class="font-bold text-sm" style="color:{{ $a->note_moyenne>=4?'#10b981':($a->note_moyenne>=2.5?'#f59e0b':'#94a3b8') }}">{{ number_format($a->note_moyenne,1) }}</span>
                                @else<span class="text-gray-300 dark:text-gray-600">—</span>@endif
                            </td>
                            <td class="px-3 py-2.5 text-center">
                                @if($a->avg_reaction>0)
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold" style="{{ $a->avg_reaction<=4?'background:#d1fae5;color:#065f46':($a->avg_reaction<=12?'background:#fef3c7;color:#92400e':'background:#f1f5f9;color:#475569') }}">{{ $a->avg_reaction }}h</span>
                                @else<span class="text-gray-300 dark:text-gray-600">—</span>@endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-12 text-center text-sm text-gray-400 dark:text-gray-500">Aucun agent trouvé</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

{{-- ═══════ CITOYENS ═══════ --}}
@if($onglet === 'citoyens')
    @php
        $cits    = $this->getCitoyensRanking();
        $clStats = $this->getCitoyenClassStats();
        $total   = max(1, array_sum($clStats));
        $top3    = $cits->take(3);
        $barColors = ['emerald'=>'#10b981','blue'=>'#3b82f6','amber'=>'#f59e0b','violet'=>'#8b5cf6','red'=>'#ef4444','slate'=>'#94a3b8'];
    @endphp

    {{-- Répartition --}}
    <div class="mb-6">
        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Répartition classes citoyens</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
            @foreach($citCls as $key => $cls)
                @php $cnt=$clStats[$key]??0; $pct=round($cnt/$total*100); $bc=$barColors[$cls['color']]??'#94a3b8'; @endphp
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-3 text-center">
                    <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 leading-tight">{{ $cls['label'] }}</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $cnt }}</p>
                    <div class="cl-bar mt-2">
                        <div class="cl-bar-fill" style="width:{{ $pct }}%;background:{{ $bc }}"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">{{ $pct }}%</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Podium Citoyens --}}
    @if($top3->count() >= 3)
        <div class="mb-6">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Podium citoyens</p>
            <div class="flex items-end justify-center gap-3">
                @php
                    $podC = [
                        ['u'=>$top3[1],'rank'=>2,'h'=>'84px', 'cls'=>'cl-podium-silver','medal'=>'#94a3b8','ring'=>'#cbd5e1','txt'=>'2ème'],
                        ['u'=>$top3[0],'rank'=>1,'h'=>'110px','cls'=>'cl-podium-gold',  'medal'=>'#f59e0b','ring'=>'#fde68a','txt'=>'1er'],
                        ['u'=>$top3[2],'rank'=>3,'h'=>'68px', 'cls'=>'cl-podium-bronze','medal'=>'#d97706','ring'=>'#fcd34d','txt'=>'3ème'],
                    ];
                @endphp
                @foreach($podC as $item)
                    @php $u=$item['u']; $c=$u->classification; $bc=$badgeMap[$c['color']]??$badgeMap['slate']; @endphp
                    <div class="flex flex-col items-center gap-1.5 flex-1" style="max-width:180px">
                        <div style="width:52px;height:52px;border-radius:50%;border:3px solid {{ $item['ring'] }};overflow:hidden;background:linear-gradient(135deg,#6ee7b7,#34d399);display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;font-weight:700;box-shadow:0 2px 10px rgba(0,0,0,.12)">
                            @if($u->photo)<img src="{{ asset('storage/'.$u->photo) }}" style="width:100%;height:100%;object-fit:cover" alt="">
                            @else{{ strtoupper(substr($u->nom,0,1)) }}@endif
                        </div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white text-center truncate w-full px-1">{{ $u->nom }}</p>
                        <p class="text-xs text-gray-500 text-center truncate w-full px-1">{{ $u->email }}</p>
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold" style="{{ $bc }}">{{ $c['label'] }}</span>
                        <div class="{{ $item['cls'] }} w-full rounded-t-lg flex flex-col items-center pt-2 pb-2 gap-1" style="height:{{ $item['h'] }}">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold text-white" style="background:{{ $item['medal'] }}">{{ $item['rank'] }}</span>
                            <div class="flex gap-2 text-xs text-center">
                                <div><p class="font-bold text-gray-800 dark:text-gray-200">{{ $u->signalements }}</p><p class="text-gray-500" style="font-size:10px">Signaux</p></div>
                                <div><p class="font-bold text-emerald-600">{{ $u->termines }}</p><p class="text-gray-500" style="font-size:10px">Résolus</p></div>
                                <div><p class="font-bold text-gray-800 dark:text-gray-200">{{ $u->taux_validation }}%</p><p class="text-gray-500" style="font-size:10px">Taux</p></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="flex flex-wrap items-center gap-2 mb-3">
        <div class="relative flex-1" style="max-width:240px">
            <input wire:model.live.debounce.300ms="recherche" type="text" placeholder="Rechercher un citoyen..."
                class="w-full pl-7 pr-3 py-1.5 text-xs rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-all">
            <svg style="width:11px;height:11px;position:absolute;left:9px;top:50%;transform:translateY(-50%);color:#9ca3af" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
            @foreach(['signalements'=>'Signalements','evaluations'=>'Évaluations','taux'=>'Taux','engagement'=>'Engagement'] as $v=>$l)
                <button wire:click="$set('triCitoyen','{{ $v }}')"
                    class="px-3 py-1.5 rounded-md text-xs font-semibold transition-all {{ $triCitoyen===$v ? 'cl-sort-active' : 'text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700' }}">{{ $l }}</button>
            @endforeach
        </div>
    </div>

    {{-- Table --}}
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-3 py-2.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase w-10">#</th>
                        <th class="px-3 py-2.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Citoyen</th>
                        <th class="px-3 py-2.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Classe</th>
                        <th class="px-3 py-2.5 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Signaux</th>
                        <th class="px-3 py-2.5 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Résolus</th>
                        <th class="px-3 py-2.5 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Rejetés</th>
                        <th class="px-3 py-2.5 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase" style="min-width:110px">Taux valid.</th>
                        <th class="px-3 py-2.5 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Éval.</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($cits as $i => $u)
                        @php $c=$u->classification; $rank=$i+1; $bc=$badgeMap[$c['color']]??$badgeMap['slate'];
                            $medalBg = $rank===1?'#f59e0b':($rank===2?'#94a3b8':($rank===3?'#d97706':'#f3f4f6'));
                            $medalTx = $rank<=3?'#fff':'#6b7280';
                        @endphp
                        <tr class="cl-row">
                            <td class="px-3 py-2.5 text-center">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;background:{{ $medalBg }};color:{{ $medalTx }};font-size:11px;font-weight:700">{{ $rank }}</span>
                            </td>
                            <td class="px-3 py-2.5">
                                <div class="flex items-center gap-2.5">
                                    <div style="width:32px;height:32px;border-radius:8px;flex-shrink:0;overflow:hidden;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;background:linear-gradient(135deg,#6ee7b7,#34d399)">
                                        @if($u->photo)<img src="{{ asset('storage/'.$u->photo) }}" style="width:100%;height:100%;object-fit:cover" alt="">@else{{ strtoupper(substr($u->nom,0,1)) }}@endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $u->nom }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-2.5">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold" style="{{ $bc }}">{{ $c['label'] }}</span>
                            </td>
                            <td class="px-3 py-2.5 text-center font-bold text-gray-900 dark:text-white">{{ $u->signalements }}</td>
                            <td class="px-3 py-2.5 text-center font-bold" style="color:#10b981">{{ $u->termines }}</td>
                            <td class="px-3 py-2.5 text-center font-bold" style="color:{{ $u->rejetes>0?'#ef4444':'#d1d5db' }}">{{ $u->rejetes>0?$u->rejetes:'—' }}</td>
                            <td class="px-3 py-2.5">
                                <div class="flex items-center gap-2 justify-center">
                                    <div class="cl-bar" style="width:64px">
                                        <div class="cl-bar-fill" style="width:{{ $u->taux_validation }}%;background:{{ $u->taux_validation>=70?'#10b981':($u->taux_validation>=40?'#f59e0b':'#ef4444') }}"></div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300" style="min-width:30px;text-align:right">{{ $u->taux_validation }}%</span>
                                </div>
                            </td>
                            <td class="px-3 py-2.5 text-center">
                                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold" style="{{ $u->evaluations>0?'background:#ede9fe;color:#5b21b6':'background:#f1f5f9;color:#94a3b8' }}">{{ $u->evaluations>0?$u->evaluations:'—' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-12 text-center text-sm text-gray-400 dark:text-gray-500">Aucun citoyen trouvé</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

{{-- ── Règles ── --}}
<div class="mt-6 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Règles — {{ $onglet==='agents'?'Agents':'Citoyens' }}</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
        @foreach(($onglet==='agents'?$agentCls:$citCls) as $cls)
            <div class="flex items-start gap-2 px-3 py-2 rounded-lg" style="{{ $badgeMap[$cls['color']]??$badgeMap['slate'] }}">
                <span style="width:6px;height:6px;border-radius:50%;background:{{ $barColors[$cls['color']]??'#94a3b8' }};margin-top:5px;flex-shrink:0;display:inline-block"></span>
                <div>
                    <p class="text-xs font-bold">{{ $cls['label'] }}</p>
                    <p class="text-xs opacity-75 mt-0.5">{{ $cls['desc'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>

</x-filament-panels::page>
