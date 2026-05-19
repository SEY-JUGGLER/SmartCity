<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-m-clock style="width:18px;height:18px" class="text-primary-500" />
                <span class="text-sm font-semibold">Pointage du jour</span>
            </div>
        </x-slot>

        @php $d = $this->getData(); @endphp

        <x-widget-table :headings="[
            ['label' => 'Indicateur'],
            ['label' => 'Valeur', 'class' => 'text-right'],
            ['label' => 'Statut', 'class' => 'text-right'],
        ]">
            <tr class="hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-colors">
                <td class="px-2 py-2 font-medium text-gray-900 dark:text-gray-100">Total agents</td>
                <td class="px-2 py-2 text-right font-bold text-gray-900 dark:text-white">{{ $d['totalAgents'] }}</td>
                <td class="px-2 py-2 text-right">
                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 dark:bg-gray-800 px-2 py-0.5 text-[10px] font-semibold text-gray-600 dark:text-gray-400">Effectif</span>
                </td>
            </tr>
            <tr class="hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-colors bg-gray-50/50 dark:bg-gray-900/20">
                <td class="px-2 py-2 font-medium text-gray-900 dark:text-gray-100">Présents</td>
                <td class="px-2 py-2 text-right font-bold text-emerald-600 dark:text-emerald-400">{{ $d['presents'] }}</td>
                <td class="px-2 py-2 text-right">
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 dark:bg-emerald-900/20 px-2 py-0.5 text-[10px] font-semibold text-emerald-700 dark:text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        En poste
                    </span>
                </td>
            </tr>
            <tr class="hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-colors">
                <td class="px-2 py-2 font-medium text-gray-900 dark:text-gray-100">Absents</td>
                <td class="px-2 py-2 text-right font-bold text-red-600 dark:text-red-400">{{ $d['absents'] }}</td>
                <td class="px-2 py-2 text-right">
                    @if($d['absents'] > 0)
                    <span class="inline-flex items-center gap-1 rounded-full bg-red-50 dark:bg-red-900/20 px-2 py-0.5 text-[10px] font-semibold text-red-700 dark:text-red-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                        Absent
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 dark:bg-emerald-900/20 px-2 py-0.5 text-[10px] font-semibold text-emerald-700 dark:text-emerald-400">Complet</span>
                    @endif
                </td>
            </tr>
            <tr class="hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-colors bg-gray-50/50 dark:bg-gray-900/20">
                <td class="px-2 py-2 font-medium text-gray-900 dark:text-gray-100">Non pointés</td>
                <td class="px-2 py-2 text-right font-bold text-amber-600 dark:text-amber-400">{{ $d['nonPointes'] }}</td>
                <td class="px-2 py-2 text-right">
                    @if($d['nonPointes'] > 0)
                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 dark:bg-amber-900/20 px-2 py-0.5 text-[10px] font-semibold text-amber-700 dark:text-amber-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        En attente
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 dark:bg-emerald-900/20 px-2 py-0.5 text-[10px] font-semibold text-emerald-700 dark:text-emerald-400">Tous pointés</span>
                    @endif
                </td>
            </tr>
            <tr class="hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-colors">
                <td class="px-2 py-2 font-medium text-gray-900 dark:text-gray-100">Taux de présence</td>
                <td class="px-2 py-2 text-right font-bold {{ $d['tauxPresence'] >= 80 ? 'text-emerald-600' : ($d['tauxPresence'] >= 60 ? 'text-amber-600' : 'text-red-600') }} dark:text-inherit">{{ $d['tauxPresence'] }}%</td>
                <td class="px-2 py-2 text-right">
                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $d['tauxPresence'] >= 80 ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400' : ($d['tauxPresence'] >= 60 ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400' : 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400') }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $d['tauxPresence'] >= 80 ? 'bg-emerald-500' : ($d['tauxPresence'] >= 60 ? 'bg-amber-500' : 'bg-red-500') }}"></span>
                        {{ $d['tauxPresence'] >= 80 ? 'Bon' : ($d['tauxPresence'] >= 60 ? 'Moyen' : 'Faible') }}
                    </span>
                </td>
            </tr>
            <tr class="hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-colors bg-gray-50/50 dark:bg-gray-900/20">
                <td class="px-2 py-2 font-medium text-gray-900 dark:text-gray-100">Moy. depuis pointage</td>
                <td class="px-2 py-2 text-right font-bold text-gray-900 dark:text-white">{{ $d['avgHours'] !== null ? $d['avgHours'] . 'h' : '—' }}</td>
                <td class="px-2 py-2 text-right">
                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 dark:bg-gray-800 px-2 py-0.5 text-[10px] font-semibold text-gray-600 dark:text-gray-400">Temps réel</span>
                </td>
            </tr>
            @if($d['inactifs'])
            <tr class="hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-colors bg-gray-50/50 dark:bg-gray-900/20">
                <td class="px-2 py-2 font-medium text-gray-900 dark:text-gray-100">Inactifs</td>
                <td class="px-2 py-2 text-right font-bold text-gray-500">{{ $d['inactifs'] }}</td>
                <td class="px-2 py-2 text-right">
                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 dark:bg-gray-800 px-2 py-0.5 text-[10px] font-semibold text-gray-500">Inactif</span>
                </td>
            </tr>
            @endif
        </x-widget-table>
    </x-filament::section>
</x-filament-widgets::widget>
