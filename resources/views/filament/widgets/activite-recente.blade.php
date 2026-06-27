<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center rounded-xl bg-primary-500/10 flex-shrink-0" style="width:36px;height:36px">
                        <x-heroicon-m-bell-alert style="width:18px;height:18px" class="text-primary-500" />
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Activité récente</p>
                        <p class="text-xs text-gray-400 leading-tight">10 derniers signalements</p>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse flex-shrink-0"></span>
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Live</span>
                </div>
            </div>
        </x-slot>

        @php $activites = $this->getActivites(); @endphp

        <div class="space-y-2">
            @forelse($activites as $a)
            @php
                $border = match($a['statut']) {
                    'enAttente' => 'border-amber-200 dark:border-amber-800/40',
                    'enCours'   => 'border-cyan-200 dark:border-cyan-800/40',
                    'terminer'  => 'border-emerald-200 dark:border-emerald-800/40',
                    'rejeter'   => 'border-red-200 dark:border-red-800/40',
                    default     => 'border-gray-200 dark:border-gray-700',
                };
                $bg = match($a['statut']) {
                    'enAttente' => 'bg-amber-50/60 dark:bg-amber-900/5',
                    'enCours'   => 'bg-cyan-50/60 dark:bg-cyan-900/5',
                    'terminer'  => 'bg-emerald-50/60 dark:bg-emerald-900/5',
                    'rejeter'   => 'bg-red-50/60 dark:bg-red-900/5',
                    default     => 'bg-gray-50 dark:bg-gray-800/40',
                };
                $iconBg = match($a['statut']) {
                    'enAttente' => 'bg-amber-100 dark:bg-amber-900/30',
                    'enCours'   => 'bg-cyan-100 dark:bg-cyan-900/30',
                    'terminer'  => 'bg-emerald-100 dark:bg-emerald-900/30',
                    'rejeter'   => 'bg-red-100 dark:bg-red-900/30',
                    default     => 'bg-gray-100 dark:bg-gray-800',
                };
            @endphp
            <div class="flex items-start gap-3 p-3 rounded-xl border {{ $border }} {{ $bg }} hover:shadow-sm transition-all duration-150">
                <div class="flex items-center justify-center rounded-xl {{ $iconBg }} flex-shrink-0" style="width:34px;height:34px;margin-top:1px">
                    @if($a['statut'] === 'enAttente')
                        <x-heroicon-m-clock style="width:15px;height:15px" class="text-amber-500" />
                    @elseif($a['statut'] === 'enCours')
                        <x-heroicon-m-arrow-path style="width:15px;height:15px" class="text-cyan-500" />
                    @elseif($a['statut'] === 'terminer')
                        <x-heroicon-m-check-circle style="width:15px;height:15px" class="text-emerald-500" />
                    @elseif($a['statut'] === 'rejeter')
                        <x-heroicon-m-x-circle style="width:15px;height:15px" class="text-red-500" />
                    @else
                        <x-heroicon-m-bell style="width:15px;height:15px" class="text-gray-400" />
                    @endif
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-gray-900 dark:text-gray-100 leading-snug">{{ $a['label'] }}</p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5 truncate">{{ $a['sub'] }}</p>
                            @if($a['citoyen'] ?? null)
                            <p class="text-[11px] text-gray-400 mt-0.5 truncate">
                                <span class="text-gray-300 dark:text-gray-600">par</span> <span class="font-medium text-gray-500 dark:text-gray-400">{{ $a['citoyen'] }}</span>
                            </p>
                            @endif
                        </div>
                        <div class="flex flex-col items-end gap-1.5 flex-shrink-0 ml-1">
                            <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $a['color'] }} whitespace-nowrap">
                                {{ match($a['statut']) { 'enAttente' => 'Attente', 'enCours' => 'En cours', 'terminer' => 'Terminé', 'rejeter' => 'Rejeté', default => $a['statut'] } }}
                            </span>
                            <span class="text-[10px] text-gray-400 whitespace-nowrap">{{ $a['time'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-14 flex flex-col items-center gap-3">
                <div class="flex items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-800" style="width:56px;height:56px">
                    <x-heroicon-o-inbox style="width:24px;height:24px" class="text-gray-300 dark:text-gray-600" />
                </div>
                <p class="text-sm font-medium text-gray-400">Aucune activité récente</p>
            </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
