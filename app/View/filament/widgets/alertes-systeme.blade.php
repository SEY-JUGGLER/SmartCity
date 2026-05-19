<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-m-bell-alert class="w-5 h-5 text-red-400"/>
                Alertes système
            </div>
        </x-slot>
        <x-slot name="headerEnd">
            <span class="flex items-center gap-1.5 text-xs text-gray-400">
                <span class="live-dot"></span> 30s
            </span>
        </x-slot>

        @php $alertes = $this->getAlertes(); @endphp

        <div class="space-y-2.5">
            @foreach($alertes as $a)
            @php
                [$bg, $border, $tc, $ic] = match($a['type']) {
                    'danger'  => ['bg-red-50 dark:bg-red-900/15',     'border-red-200 dark:border-red-800',     'text-red-800 dark:text-red-200',     'text-red-500'],
                    'warning' => ['bg-amber-50 dark:bg-amber-900/15', 'border-amber-200 dark:border-amber-800', 'text-amber-800 dark:text-amber-200', 'text-amber-500'],
                    'info'    => ['bg-blue-50 dark:bg-blue-900/15',   'border-blue-200 dark:border-blue-800',   'text-blue-800 dark:text-blue-200',   'text-blue-500'],
                    'success' => ['bg-emerald-50 dark:bg-emerald-900/15', 'border-emerald-200 dark:border-emerald-800', 'text-emerald-800 dark:text-emerald-200', 'text-emerald-500'],
                    default   => ['bg-gray-50', 'border-gray-200', 'text-gray-800', 'text-gray-500'],
                };
            @endphp
            <div class="flex items-start gap-3 p-3.5 rounded-xl border {{ $bg }} {{ $border }}">
                <div class="{{ $ic }} shrink-0 mt-0.5">
                    @switch($a['icon'])
                        @case('exclamation-triangle') <x-heroicon-m-exclamation-triangle class="w-5 h-5"/> @break
                        @case('user-minus')           <x-heroicon-m-user-minus class="w-5 h-5"/>          @break
                        @case('exclamation-circle')   <x-heroicon-m-exclamation-circle class="w-5 h-5"/>  @break
                        @case('map-pin')              <x-heroicon-m-map-pin class="w-5 h-5"/>              @break
                        @case('clock')                <x-heroicon-m-clock class="w-5 h-5"/>               @break
                        @case('check-circle')         <x-heroicon-m-check-circle class="w-5 h-5"/>        @break
                        @default                      <x-heroicon-m-bell class="w-5 h-5"/>
                    @endswitch
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold {{ $tc }}">{{ $a['titre'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $a['detail'] }}</p>
                </div>
                <span class="text-xs text-gray-400 shrink-0">{{ now()->format('H:i') }}</span>
            </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>