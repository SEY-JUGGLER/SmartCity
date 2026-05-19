<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-m-bolt class="w-5 h-5 text-amber-400"/>
                Activité récente
            </div>
        </x-slot>
        <x-slot name="headerEnd">
            <span class="flex items-center gap-1.5 text-xs text-gray-400">
                <span class="live-dot"></span> Live · 15s
            </span>
        </x-slot>

        @php $activites = $this->getActivites(); @endphp

        <ul class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse($activites as $a)
            <li class="flex items-start gap-3 py-3 px-1 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors">
                <span class="flex items-center justify-center w-8 h-8 rounded-full shrink-0 mt-0.5 {{ $a['color'] }}">
                    @switch($a['icon'])
                        @case('clock')        <x-heroicon-m-clock class="w-4 h-4"/>         @break
                        @case('arrow-path')   <x-heroicon-m-arrow-path class="w-4 h-4"/>    @break
                        @case('check-circle') <x-heroicon-m-check-circle class="w-4 h-4"/> @break
                        @case('x-circle')     <x-heroicon-m-x-circle class="w-4 h-4"/>     @break
                        @default              <x-heroicon-m-bell class="w-4 h-4"/>
                    @endswitch
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200 leading-snug">{{ $a['label'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $a['sub'] }}
                        @if($a['citoyen']) · <span class="text-gray-500 dark:text-gray-300">{{ $a['citoyen'] }}</span> @endif
                    </p>
                </div>
                <span class="text-xs text-gray-400 whitespace-nowrap shrink-0">{{ $a['time'] }}</span>
            </li>
            @empty
            <li class="py-10 text-center text-sm text-gray-400">
                <x-heroicon-o-inbox class="w-8 h-8 mx-auto mb-2 opacity-40"/>
                Aucune activité récente
            </li>
            @endforelse
        </ul>

        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center">
            <a href="{{ \App\Filament\Resources\Signalements\SignalementResource::getUrl('index') }}"
               class="text-xs font-medium text-blue-500 hover:text-blue-400 flex items-center gap-1">
                Voir tous les signalements <x-heroicon-m-arrow-right class="w-3.5 h-3.5"/>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>