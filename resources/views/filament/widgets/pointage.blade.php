<x-filament-widgets::widget>
    <x-filament::section>
        @php $d = $this->getData(); @endphp

        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center rounded-xl bg-primary-500/10 flex-shrink-0" style="width:36px;height:36px">
                        <x-heroicon-m-clock style="width:18px;height:18px" class="text-primary-500" />
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Pointage du jour</p>
                        <p class="text-xs text-gray-400 leading-tight">Présences en temps réel</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full border flex-shrink-0
                    {{ $d['tauxPresence'] >= 80 ? 'bg-emerald-500/10 border-emerald-500/20' : ($d['tauxPresence'] >= 60 ? 'bg-amber-500/10 border-amber-500/20' : 'bg-red-500/10 border-red-500/20') }}">
                    <span class="text-sm font-black leading-none {{ $d['tauxPresence'] >= 80 ? 'text-emerald-600 dark:text-emerald-400' : ($d['tauxPresence'] >= 60 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400') }}">{{ $d['tauxPresence'] }}%</span>
                    <span class="text-xs {{ $d['tauxPresence'] >= 80 ? 'text-emerald-500' : ($d['tauxPresence'] >= 60 ? 'text-amber-500' : 'text-red-500') }}">présence</span>
                </div>
            </div>
        </x-slot>

        {{-- Barre taux globale --}}
        <div class="mb-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-medium uppercase tracking-wider">Taux de présence global</span>
                <span class="text-xs font-bold {{ $d['tauxPresence'] >= 80 ? 'text-emerald-600 dark:text-emerald-400' : ($d['tauxPresence'] >= 60 ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400') }}">
                    {{ $d['presents'] }} / {{ $d['totalAgents'] }} agents
                </span>
            </div>
            <div class="w-full h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-700 ease-out {{ $d['tauxPresence'] >= 80 ? 'bg-emerald-500' : ($d['tauxPresence'] >= 60 ? 'bg-amber-500' : 'bg-red-500') }}"
                     style="width: {{ $d['tauxPresence'] }}%"></div>
            </div>
        </div>

        {{-- Metric Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

            <div class="relative overflow-hidden rounded-2xl border border-emerald-100 dark:border-emerald-900/30 bg-gradient-to-br from-emerald-50/60 to-white dark:from-emerald-900/10 dark:to-gray-900 p-4 shadow-sm">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Présents</p>
                <p class="text-4xl font-black text-emerald-500 leading-none">{{ $d['presents'] }}</p>
                <span class="inline-flex items-center gap-1.5 mt-2 rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2 py-0.5 text-[10px] font-semibold text-emerald-700 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse flex-shrink-0"></span>
                    En poste
                </span>
                <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-emerald-400/40"></div>
            </div>

            <div class="relative overflow-hidden rounded-2xl border {{ $d['nonPointes'] > 0 ? 'border-amber-100 dark:border-amber-900/30' : 'border-emerald-100 dark:border-emerald-900/30' }} bg-gradient-to-br {{ $d['nonPointes'] > 0 ? 'from-amber-50/60 to-white dark:from-amber-900/10' : 'from-emerald-50/60 to-white dark:from-emerald-900/10' }} dark:to-gray-900 p-4 shadow-sm">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Non pointés</p>
                <p class="text-4xl font-black {{ $d['nonPointes'] > 0 ? 'text-amber-500' : 'text-emerald-500' }} leading-none">{{ $d['nonPointes'] }}</p>
                @if($d['nonPointes'] > 0)
                <span class="inline-flex items-center gap-1.5 mt-2 rounded-full bg-amber-100 dark:bg-amber-900/30 px-2 py-0.5 text-[10px] font-semibold text-amber-700 dark:text-amber-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 flex-shrink-0"></span>
                    En attente
                </span>
                @else
                <span class="inline-flex items-center mt-2 rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2 py-0.5 text-[10px] font-semibold text-emerald-700 dark:text-emerald-400">Tous pointés</span>
                @endif
                <div class="absolute bottom-0 left-0 right-0 h-0.5 {{ $d['nonPointes'] > 0 ? 'bg-amber-400/40' : 'bg-emerald-400/40' }}"></div>
            </div>

            <div class="relative overflow-hidden rounded-2xl border {{ $d['absents'] > 0 ? 'border-red-100 dark:border-red-900/30' : 'border-emerald-100 dark:border-emerald-900/30' }} bg-gradient-to-br {{ $d['absents'] > 0 ? 'from-red-50/60 to-white dark:from-red-900/10' : 'from-emerald-50/60 to-white dark:from-emerald-900/10' }} dark:to-gray-900 p-4 shadow-sm">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Absents</p>
                <p class="text-4xl font-black {{ $d['absents'] > 0 ? 'text-red-500' : 'text-emerald-500' }} leading-none">{{ $d['absents'] }}</p>
                @if($d['absents'] > 0)
                <span class="inline-flex items-center gap-1.5 mt-2 rounded-full bg-red-100 dark:bg-red-900/30 px-2 py-0.5 text-[10px] font-semibold text-red-700 dark:text-red-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 flex-shrink-0"></span>
                    Absent
                </span>
                @else
                <span class="inline-flex items-center mt-2 rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-2 py-0.5 text-[10px] font-semibold text-emerald-700 dark:text-emerald-400">Complet</span>
                @endif
                <div class="absolute bottom-0 left-0 right-0 h-0.5 {{ $d['absents'] > 0 ? 'bg-red-400/40' : 'bg-emerald-400/40' }}"></div>
            </div>

            <div class="relative overflow-hidden rounded-2xl border border-gray-100 dark:border-gray-800 bg-gradient-to-br from-gray-50/80 to-white dark:from-gray-800/30 dark:to-gray-900 p-4 shadow-sm">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Moy. en poste</p>
                <p class="text-4xl font-black text-gray-700 dark:text-gray-200 leading-none">{{ $d['avgHours'] !== null ? $d['avgHours'] . 'h' : '—' }}</p>
                <span class="inline-flex items-center mt-2 rounded-full bg-gray-100 dark:bg-gray-800 px-2 py-0.5 text-[10px] font-semibold text-gray-500">Temps réel</span>
                <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gray-300/40"></div>
            </div>

        </div>

        @if($d['inactifs'])
        <div class="mt-3 flex items-center gap-3 p-3.5 rounded-2xl border border-slate-100 dark:border-slate-800/60 bg-slate-50/60 dark:bg-slate-900/20">
            <div class="flex items-center justify-center rounded-xl bg-slate-500/10 flex-shrink-0" style="width:34px;height:34px">
                <x-heroicon-m-moon style="width:15px;height:15px" class="text-slate-500" />
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400">{{ $d['inactifs'] }} agent(s) inactif(s)</p>
                <p class="text-[11px] text-gray-400">Compte(s) désactivé(s) — non pris en compte dans les statistiques</p>
            </div>
        </div>
        @endif

    </x-filament::section>
</x-filament-widgets::widget>
