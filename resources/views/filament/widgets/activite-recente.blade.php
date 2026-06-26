<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-2.5">
                    <div class="flex items-center justify-center rounded-lg bg-primary-500/10" style="width:28px;height:28px;flex-shrink:0">
                        <x-heroicon-m-bell-alert style="width:14px;height:14px" class="text-primary-500" />
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Activité récente</span>
                </div>
                <div class="flex items-center gap-1.5 px-2 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse flex-shrink-0"></span>
                    <span class="text-[10px] font-medium text-emerald-600 dark:text-emerald-400">Live</span>
                </div>
            </div>
        </x-slot>

        @php $activites = $this->getActivites(); @endphp

        <div class="divide-y divide-gray-100 dark:divide-gray-800/60">
            @forelse($activites as $a)
            <div class="flex items-start gap-3 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors duration-150 rounded-lg px-1 -mx-1">
                <div class="flex-shrink-0" style="margin-top:1px">
                    <div class="flex items-center justify-center rounded-lg" style="width:28px;height:28px;background:{{ match($a['statut']) { 'enAttente' => 'rgba(245,158,11,0.1)', 'enCours' => 'rgba(6,182,212,0.1)', 'terminer' => 'rgba(16,185,129,0.1)', 'rejeter' => 'rgba(239,68,68,0.1)', default => 'rgba(107,114,128,0.1)' } }}">
                        @if($a['statut'] === 'enAttente')
                            <x-heroicon-m-clock style="width:13px;height:13px" class="text-amber-500" />
                        @elseif($a['statut'] === 'enCours')
                            <x-heroicon-m-arrow-path style="width:13px;height:13px" class="text-cyan-500" />
                        @elseif($a['statut'] === 'terminer')
                            <x-heroicon-m-check-circle style="width:13px;height:13px" class="text-emerald-500" />
                        @elseif($a['statut'] === 'rejeter')
                            <x-heroicon-m-x-circle style="width:13px;height:13px" class="text-red-500" />
                        @else
                            <x-heroicon-m-bell style="width:13px;height:13px" class="text-gray-400" />
                        @endif
                    </div>
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-gray-900 dark:text-gray-100 leading-tight truncate">{{ $a['label'] }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5 truncate">{{ $a['sub'] }}</p>
                            @if($a['citoyen'] ?? null)
                            <p class="text-[10px] text-gray-500 mt-0.5 truncate">
                                <span class="text-gray-400">par</span> {{ $a['citoyen'] }}
                            </p>
                            @endif
                        </div>
                        <div class="flex-shrink-0 flex flex-col items-end gap-1">
                            <span class="inline-flex items-center gap-1 rounded-full px-1.5 py-0.5 text-[9px] font-semibold {{ $a['color'] }} whitespace-nowrap">
                                {{ match($a['statut']) { 'enAttente' => 'Attente', 'enCours' => 'En cours', 'terminer' => 'Terminé', 'rejeter' => 'Rejeté', default => $a['statut'] } }}
                            </span>
                            <span class="text-[9px] text-gray-400 whitespace-nowrap">{{ $a['time'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-10 flex flex-col items-center gap-2">
                <div class="flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800" style="width:40px;height:40px">
                    <x-heroicon-o-inbox style="width:18px;height:18px" class="text-gray-400" />
                </div>
                <p class="text-xs text-gray-400">Aucune activité récente</p>
            </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
