<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                @php $alertes = $this->getAlertes(); $hasDanger = collect($alertes)->contains('type', 'danger'); @endphp
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center rounded-xl {{ $hasDanger ? 'bg-red-500/10' : 'bg-amber-500/10' }} flex-shrink-0" style="width:36px;height:36px">
                        <x-heroicon-m-exclamation-triangle style="width:18px;height:18px" class="{{ $hasDanger ? 'text-red-500' : 'text-amber-500' }}" />
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Alertes système</p>
                        <p class="text-xs text-gray-400 leading-tight">{{ count($alertes) }} alerte(s) active(s)</p>
                    </div>
                </div>
                @if($hasDanger)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 dark:bg-red-900/30 px-3 py-1 text-xs font-bold text-red-700 dark:text-red-400 flex-shrink-0">
                    <span class="h-2 w-2 rounded-full bg-red-500 animate-pulse"></span>
                    Critique
                </span>
                @endif
            </div>
        </x-slot>

        <div class="space-y-2.5">
            @foreach($alertes as $alerte)
            @php
                $cfg = match($alerte['type']) {
                    'danger'  => [
                        'border' => 'border-red-200 dark:border-red-800/50',
                        'bg'     => 'bg-gradient-to-r from-red-50 to-red-50/30 dark:from-red-900/15 dark:to-transparent',
                        'icon'   => 'text-red-500',
                        'iconBg' => 'bg-red-100 dark:bg-red-900/30',
                        'title'  => 'text-red-800 dark:text-red-300',
                        'detail' => 'text-red-600/70 dark:text-red-400/70',
                        'dot'    => 'bg-red-500',
                        'badge'  => 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 ring-1 ring-red-200 dark:ring-red-700/50',
                    ],
                    'warning' => [
                        'border' => 'border-amber-200 dark:border-amber-800/50',
                        'bg'     => 'bg-gradient-to-r from-amber-50 to-amber-50/30 dark:from-amber-900/15 dark:to-transparent',
                        'icon'   => 'text-amber-500',
                        'iconBg' => 'bg-amber-100 dark:bg-amber-900/30',
                        'title'  => 'text-amber-800 dark:text-amber-300',
                        'detail' => 'text-amber-600/70 dark:text-amber-400/70',
                        'dot'    => 'bg-amber-500',
                        'badge'  => 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 ring-1 ring-amber-200 dark:ring-amber-700/50',
                    ],
                    'info'    => [
                        'border' => 'border-blue-200 dark:border-blue-800/50',
                        'bg'     => 'bg-gradient-to-r from-blue-50 to-blue-50/30 dark:from-blue-900/15 dark:to-transparent',
                        'icon'   => 'text-blue-500',
                        'iconBg' => 'bg-blue-100 dark:bg-blue-900/30',
                        'title'  => 'text-blue-800 dark:text-blue-300',
                        'detail' => 'text-blue-600/70 dark:text-blue-400/70',
                        'dot'    => 'bg-blue-500',
                        'badge'  => 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 ring-1 ring-blue-200 dark:ring-blue-700/50',
                    ],
                    'success' => [
                        'border' => 'border-emerald-200 dark:border-emerald-800/50',
                        'bg'     => 'bg-gradient-to-r from-emerald-50 to-emerald-50/30 dark:from-emerald-900/15 dark:to-transparent',
                        'icon'   => 'text-emerald-500',
                        'iconBg' => 'bg-emerald-100 dark:bg-emerald-900/30',
                        'title'  => 'text-emerald-800 dark:text-emerald-300',
                        'detail' => 'text-emerald-600/70 dark:text-emerald-400/70',
                        'dot'    => 'bg-emerald-500',
                        'badge'  => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 ring-1 ring-emerald-200 dark:ring-emerald-700/50',
                    ],
                    default   => [
                        'border' => 'border-gray-200 dark:border-gray-700',
                        'bg'     => 'bg-gray-50 dark:bg-gray-800/50',
                        'icon'   => 'text-gray-500',
                        'iconBg' => 'bg-gray-100 dark:bg-gray-800',
                        'title'  => 'text-gray-700 dark:text-gray-300',
                        'detail' => 'text-gray-500 dark:text-gray-400',
                        'dot'    => 'bg-gray-400',
                        'badge'  => 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 ring-1 ring-gray-200 dark:ring-gray-700',
                    ],
                };
            @endphp
            <div class="flex items-center gap-3.5 p-3.5 rounded-2xl border {{ $cfg['border'] }} {{ $cfg['bg'] }}">
                <div class="flex items-center justify-center rounded-xl {{ $cfg['iconBg'] }} flex-shrink-0" style="width:38px;height:38px">
                    @if($alerte['icon'] === 'exclamation-triangle')
                        <x-heroicon-m-exclamation-triangle style="width:16px;height:16px" class="{{ $cfg['icon'] }}" />
                    @elseif($alerte['icon'] === 'user-minus')
                        <x-heroicon-m-user-minus style="width:16px;height:16px" class="{{ $cfg['icon'] }}" />
                    @elseif($alerte['icon'] === 'exclamation-circle')
                        <x-heroicon-m-exclamation-circle style="width:16px;height:16px" class="{{ $cfg['icon'] }}" />
                    @elseif($alerte['icon'] === 'map-pin')
                        <x-heroicon-m-map-pin style="width:16px;height:16px" class="{{ $cfg['icon'] }}" />
                    @elseif($alerte['icon'] === 'clock')
                        <x-heroicon-m-clock style="width:16px;height:16px" class="{{ $cfg['icon'] }}" />
                    @elseif($alerte['icon'] === 'check-circle')
                        <x-heroicon-m-check-circle style="width:16px;height:16px" class="{{ $cfg['icon'] }}" />
                    @else
                        <x-heroicon-m-bell style="width:16px;height:16px" class="{{ $cfg['icon'] }}" />
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold leading-tight {{ $cfg['title'] }} truncate">{{ $alerte['titre'] }}</p>
                    <p class="text-[11px] {{ $cfg['detail'] }} mt-0.5 leading-snug">{{ $alerte['detail'] }}</p>
                </div>
                <span class="flex-shrink-0 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-semibold {{ $cfg['badge'] }} whitespace-nowrap">
                    <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }} flex-shrink-0"></span>
                    {{ ucfirst($alerte['type']) }}
                </span>
            </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
