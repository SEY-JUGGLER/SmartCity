<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-2">
                    <x-heroicon-m-bell-alert style="width:18px;height:18px" class="text-primary-500" />
                    <span class="text-sm font-semibold">Activité récente</span>
                </div>
                <span class="text-[10px] text-gray-400">Temps réel</span>
            </div>
        </x-slot>

        @php $activites = $this->getActivites(); @endphp

        <x-widget-table :headings="[
            ['label' => 'Signalement'],
            ['label' => 'Citoyen'],
            ['label' => 'Statut'],
            ['label' => 'Date', 'class' => 'text-right'],
        ]">
            @forelse($activites as $a)
            <tr class="hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-colors {{ $loop->even ? 'bg-gray-50/50 dark:bg-gray-900/20' : '' }}">
                <td class="px-2 py-2">
                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $a['label'] }}</p>
                    <p class="text-[10px] text-gray-400">{{ $a['sub'] }}</p>
                </td>
                <td class="px-2 py-2 text-gray-600 dark:text-gray-400">{{ $a['citoyen'] ?? '—' }}</td>
                <td class="px-2 py-2">
                    <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $a['color'] }}">
                        @if($a['statut'] === 'enAttente')
                            <x-heroicon-m-clock style="width:10px;height:10px" />
                        @elseif($a['statut'] === 'enCours')
                            <x-heroicon-m-arrow-path style="width:10px;height:10px" />
                        @elseif($a['statut'] === 'terminer')
                            <x-heroicon-m-check-circle style="width:10px;height:10px" />
                        @elseif($a['statut'] === 'rejeter')
                            <x-heroicon-m-x-circle style="width:10px;height:10px" />
                        @endif
                        {{ match($a['statut']) { 'enAttente' => 'Attente', 'enCours' => 'En cours', 'terminer' => 'Terminé', 'rejeter' => 'Rejeté', default => $a['statut'] } }}
                    </span>
                </td>
                <td class="px-2 py-2 text-right text-gray-400 whitespace-nowrap">{{ $a['time'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-2 py-8 text-center text-gray-400">Aucune activité récente</td>
            </tr>
            @endforelse
        </x-widget-table>
    </x-filament::section>
</x-filament-widgets::widget>
