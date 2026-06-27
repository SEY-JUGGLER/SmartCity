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
</style>

@php
    $stats      = $this->getStatsTop();
    $agentCls   = \App\Services\ClassificationService::AGENT_CLASSES;
    $citCls     = \App\Services\ClassificationService::CITOYEN_CLASSES;

    $badgeStyle = fn($c) => match($c) {
        'emerald' => 'background:#d1fae5;color:#065f46',
        'blue'    => 'background:#dbeafe;color:#1e40af',
        'amber'   => 'background:#fef3c7;color:#92400e',
        'violet'  => 'background:#ede9fe;color:#5b21b6',
        'red'     => 'background:#fee2e2;color:#991b1b',
        default   => 'background:#f1f5f9;color:#475569',
    };
    $barColors  = ['emerald'=>'#10b981','blue'=>'#3b82f6','amber'=>'#f59e0b','violet'=>'#8b5cf6','red'=>'#ef4444','slate'=>'#94a3b8'];
    $cardHex   = ['#6366f1','#10b981','#f59e0b','#8b5cf6','#06b6d4'];
    $cardHex40 = ['#818cf8','#34d399','#fbbf24','#a78bfa','#22d3ee'];
@endphp

{{-- ── Stats Cards ── --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
    @php
        $icons = [
            'heroicon-m-user-group', 'heroicon-m-check-circle',
            'heroicon-m-star', 'heroicon-m-users', 'heroicon-m-flag'
        ];
        $colors = ['primary','success','warning','violet','cyan'];
        $cards = [
            ['l'=>'Total agents',   'v'=>$stats['total_agents'],   's'=>$stats['actifs'].' actifs'],
            ['l'=>'Disponibles',    'v'=>$stats['disponibles'],    's'=>'sur '.$stats['actifs'].' agents'],
            ['l'=>'Note moyenne',   'v'=>number_format($stats['moy_taux'],1), 's'=>'/ 5.0'],
            ['l'=>'Citoyens',       'v'=>$stats['total_citoyens'], 's'=>'inscrits'],
            ['l'=>'Missions',       'v'=>\App\Models\Attribution::count(), 's'=>'attributions'],
        ];
    @endphp
    @foreach($cards as $i => $c)
        <div class="relative overflow-hidden rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 shadow-sm transition-shadow duration-150 hover:shadow-md">
            <div class="flex items-start justify-between gap-2 mb-1">
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $c['l'] }}</p>
                    <p class="text-3xl font-black text-gray-900 dark:text-white leading-none mt-1">{{ $c['v'] }}</p>
                    <p class="text-[10px] text-gray-400 mt-1.5">{{ $c['s'] }}</p>
                </div>
                <div class="flex items-center justify-center rounded-xl flex-shrink-0" style="width:40px;height:40px;background:color-mix(in srgb, {{ $cardHex[$i] }} 10%, transparent)">
                    <x-dynamic-component :component="$icons[$i]" style="width:18px;height:18px;color:{{ $cardHex[$i] }}" />
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-0.5" style="background:color-mix(in srgb, {{ $cardHex40[$i] }} 20%, transparent)"></div>
        </div>
    @endforeach
</div>

{{-- ── Tabs ── --}}
<div class="flex items-center gap-1 mb-6 bg-gray-100 dark:bg-gray-800 rounded-xl p-1 w-fit">
    <button wire:click="$set('onglet','agents')"
        class="px-5 py-2 text-sm font-semibold rounded-lg transition-all {{ $onglet==='agents' ? 'cl-tab-active text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
        <span class="flex items-center gap-2"><x-heroicon-m-user-group style="width:16px;height:16px" /> Agents</span>
    </button>
    <button wire:click="$set('onglet','citoyens')"
        class="px-5 py-2 text-sm font-semibold rounded-lg transition-all {{ $onglet==='citoyens' ? 'cl-tab-active text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
        <span class="flex items-center gap-2"><x-heroicon-m-users style="width:16px;height:16px" /> Citoyens</span>
    </button>
