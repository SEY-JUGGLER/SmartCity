<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-2">
                    <x-heroicon-m-exclamation-triangle style="width:18px;height:18px" class="text-amber-500" />
                    <span class="text-sm font-semibold">Alertes système</span>
                </div>
                @php $alertes = $this->getAlertes(); $hasDanger = collect($alertes)->contains('type', 'danger'); @endphp
                @if($hasDanger)
                    <span class="inline-flex items-center gap-1 rounded-full bg-red-100 dark:bg-red-900/30 px-2 py-0.5 text-[10px] font-semibold text-red-700 dark:text-red-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                        Critique
                    </span>
                @endif
            </div>
        </x-slot>

        <x-widget-table :headings="[
            ['label' => 'Type'],
            ['label' => 'Message'],
            ['label' => 'Détail'],
        ]">
            @foreach($alertes as $alerte)
            @php
                $typeStyles = match($alerte['type']) {
                    'danger'  => ['dot' => 'bg-red-500', 'text' => 'text-red-700 dark:text-red-400', 'bg' => 'bg-red-50 dark:bg-red-900/20'],
                    'warning' => ['dot' => 'bg-amber-500', 'text' => 'text-amber-700 dark:text-amber-400', 'bg' => 'bg-amber-50 dark:bg-amber-900/20'],
                    'info'    => ['dot' => 'bg-blue-500', 'text' => 'text-blue-700 dark:text-blue-400', 'bg' => 'bg-blue-50 dark:bg-blue-900/20'],
                    'success' => ['dot' => 'bg-emerald-500', 'text' => 'text-emerald-700 dark:text-emerald-400', 'bg' => 'bg-emerald-50 dark:bg-emerald-900/20'],
                    default   => ['dot' => 'bg-gray-500', 'text' => 'text-gray-700 dark:text-gray-400', 'bg' => 'bg-gray-50 dark:bg-gray-800'],
                };
            @endphp
            <tr class="hover:bg-gray-100 dark:hover:bg-gray-800/50 transition-colors {{ $loop->even ? 'bg-gray-50/50 dark:bg-gray-900/20' : '' }}">
                <td class="px-2 py-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $typeStyles['bg'] }} {{ $typeStyles['text'] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $typeStyles['dot'] }}"></span>
                        {{ ucfirst($alerte['type']) }}
                    </span>
                </td>
                <td class="px-2 py-2 font-medium text-gray-900 dark:text-gray-100">{{ $alerte['titre'] }}</td>
                <td class="px-2 py-2 text-gray-500">{{ $alerte['detail'] }}</td>
            </tr>
            @endforeach
        </x-widget-table>
    </x-filament::section>
</x-filament-widgets::widget>
