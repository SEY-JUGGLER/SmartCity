<x-filament-widgets::widget>
@php $d = $this->getData(); @endphp

<x-filament::section>
    <x-slot name="heading">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center rounded-xl bg-primary-500/10 flex-shrink-0"
                     style="width:36px;height:36px">
                    <x-heroicon-m-signal style="width:18px;height:18px" class="text-primary-500" />
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Vue d'ensemble</p>
                    <p class="text-xs text-gray-400 leading-tight">Signalements & Utilisateurs</p>
                </div>
            </div>
            <div class="flex items-center gap-1.5 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-3 py-1 flex-shrink-0">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Temps réel</span>
            </div>
        </div>
    </x-slot>

    {{-- Taux global --}}
    <div class="mb-5 rounded-2xl border border-indigo-100 dark:border-indigo-900/30 bg-indigo-50/50 dark:bg-indigo-900/10 p-4">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">Taux de résolution global</span>
            <div class="flex items-baseline gap-1">
                <span class="text-xl font-black text-indigo-600 dark:text-indigo-400">{{ $d['tauxResolution'] }}%</span>
                <span class="text-xs text-gray-400">— {{ $d['termines'] }}/{{ $d['total'] }}</span>
            </div>
        </div>
        <div class="w-full h-2.5 bg-white dark:bg-gray-800 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all duration-700"
                 style="width:{{ $d['tauxResolution'] }}%; background:linear-gradient(90deg,#4338ca,#7c3aed)"></div>
        </div>
    </div>

    {{-- Grille signalements : 6 cartes --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3 mb-5">
        @foreach([
            ['Total',      $d['total'],      'Tous statuts',          'slate',   'heroicon-m-inbox'],
            ['En attente', $d['enAttente'],  'À traiter',             'amber',   'heroicon-m-clock'],
            ['En cours',   $d['enCours'],    'En traitement',         'blue',    'heroicon-m-arrow-path'],
            ['Terminés',   $d['termines'],   'Résolus',               'emerald', 'heroicon-m-check-circle'],
            ['Rejetés',    $d['rejetes'],    'Non retenus',           'rose',    'heroicon-m-x-circle'],
            ['Critiques',  $d['critiques'],  $d['critiques']>0 ? 'Action requise' : 'Tout va bien',
             $d['critiques']>0 ? 'red' : 'emerald',
             $d['critiques']>0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-shield-check'],
        ] as [$label, $value, $desc, $color, $icon])
        @php
            $cfg = [
                'slate'   => ['bg'=>'bg-slate-50 dark:bg-slate-900/30',   'border'=>'border-slate-200 dark:border-slate-700/60',   'num'=>'text-slate-700 dark:text-slate-200',   'ibg'=>'bg-slate-100 dark:bg-slate-800',   'ico'=>'text-slate-500',   'bar'=>'linear-gradient(90deg,#64748b,#94a3b8)'],
                'amber'   => ['bg'=>'bg-amber-50 dark:bg-amber-900/10',   'border'=>'border-amber-200 dark:border-amber-800/40',   'num'=>'text-amber-600 dark:text-amber-400',   'ibg'=>'bg-amber-100 dark:bg-amber-900/30',  'ico'=>'text-amber-500',   'bar'=>'linear-gradient(90deg,#f59e0b,#fbbf24)'],
                'blue'    => ['bg'=>'bg-blue-50 dark:bg-blue-900/10',     'border'=>'border-blue-200 dark:border-blue-800/40',     'num'=>'text-blue-600 dark:text-blue-400',     'ibg'=>'bg-blue-100 dark:bg-blue-900/30',    'ico'=>'text-blue-500',    'bar'=>'linear-gradient(90deg,#3b82f6,#60a5fa)'],
                'emerald' => ['bg'=>'bg-emerald-50 dark:bg-emerald-900/10','border'=>'border-emerald-200 dark:border-emerald-800/40','num'=>'text-emerald-600 dark:text-emerald-400','ibg'=>'bg-emerald-100 dark:bg-emerald-900/30','ico'=>'text-emerald-500','bar'=>'linear-gradient(90deg,#10b981,#34d399)'],
                'rose'    => ['bg'=>'bg-rose-50 dark:bg-rose-900/10',     'border'=>'border-rose-200 dark:border-rose-800/40',     'num'=>'text-rose-600 dark:text-rose-400',     'ibg'=>'bg-rose-100 dark:bg-rose-900/30',    'ico'=>'text-rose-500',    'bar'=>'linear-gradient(90deg,#f43f5e,#fb7185)'],
                'red'     => ['bg'=>'bg-red-50 dark:bg-red-900/10',       'border'=>'border-red-200 dark:border-red-800/40',       'num'=>'text-red-600 dark:text-red-400',       'ibg'=>'bg-red-100 dark:bg-red-900/30',      'ico'=>'text-red-500',     'bar'=>'linear-gradient(90deg,#ef4444,#f87171)'],
            ][$color];
        @endphp
        <div class="relative overflow-hidden rounded-2xl border {{ $cfg['border'] }} {{ $cfg['bg'] }} p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center justify-center rounded-xl {{ $cfg['ibg'] }}" style="width:34px;height:34px">
                    <x-dynamic-component :component="$icon" style="width:16px;height:16px" class="{{ $cfg['ico'] }}" />
                </div>
            </div>
            <p class="text-2xl font-black {{ $cfg['num'] }} leading-none">{{ $value }}</p>
            <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mt-1">{{ $label }}</p>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">{{ $desc }}</p>
            <div class="absolute bottom-0 left-0 right-0 h-0.5" style="background:{{ $cfg['bar'] }};opacity:0.5"></div>
        </div>
        @endforeach
    </div>

    {{-- Utilisateurs + Aujourd'hui --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

        {{-- Agents --}}
        <div class="relative overflow-hidden rounded-2xl border border-orange-100 dark:border-orange-900/30 bg-gradient-to-br from-orange-50 to-white dark:from-orange-900/10 dark:to-gray-900 p-4">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="flex items-center justify-center rounded-xl bg-orange-100 dark:bg-orange-900/30 flex-shrink-0" style="width:34px;height:34px">
                    <x-heroicon-m-identification style="width:16px;height:16px" class="text-orange-500" />
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Agents</p>
                    <p class="text-2xl font-black text-orange-600 dark:text-orange-400 leading-none">{{ $d['totalAgents'] }}</p>
                </div>
            </div>
            <div class="w-full h-1.5 bg-orange-100 dark:bg-orange-900/40 rounded-full overflow-hidden">
                <div class="h-full bg-orange-400 rounded-full" style="width:{{ $d['totalUsers']>0 ? round($d['totalAgents']/$d['totalUsers']*100) : 0 }}%"></div>
            </div>
            <p class="text-[10px] text-gray-400 mt-1">{{ $d['totalUsers']>0 ? round($d['totalAgents']/$d['totalUsers']*100) : 0 }}% des utilisateurs</p>
            <div class="absolute bottom-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#f97316,#fb923c);opacity:0.5"></div>
        </div>

        {{-- Citoyens --}}
        <div class="relative overflow-hidden rounded-2xl border border-emerald-100 dark:border-emerald-900/30 bg-gradient-to-br from-emerald-50 to-white dark:from-emerald-900/10 dark:to-gray-900 p-4">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="flex items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex-shrink-0" style="width:34px;height:34px">
                    <x-heroicon-m-user-group style="width:16px;height:16px" class="text-emerald-500" />
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Citoyens</p>
                    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 leading-none">{{ $d['totalCitoyens'] }}</p>
                </div>
            </div>
            <div class="w-full h-1.5 bg-emerald-100 dark:bg-emerald-900/40 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-400 rounded-full" style="width:{{ $d['totalUsers']>0 ? round($d['totalCitoyens']/$d['totalUsers']*100) : 0 }}%"></div>
            </div>
            <p class="text-[10px] text-gray-400 mt-1">{{ $d['totalUsers']>0 ? round($d['totalCitoyens']/$d['totalUsers']*100) : 0 }}% des utilisateurs</p>
            <div class="absolute bottom-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#10b981,#34d399);opacity:0.5"></div>
        </div>

        {{-- Aujourd'hui --}}
        <div class="relative overflow-hidden rounded-2xl border border-cyan-200 dark:border-cyan-800/40 bg-gradient-to-br from-cyan-50 to-white dark:from-cyan-900/10 dark:to-gray-900 p-4">
            <div class="flex items-center gap-2.5 mb-3">
                <div class="flex items-center justify-center rounded-xl bg-cyan-100 dark:bg-cyan-900/30 flex-shrink-0" style="width:34px;height:34px">
                    <x-heroicon-m-calendar-days style="width:16px;height:16px" class="text-cyan-600 dark:text-cyan-400" />
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Aujourd'hui</p>
                    <p class="text-2xl font-black text-cyan-600 dark:text-cyan-400 leading-none">{{ $d['aujourdHui'] }}</p>
                </div>
            </div>
            @if($d['aujourdHui'] > 0)
            <span class="inline-flex items-center gap-1.5 rounded-full bg-cyan-100 dark:bg-cyan-900/40 px-2.5 py-1 text-[10px] font-semibold text-cyan-700 dark:text-cyan-300">
                <span class="h-1.5 w-1.5 rounded-full bg-cyan-500 animate-pulse"></span>
                Activité en cours
            </span>
            @else
            <span class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-800 px-2.5 py-1 text-[10px] font-semibold text-gray-500">Calme pour l'instant</span>
            @endif
            <div class="absolute bottom-0 left-0 right-0 h-0.5" style="background:linear-gradient(90deg,#06b6d4,#22d3ee);opacity:0.5"></div>
        </div>

    </div>

</x-filament::section>
</x-filament-widgets::widget>