</div>

{{-- ═══════ AGENTS ═══════ --}}
@if($onglet === 'agents')
    @php
        $agents     = $this->getAgentsRanking();
        $clStats    = $this->getAgentClassStats();
        $total      = max(1, array_sum($clStats));
        $top3       = $agents->take(3);
    @endphp

    {{-- Répartition classes --}}
    <div class="mb-6">
        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Répartition classes agents</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            @foreach($agentCls as $key => $cls)
                @php $cnt=$clStats[$key]??0; $pct=round($cnt/$total*100); $bc=$barColors[$cls['color']]??'#94a3b8'; @endphp
                <div class="relative overflow-hidden rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 text-center shadow-sm">
                    <p class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $cls['emoji'] }} {{ $cls['label'] }}</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white mt-1.5" style="color:{{ $bc }}">{{ $cnt }}</p>
                    <div class="mt-3 h-1.5 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-700" style="width:{{ $pct }}%;background:{{ $bc }}"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1 font-semibold">{{ $pct }}%</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Podium --}}
    @if($top3->count() >= 3)
        <div class="mb-6">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Podium</p>
            <div class="flex items-end justify-center gap-4">
                @php
                    $podOrder = [
                        ['a'=>$top3[1],'rank'=>2,'h'=>'84px', 'cls'=>'cl-podium-silver','badgeCls'=>'bg-slate-400/20 text-slate-600 dark:text-slate-300','ring'=>'#cbd5e1','icon'=>'heroicon-m-chevron-up'],
                        ['a'=>$top3[0],'rank'=>1,'h'=>'110px','cls'=>'cl-podium-gold',  'badgeCls'=>'bg-amber-400/20 text-amber-700 dark:text-amber-300','ring'=>'#fbbf24','icon'=>'heroicon-m-crown'],
                        ['a'=>$top3[2],'rank'=>3,'h'=>'68px', 'cls'=>'cl-podium-bronze','badgeCls'=>'bg-orange-400/20 text-orange-700 dark:text-orange-300','ring'=>'#d97706','icon'=>'heroicon-m-chevron-down'],
                    ];
                @endphp
                @foreach($podOrder as $item)
                    @php $a=$item['a']; $c=$a->classification; @endphp
                    <div class="flex flex-col items-center gap-2 flex-1" style="max-width:200px">
                        <div class="relative">
                            <div style="width:56px;height:56px;border-radius:50%;border:3px solid {{ $item['ring'] }};overflow:hidden;background:linear-gradient(135deg,#e5e7eb,#d1d5db);display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;font-weight:700;box-shadow:0 4px 12px rgba(0,0,0,.12)">
                                @if($a->photo)<img src="{{ asset('storage/'.$a->photo) }}" style="width:100%;height:100%;object-fit:cover" alt="">
                                @else{{ strtoupper(substr($a->nom,0,1)) }}@endif
                            </div>
                            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-black text-white shadow-md" style="background:{{ match($item['rank']){1=>'#f59e0b',2=>'#94a3b8',3=>'#d97706'} }}">{{ $item['rank'] }}</span>
                        </div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white text-center truncate w-full px-1">{{ $a->nom }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center truncate w-full px-1">{{ $a->zone }}</p>
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $item['badgeCls'] }}">{{ $c['label'] }}</span>
                        <div class="{{ $item['cls'] }} w-full rounded-xl flex flex-col items-center justify-center pt-2 pb-2 px-3 gap-1.5 shadow-sm" style="height:{{ $item['h'] }}">
                            <div class="flex gap-3 text-center">
                                <div><p class="text-sm font-black text-gray-800 dark:text-gray-200">{{ $a->missions_terminees }}</p><p class="text-gray-500" style="font-size:10px">Missions</p></div>
                                <div class="w-px bg-gray-300/50 dark:bg-gray-600/50"></div>
                                <div><p class="text-sm font-black {{ $a->note_moyenne>=4?'text-emerald-600':'text-gray-800 dark:text-gray-200' }}">{{ $a->note_moyenne>0?$a->note_moyenne:'-' }}</p><p class="text-gray-500" style="font-size:10px">Note</p></div>
                                <div class="w-px bg-gray-300/50 dark:bg-gray-600/50"></div>
                                <div><p class="text-sm font-black text-gray-800 dark:text-gray-200">{{ $a->taux_completion }}%</p><p class="text-gray-500" style="font-size:10px">Taux</p></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div class="relative" style="min-width:200px;max-width:260px">
            <input wire:model.live.debounce.300ms="recherche" type="text" placeholder="Rechercher un agent..."
                class="w-full pl-8 pr-3 py-2 text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 transition-all">
            <svg style="width:12px;height:12px;position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#9ca3af" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-xl p-1">
            @foreach(['missions'=>'Missions','note'=>'Note','taux'=>'Taux','reaction'=>'Réactivité'] as $v=>$l)
                <button wire:click="$set('triAgent','{{ $v }}')"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $triAgent===$v ? 'cl-sort-active' : 'text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700' }}">{{ $l }}</button>
            @endforeach
        </div>
    </div>

    {{-- Table --}}
    <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50">
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-10">#</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Agent</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Classe</th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Missions</th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider" style="min-width:120px">Taux</th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Note</th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Réactivité</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800/60">
                    @forelse($agents as $i => $a)
                        @php $c=$a->classification; $rank=$i+1;
                            $medalBg = $rank===1?'#f59e0b':($rank===2?'#94a3b8':($rank===3?'#d97706':'#f3f4f6'));
                            $medalTx = $rank<=3?'#fff':'#6b7280';
                        @endphp
                        <tr class="cl-row transition-colors duration-100">
                            <td class="px-4 py-3 text-center">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:50%;background:{{ $medalBg }};color:{{ $medalTx }};font-size:11px;font-weight:800">{{ $rank }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#e5e7eb,#d1d5db);flex-shrink:0;overflow:hidden;display:flex;align-items:center;justify-content:center;color:#6b7280;font-size:13px;font-weight:700;box-shadow:0 2px 4px rgba(0,0,0,.06)">
                                        @if($a->photo)<img src="{{ asset('storage/'.$a->photo) }}" style="width:100%;height:100%;object-fit:cover" alt="">@else{{ strtoupper(substr($a->nom,0,1)) }}@endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $a->nom }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $a->zone }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-semibold" style="{{ $badgeStyle($c['color']) }}">{{ $c['label'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-black text-gray-900 dark:text-white text-sm">{{ $a->missions_terminees }}</span>
                                <span class="text-xs text-gray-400 ml-0.5">/{{ $a->total_missions }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2.5 justify-center">
                                    <div style="width:72px;height:6px;border-radius:3px;background:#f3f4f6;overflow:hidden" class="dark:bg-gray-700">
                                        <div style="height:100%;border-radius:3px;transition:width .5s ease;width:{{ $a->taux_completion }}%;background:{{ $a->taux_completion>=70?'#10b981':($a->taux_completion>=40?'#f59e0b':'#ef4444') }}"></div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300 min-w-[32px] text-right">{{ $a->taux_completion }}%</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($a->note_moyenne>0)
                                    <span class="font-black text-sm" style="color:{{ $a->note_moyenne>=4?'#10b981':($a->note_moyenne>=2.5?'#f59e0b':'#94a3b8') }}">{{ number_format($a->note_moyenne,1) }}</span>
                                @else<span class="text-gray-300 dark:text-gray-600">—</span>@endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($a->avg_reaction>0)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-semibold" style="{{ $a->avg_reaction<=4?'background:#d1fae5;color:#065f46':($a->avg_reaction<=12?'background:#fef3c7;color:#92400e':'background:#f1f5f9;color:#475569') }}">{{ $a->avg_reaction }}h</span>
                                @else<span class="text-gray-300 dark:text-gray-600">—</span>@endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-16 text-center text-sm text-gray-400">Aucun agent trouvé</td></tr>
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
    @endphp

    {{-- Répartition --}}
    <div class="mb-6">
        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Répartition classes citoyens</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            @foreach($citCls as $key => $cls)
                @php $cnt=$clStats[$key]??0; $pct=round($cnt/$total*100); $bc=$barColors[$cls['color']]??'#94a3b8'; @endphp
                <div class="relative overflow-hidden rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 text-center shadow-sm">
                    <p class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $cls['emoji'] }} {{ $cls['label'] }}</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white mt-1.5" style="color:{{ $bc }}">{{ $cnt }}</p>
                    <div class="mt-3 h-1.5 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-700" style="width:{{ $pct }}%;background:{{ $bc }}"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1 font-semibold">{{ $pct }}%</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Podium --}}
    @if($top3->count() >= 3)
        <div class="mb-6">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Podium citoyens</p>
            <div class="flex items-end justify-center gap-4">
                @php
                    $podC = [
                        ['u'=>$top3[1],'rank'=>2,'h'=>'84px', 'cls'=>'cl-podium-silver','badgeCls'=>'bg-slate-400/20 text-slate-600 dark:text-slate-300','ring'=>'#cbd5e1'],
                        ['u'=>$top3[0],'rank'=>1,'h'=>'110px','cls'=>'cl-podium-gold',  'badgeCls'=>'bg-amber-400/20 text-amber-700 dark:text-amber-300','ring'=>'#fbbf24'],
                        ['u'=>$top3[2],'rank'=>3,'h'=>'68px', 'cls'=>'cl-podium-bronze','badgeCls'=>'bg-orange-400/20 text-orange-700 dark:text-orange-300','ring'=>'#d97706'],
                    ];
                @endphp
                @foreach($podC as $item)
                    @php $u=$item['u']; $c=$u->classification; @endphp
                    <div class="flex flex-col items-center gap-2 flex-1" style="max-width:200px">
                        <div class="relative">
                            <div style="width:56px;height:56px;border-radius:50%;border:3px solid {{ $item['ring'] }};overflow:hidden;background:linear-gradient(135deg,#6ee7b7,#34d399);display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;font-weight:700;box-shadow:0 4px 12px rgba(0,0,0,.12)">
                                @if($u->photo)<img src="{{ asset('storage/'.$u->photo) }}" style="width:100%;height:100%;object-fit:cover" alt="">
                                @else{{ strtoupper(substr($u->nom,0,1)) }}@endif
                            </div>
                            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-black text-white shadow-md" style="background:{{ match($item['rank']){1=>'#f59e0b',2=>'#94a3b8',3=>'#d97706'} }}">{{ $item['rank'] }}</span>
                        </div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white text-center truncate w-full px-1">{{ $u->nom }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center truncate w-full px-1">{{ $u->email }}</p>
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $item['badgeCls'] }}">{{ $c['label'] }}</span>
                        <div class="{{ $item['cls'] }} w-full rounded-xl flex flex-col items-center justify-center pt-2 pb-2 px-3 gap-1.5 shadow-sm" style="height:{{ $item['h'] }}">
                            <div class="flex gap-3 text-center">
                                <div><p class="text-sm font-black text-gray-800 dark:text-gray-200">{{ $u->signalements }}</p><p class="text-gray-500" style="font-size:10px">Signaux</p></div>
                                <div class="w-px bg-gray-300/50 dark:bg-gray-600/50"></div>
                                <div><p class="text-sm font-black text-emerald-600">{{ $u->termines }}</p><p class="text-gray-500" style="font-size:10px">Résolus</p></div>
                                <div class="w-px bg-gray-300/50 dark:bg-gray-600/50"></div>
                                <div><p class="text-sm font-black text-gray-800 dark:text-gray-200">{{ $u->taux_validation }}%</p><p class="text-gray-500" style="font-size:10px">Taux</p></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div class="relative" style="min-width:200px;max-width:260px">
            <input wire:model.live.debounce.300ms="recherche" type="text" placeholder="Rechercher un citoyen..."
                class="w-full pl-8 pr-3 py-2 text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400/20 transition-all">
            <svg style="width:12px;height:12px;position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#9ca3af" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-xl p-1">
            @foreach(['signalements'=>'Signalements','evaluations'=>'Évaluations','taux'=>'Taux','engagement'=>'Engagement'] as $v=>$l)
                <button wire:click="$set('triCitoyen','{{ $v }}')"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $triCitoyen===$v ? 'cl-sort-active' : 'text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700' }}">{{ $l }}</button>
            @endforeach
        </div>
    </div>

    {{-- Table --}}
    <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50">
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-10">#</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Citoyen</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Classe</th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Signaux</th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Résolus</th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rejetés</th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider" style="min-width:120px">Taux valid.</th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Évaluations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800/60">
                    @forelse($cits as $i => $u)
                        @php $c=$u->classification; $rank=$i+1;
                            $medalBg = $rank===1?'#f59e0b':($rank===2?'#94a3b8':($rank===3?'#d97706':'#f3f4f6'));
                            $medalTx = $rank<=3?'#fff':'#6b7280';
                        @endphp
                        <tr class="cl-row transition-colors duration-100">
                            <td class="px-4 py-3 text-center">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:50%;background:{{ $medalBg }};color:{{ $medalTx }};font-size:11px;font-weight:800">{{ $rank }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div style="width:34px;height:34px;border-radius:10px;flex-shrink:0;overflow:hidden;display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700;background:linear-gradient(135deg,#6ee7b7,#34d399);box-shadow:0 2px 4px rgba(0,0,0,.06)">
                                        @if($u->photo)<img src="{{ asset('storage/'.$u->photo) }}" style="width:100%;height:100%;object-fit:cover" alt="">@else{{ strtoupper(substr($u->nom,0,1)) }}@endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $u->nom }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-semibold" style="{{ $badgeStyle($c['color']) }}">{{ $c['label'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-center font-black text-gray-900 dark:text-white text-sm">{{ $u->signalements }}</td>
                            <td class="px-4 py-3 text-center font-black text-emerald-600 text-sm">{{ $u->termines }}</td>
                            <td class="px-4 py-3 text-center font-black text-sm" style="color:{{ $u->rejetes>0?'#ef4444':'#d1d5db' }}">{{ $u->rejetes>0?$u->rejetes:'—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2.5 justify-center">
                                    <div style="width:72px;height:6px;border-radius:3px;background:#f3f4f6;overflow:hidden" class="dark:bg-gray-700">
                                        <div style="height:100%;border-radius:3px;transition:width .5s ease;width:{{ $u->taux_validation }}%;background:{{ $u->taux_validation>=70?'#10b981':($u->taux_validation>=40?'#f59e0b':'#ef4444') }}"></div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300 min-w-[32px] text-right">{{ $u->taux_validation }}%</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-bold" style="{{ $u->evaluations>0?'background:#ede9fe;color:#5b21b6':'background:#f1f5f9;color:#94a3b8' }}">{{ $u->evaluations>0?$u->evaluations:'—' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-16 text-center text-sm text-gray-400">Aucun citoyen trouvé</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

{{-- ── Règles ── --}}
<div class="mt-6">
    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Règles — {{ $onglet==='agents'?'Agents':'Citoyens' }}</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach(($onglet==='agents'?$agentCls:$citCls) as $cls)
            @php $bc=$barColors[$cls['color']]??'#94a3b8'; @endphp
            <div class="rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 p-4 shadow-sm transition-shadow hover:shadow-md" style="border-left:3px solid {{ $bc }}">
                <div class="flex items-start gap-3">
                    <div class="flex items-center justify-center rounded-xl flex-shrink-0" style="width:36px;height:36px;background:color-mix(in srgb, {{ $bc }} 12%, transparent)">
                        <span style="font-size:16px">{{ $cls['emoji'] }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $cls['label'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">{{ $cls['desc'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

</x-filament-panels::page>
