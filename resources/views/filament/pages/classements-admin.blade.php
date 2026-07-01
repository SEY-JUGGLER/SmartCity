<x-filament-panels::page>

@php
    $stats = $this->getStatsTop();
    $agentCls = \App\Services\ClassificationService::AGENT_CLASSES;
    $citCls   = \App\Services\ClassificationService::CITOYEN_CLASSES;
@endphp

<div class="grid grid-cols-5 gap-2 mb-4">
    @foreach([
        ['v' => $stats['total_agents'], 'l' => 'Agents'],
        ['v' => $stats['disponibles'],  'l' => 'Disponibles'],
        ['v' => number_format($stats['moy_taux'],1), 'l' => 'Note moy.'],
        ['v' => $stats['total_citoyens'], 'l' => 'Citoyens'],
        ['v' => \App\Models\Attribution::count(), 'l' => 'Missions'],
    ] as $s)
    <div class="rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-2 text-center bg-white dark:bg-gray-900">
        <p class="text-base font-bold text-gray-900 dark:text-white">{{ $s['v'] }}</p>
        <p class="text-[10px] text-gray-500 dark:text-gray-400">{{ $s['l'] }}</p>
    </div>
    @endforeach
</div>

<div class="flex gap-1 mb-4 bg-gray-100 dark:bg-gray-800 rounded-lg p-0.5 w-fit">
    <button wire:click="$set('onglet','agents')"
        class="px-3 py-1.5 text-xs font-medium rounded-md transition-all {{ $onglet==='agents' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
        Agents
    </button>
    <button wire:click="$set('onglet','citoyens')"
        class="px-3 py-1.5 text-xs font-medium rounded-md transition-all {{ $onglet==='citoyens' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
        Citoyens
    </button>
</div>

@if($onglet === 'agents')
    @php $agents = $this->getAgentsRanking(); @endphp

    <div class="flex items-center justify-between gap-2 mb-3">
        <input wire:model.live.debounce.300ms="recherche" type="text" placeholder="Rechercher..."
            class="w-56 px-3 py-1.5 text-xs rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400/30">
        <div class="flex gap-0.5 bg-gray-100 dark:bg-gray-800 rounded-lg p-0.5">
            @foreach(['missions'=>'Missions','note'=>'Note','taux'=>'Taux','reaction'=>'Réac.'] as $v => $l)
            <button wire:click="$set('triAgent','{{ $v }}')"
                class="px-2 py-1 rounded-md text-[10px] font-medium transition-all {{ $triAgent===$v ? 'bg-indigo-500 text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700' }}">{{ $l }}</button>
            @endforeach
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                    <th class="px-3 py-2 text-left text-[10px] font-semibold text-gray-400 uppercase w-8">Rang</th>
                    <th class="px-3 py-2 text-left text-[10px] font-semibold text-gray-400 uppercase">Agent</th>
                    <th class="px-3 py-2 text-left text-[10px] font-semibold text-gray-400 uppercase">Classe</th>
                    <th class="px-3 py-2 text-center text-[10px] font-semibold text-gray-400 uppercase">Missions</th>
                    <th class="px-3 py-2 text-center text-[10px] font-semibold text-gray-400 uppercase">Taux</th>
                    <th class="px-3 py-2 text-center text-[10px] font-semibold text-gray-400 uppercase">Note</th>
                    <th class="px-3 py-2 text-center text-[10px] font-semibold text-gray-400 uppercase">Réac.</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($agents as $i => $a)
                @php
                    $rank = $i + 1;
                    $rankBg = $rank === 1 ? 'bg-amber-400 text-white' : ($rank === 2 ? 'bg-gray-400 text-white' : ($rank === 3 ? 'bg-orange-400 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-400'));
                    $cc = $a->classification['color'] ?? 'slate';
                    $badgeCls = ['emerald'=>'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300','blue'=>'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300','amber'=>'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300','violet'=>'bg-violet-50 text-violet-700 dark:bg-violet-900/20 dark:text-violet-300','red'=>'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300','slate'=>'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400'][$cc] ?? 'bg-gray-100 text-gray-500';
                    $tauxBar = $a->taux_completion >= 70 ? 'bg-emerald-500' : ($a->taux_completion >= 40 ? 'bg-amber-500' : 'bg-red-500');
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                    <td class="px-3 py-2.5 text-center">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded text-[9px] font-bold {{ $rankBg }}">{{ $rank }}</span>
                    </td>
                    <td class="px-3 py-2.5">
                        <span class="text-xs font-medium text-gray-900 dark:text-white">{{ $a->nom }}</span>
                        <span class="text-[10px] text-gray-400 ml-1.5">{{ $a->zone }}</span>
                    </td>
                    <td class="px-3 py-2.5">
                        <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-semibold {{ $badgeCls }}">{{ $a->classification['label'] ?? '—' }}</span>
                    </td>
                    <td class="px-3 py-2.5 text-center text-xs text-gray-900 dark:text-white font-medium">{{ $a->missions_terminees }}<span class="text-gray-400 text-[10px]">/{{ $a->total_missions }}</span></td>
                    <td class="px-3 py-2.5">
                        <div class="flex items-center gap-1.5 justify-center">
                            <div class="w-12 h-1 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $tauxBar }}" style="width: {{ $a->taux_completion }}%"></div>
                            </div>
                            <span class="text-[10px] text-gray-600 dark:text-gray-300 font-medium w-7 text-right">{{ $a->taux_completion }}%</span>
                        </div>
                    </td>
                    <td class="px-3 py-2.5 text-center text-xs {{ $a->note_moyenne >= 4 ? 'text-emerald-600 font-bold' : ($a->note_moyenne > 0 ? 'text-amber-600 font-bold' : 'text-gray-300') }}">{{ $a->note_moyenne > 0 ? number_format($a->note_moyenne,1) : '—' }}</td>
                    <td class="px-3 py-2.5 text-center">
                        @if($a->avg_reaction > 0)
                        <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-semibold {{ $a->avg_reaction <= 4 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : ($a->avg_reaction <= 12 ? 'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400') }}">{{ $a->avg_reaction }}h</span>
                        @else<span class="text-gray-300 text-xs">—</span>@endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-10 text-center text-xs text-gray-400">Aucun agent</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif

@if($onglet === 'citoyens')
    @php $cits = $this->getCitoyensRanking(); @endphp

    <div class="flex items-center justify-between gap-2 mb-3">
        <input wire:model.live.debounce.300ms="recherche" type="text" placeholder="Rechercher..."
            class="w-56 px-3 py-1.5 text-xs rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400/30">
        <div class="flex gap-0.5 bg-gray-100 dark:bg-gray-800 rounded-lg p-0.5">
            @foreach(['signalements'=>'Signaux','evaluations'=>'Évals','taux'=>'Taux','engagement'=>'Eng.'] as $v => $l)
            <button wire:click="$set('triCitoyen','{{ $v }}')"
                class="px-2 py-1 rounded-md text-[10px] font-medium transition-all {{ $triCitoyen===$v ? 'bg-indigo-500 text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700' }}">{{ $l }}</button>
            @endforeach
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                    <th class="px-3 py-2 text-left text-[10px] font-semibold text-gray-400 uppercase w-8">Rang</th>
                    <th class="px-3 py-2 text-left text-[10px] font-semibold text-gray-400 uppercase">Citoyen</th>
                    <th class="px-3 py-2 text-left text-[10px] font-semibold text-gray-400 uppercase">Classe</th>
                    <th class="px-3 py-2 text-center text-[10px] font-semibold text-gray-400 uppercase">Signaux</th>
                    <th class="px-3 py-2 text-center text-[10px] font-semibold text-gray-400 uppercase">Résolus</th>
                    <th class="px-3 py-2 text-center text-[10px] font-semibold text-gray-400 uppercase">Rejetés</th>
                    <th class="px-3 py-2 text-center text-[10px] font-semibold text-gray-400 uppercase">Taux</th>
                    <th class="px-3 py-2 text-center text-[10px] font-semibold text-gray-400 uppercase">Évals</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($cits as $i => $u)
                @php
                    $rank = $i + 1;
                    $rankBg = $rank === 1 ? 'bg-amber-400 text-white' : ($rank === 2 ? 'bg-gray-400 text-white' : ($rank === 3 ? 'bg-orange-400 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-400'));
                    $cc = $u->classification['color'] ?? 'slate';
                    $badgeCls = ['emerald'=>'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300','blue'=>'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300','amber'=>'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-300','violet'=>'bg-violet-50 text-violet-700 dark:bg-violet-900/20 dark:text-violet-300','red'=>'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300','slate'=>'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400'][$cc] ?? 'bg-gray-100 text-gray-500';
                    $tauxBar = $u->taux_validation >= 70 ? 'bg-emerald-500' : ($u->taux_validation >= 40 ? 'bg-amber-500' : 'bg-red-500');
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40">
                    <td class="px-3 py-2.5 text-center">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded text-[9px] font-bold {{ $rankBg }}">{{ $rank }}</span>
                    </td>
                    <td class="px-3 py-2.5">
                        <span class="text-xs font-medium text-gray-900 dark:text-white">{{ $u->nom }}</span>
                        <span class="text-[10px] text-gray-400 ml-1.5">{{ $u->email }}</span>
                    </td>
                    <td class="px-3 py-2.5">
                        <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-semibold {{ $badgeCls }}">{{ $u->classification['label'] ?? '—' }}</span>
                    </td>
                    <td class="px-3 py-2.5 text-center text-xs font-medium text-gray-900 dark:text-white">{{ $u->signalements }}</td>
                    <td class="px-3 py-2.5 text-center text-xs font-medium text-emerald-600">{{ $u->termines }}</td>
                    <td class="px-3 py-2.5 text-center text-xs font-medium {{ $u->rejetes > 0 ? 'text-red-500' : 'text-gray-300' }}">{{ $u->rejetes > 0 ? $u->rejetes : '—' }}</td>
                    <td class="px-3 py-2.5">
                        <div class="flex items-center gap-1.5 justify-center">
                            <div class="w-12 h-1 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $tauxBar }}" style="width: {{ $u->taux_validation }}%"></div>
                            </div>
                            <span class="text-[10px] text-gray-600 dark:text-gray-300 font-medium w-7 text-right">{{ $u->taux_validation }}%</span>
                        </div>
                    </td>
                    <td class="px-3 py-2.5 text-center">
                        <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-semibold {{ $u->evaluations > 0 ? 'bg-violet-50 text-violet-700 dark:bg-violet-900/20 dark:text-violet-300' : 'bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500' }}">{{ $u->evaluations > 0 ? $u->evaluations : '—' }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="py-10 text-center text-xs text-gray-400">Aucun citoyen</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif

</x-filament-panels::page>
