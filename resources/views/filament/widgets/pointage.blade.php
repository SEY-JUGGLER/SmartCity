<x-filament-widgets::widget>
    <x-filament::section>
        @php $d = $this->getData(); @endphp

        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-2.5">
                    <div class="flex items-center justify-center rounded-lg bg-primary-500/10" style="width:28px;height:28px;flex-shrink:0">
                        <x-heroicon-m-clock style="width:14px;height:14px" class="text-primary-500" />
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Pointage du jour</span>
                </div>
                <div class="flex items-center gap-1.5 px-2 py-1 rounded-full border flex-shrink-0
                    {{ $d['tauxPresence'] >= 80 ? 'bg-emerald-500/10 border-emerald-500/20' : ($d['tauxPresence'] >= 60 ? 'bg-amber-500/10 border-amber-500/20' : 'bg-red-500/10 border-red-500/20') }}">
                    <span class="text-xs font-black leading-none {{ $d['tauxPresence'] >= 80 ? 'text-emerald-600 dark:text-emerald-400' : ($d['tauxPresence'] >= 60 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400') }}">{{ $d['tauxPresence'] }}%</span>
                    <span class="text-[10px] {{ $d['tauxPresence'] >= 80 ? 'text-emerald-500' : ($d['tauxPresence'] >= 60 ? 'text-amber-500' : 'text-red-500') }}">présence</span>
                </div>
            </div>
        </x-slot>

        {{-- Barre taux --}}
        <div class="mb-4">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">Taux de présence global</span>
                <span class="text-[10px] font-semibold {{ $d['tauxPresence'] >= 80 ? 'text-emerald-600 dark:text-emerald-400' : ($d['tauxPresence'] >= 60 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400') }}">
                    {{ $d['presents'] }} / {{ $d['totalAgents'] }} agents
                </span>
            </div>
            <div class="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-700 ease-out {{ $d['tauxPresence'] >= 80 ? 'bg-emerald-500' : ($d['tauxPresence'] >= 60 ? 'bg-amber-500' : 'bg-red-500') }}"
                     style="width: {{ $d['tauxPresence'] }}%"></div>
            </div>
        </div>

        {{-- Metric Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">

            <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50 p-3">
                <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider mb-1">Présents</p>
                <p class="text-2xl font-black text-emerald-500 leading-none">{{ $d['presents'] }}</p>
                <span class="inline-flex items-center gap-1 mt-1.5 rounded-full bg-emerald-50 dark:bg-emerald-900/20 px-1.5 py-0.5 text-[9px] font-semibold text-emerald-700 dark:text-emerald-400">
                    <span class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse flex-shrink-0"></span>
                    En poste
                </span>
            </div>

            <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50 p-3">
                <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider mb-1">Non pointés</p>
                <p class="text-2xl font-black {{ $d['nonPointes'] > 0 ? 'text-amber-500' : 'text-emerald-500' }} leading-none">{{ $d['nonPointes'] }}</p>
                @if($d['nonPointes'] > 0)
                <span class="inline-flex items-center gap-1 mt-1.5 rounded-full bg-amber-50 dark:bg-amber-900/20 px-1.5 py-0.5 text-[9px] font-semibold text-amber-700 dark:text-amber-400">
                    <span class="w-1 h-1 rounded-full bg-amber-500 flex-shrink-0"></span>
                    En attente
                </span>
                @else
                <span class="inline-flex items-center mt-1.5 rounded-full bg-emerald-50 dark:bg-emerald-900/20 px-1.5 py-0.5 text-[9px] font-semibold text-emerald-700 dark:text-emerald-400">Tous pointés</span>
                @endif
            </div>

            <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50 p-3">
                <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider mb-1">Absents</p>
                <p class="text-2xl font-black {{ $d['absents'] > 0 ? 'text-red-500' : 'text-emerald-500' }} leading-none">{{ $d['absents'] }}</p>
                @if($d['absents'] > 0)
                <span class="inline-flex items-center gap-1 mt-1.5 rounded-full bg-red-50 dark:bg-red-900/20 px-1.5 py-0.5 text-[9px] font-semibold text-red-700 dark:text-red-400">
                    <span class="w-1 h-1 rounded-full bg-red-500 flex-shrink-0"></span>
                    Absent
                </span>
                @else
                <span class="inline-flex items-center mt-1.5 rounded-full bg-emerald-50 dark:bg-emerald-900/20 px-1.5 py-0.5 text-[9px] font-semibold text-emerald-700 dark:text-emerald-400">Complet</span>
                @endif
            </div>

            <div class="rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50 p-3">
                <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wider mb-1">Moy. en poste</p>
                <p class="text-2xl font-black text-gray-700 dark:text-gray-200 leading-none">{{ $d['avgHours'] !== null ? $d['avgHours'] . 'h' : '—' }}</p>
                <span class="inline-flex items-center mt-1.5 rounded-full bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 text-[9px] font-semibold text-gray-500">Temps réel</span>
            </div>

        </div>

        @if($d['inactifs'])
        <div class="mt-2 flex items-center gap-2 p-2.5 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
            <div class="flex items-center justify-center rounded-lg bg-slate-500/10 flex-shrink-0" style="width:24px;height:24px">
                <x-heroicon-m-moon style="width:11px;height:11px" class="text-slate-500" />
            </div>
            <p class="text-xs text-gray-500"><span class="font-semibold text-slate-600 dark:text-slate-400">{{ $d['inactifs'] }}</span> agent(s) inactif(s) — compte désactivé</p>
        </div>
        @endif

    </x-filament::section>
</x-filament-widgets::widget>
