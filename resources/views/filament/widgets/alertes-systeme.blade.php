<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                @php $alertes = $this->getAlertes(); $hasDanger = collect($alertes)->contains('type', 'danger'); @endphp
                <div class="flex items-center gap-2.5">
                    <div class="flex items-center justify-center rounded-lg {{ $hasDanger ? 'bg-red-500/10' : 'bg-amber-500/10' }}" style="width:28px;height:28px;flex-shrink:0">
                        <x-heroicon-m-exclamation-triangle style="width:14px;height:14px" class="{{ $hasDanger ? 'text-red-500' : 'text-amber-500' }}" />
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Alertes système</span>
                </div>
                @if($hasDanger)
                <span class="inline-flex items-center gap-1 rounded-full bg-red-100 dark:bg-red-900/30 px-2 py-0.5 text-[10px] font-semibold text-red-700 dark:text-red-400 flex-shrink-0">
                    <span class="h-1.5 w-1.5 rounded-full bg-red-500 animate-pulse"></span>
                    Critique
                </span>
                @endif
            </div>
        </x-slot>

        <div class="space-y-2">
            @foreach($alertes as $alerte)
            @php
                $cfg = match($alerte['type']) {
                    'danger'  => ['border' => 'border-red-200 dark:border-red-800/50',        'bg' => 'bg-red-50 dark:bg-red-900/10',         'icon' => 'text-red-500',     'title' => 'text-red-700 dark:text-red-300',         'dot' => 'bg-red-500',    'badge' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300'],
                    'warning' => ['border' => 'border-amber-200 dark:border-amber-800/50',    'bg' => 'bg-amber-50 dark:bg-amber-900/10',     'icon' => 'text-amber-500',   'title' => 'text-amber-700 dark:text-amber-300',     'dot' => 'bg-amber-500',  'badge' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300'],
                    'info'    => ['border' => 'border-blue-200 dark:border-blue-800/50',      'bg' => 'bg-blue-50 dark:bg-blue-900/10',       'icon' => 'text-blue-500',    'title' => 'text-blue-700 dark:text-blue-300',       'dot' => 'bg-blue-500',   'badge' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300'],
                    'success' => ['border' => 'border-emerald-200 dark:border-emerald-800/50','bg' => 'bg-emerald-50 dark:bg-emerald-900/10', 'icon' => 'text-emerald-500', 'title' => 'text-emerald-700 dark:text-emerald-300', 'dot' => 'bg-emerald-500','badge' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300'],
                    default   => ['border' => 'border-gray-200 dark:border-gray-700',         'bg' => 'bg-gray-50 dark:bg-gray-800/50',       'icon' => 'text-gray-500',    'title' => 'text-gray-700 dark:text-gray-300',       'dot' => 'bg-gray-400',   'badge' => 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400'],
                };
            @endphp
            <div class="flex items-start gap-2.5 p-2.5 rounded-xl border {{ $cfg['border'] }} {{ $cfg['bg'] }}">
                <div class="flex-shrink-0 flex items-center justify-center rounded-lg bg-white/60 dark:bg-white/5" style="width:28px;height:28px;margin-top:1px">
                    @if($alerte['icon'] === 'exclamation-triangle')
                        <x-heroicon-m-exclamation-triangle style="width:13px;height:13px" class="{{ $cfg['icon'] }}" />
                    @elseif($alerte['icon'] === 'user-minus')
                        <x-heroicon-m-user-minus style="width:13px;height:13px" class="{{ $cfg['icon'] }}" />
                    @elseif($alerte['icon'] === 'exclamation-circle')
                        <x-heroicon-m-exclamation-circle style="width:13px;height:13px" class="{{ $cfg['icon'] }}" />
                    @elseif($alerte['icon'] === 'map-pin')
                        <x-heroicon-m-map-pin style="width:13px;height:13px" class="{{ $cfg['icon'] }}" />
                    @elseif($alerte['icon'] === 'clock')
                        <x-heroicon-m-clock style="width:13px;height:13px" class="{{ $cfg['icon'] }}" />
                    @elseif($alerte['icon'] === 'check-circle')
                        <x-heroicon-m-check-circle style="width:13px;height:13px" class="{{ $cfg['icon'] }}" />
                    @else
                        <x-heroicon-m-bell style="width:13px;height:13px" class="{{ $cfg['icon'] }}" />
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold leading-tight {{ $cfg['title'] }} truncate">{{ $alerte['titre'] }}</p>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5 leading-snug">{{ $alerte['detail'] }}</p>
                </div>
                <span class="flex-shrink-0 inline-flex items-center gap-1 rounded-full px-1.5 py-0.5 text-[9px] font-semibold {{ $cfg['badge'] }} whitespace-nowrap">
                    <span class="w-1 h-1 rounded-full {{ $cfg['dot'] }} flex-shrink-0"></span>
                    {{ ucfirst($alerte['type']) }}
                </span>
            </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
