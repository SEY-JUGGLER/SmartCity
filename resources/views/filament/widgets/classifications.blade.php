<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center rounded-xl bg-violet-500/10 flex-shrink-0" style="width:36px;height:36px">
                        <x-heroicon-m-chart-pie style="width:18px;height:18px" class="text-violet-500" />
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Classifications</p>
                        <p class="text-xs text-gray-400 leading-tight">Distribution des profils agents et citoyens</p>
                    </div>
                </div>
            </div>
        </x-slot>

        @php
            $agentCounts    = $this->getAgentCounts();
            $citoyenCounts  = $this->getCitoyenCounts();
            $agentClasses   = $this->getAgentClasses();
            $citoyenClasses = $this->getCitoyenClasses();
            $totalAgents    = array_sum($agentCounts) ?: 1;
            $totalCitoyens  = array_sum($citoyenCounts) ?: 1;

            $colorMap = [
                'emerald' => [
                    'bar'   => 'bg-emerald-500',
                    'badge' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-300 dark:ring-emerald-700',
                    'num'   => 'text-emerald-600 dark:text-emerald-400',
                    'glow'  => 'from-emerald-500/20 to-transparent',
                ],
                'blue'    => [
                    'bar'   => 'bg-blue-500',
                    'badge' => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200 dark:bg-blue-900/20 dark:text-blue-300 dark:ring-blue-700',
                    'num'   => 'text-blue-600 dark:text-blue-400',
                    'glow'  => 'from-blue-500/20 to-transparent',
                ],
                'amber'   => [
                    'bar'   => 'bg-amber-500',
                    'badge' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200 dark:bg-amber-900/20 dark:text-amber-300 dark:ring-amber-700',
                    'num'   => 'text-amber-600 dark:text-amber-400',
                    'glow'  => 'from-amber-500/20 to-transparent',
                ],
                'violet'  => [
                    'bar'   => 'bg-violet-500',
                    'badge' => 'bg-violet-50 text-violet-700 ring-1 ring-violet-200 dark:bg-violet-900/20 dark:text-violet-300 dark:ring-violet-700',
                    'num'   => 'text-violet-600 dark:text-violet-400',
                    'glow'  => 'from-violet-500/20 to-transparent',
                ],
                'red'     => [
                    'bar'   => 'bg-red-500',
                    'badge' => 'bg-red-50 text-red-700 ring-1 ring-red-200 dark:bg-red-900/20 dark:text-red-300 dark:ring-red-700',
                    'num'   => 'text-red-600 dark:text-red-400',
                    'glow'  => 'from-red-500/20 to-transparent',
                ],
                'slate'   => [
                    'bar'   => 'bg-slate-400',
                    'badge' => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:ring-slate-700',
                    'num'   => 'text-slate-600 dark:text-slate-400',
                    'glow'  => 'from-slate-400/20 to-transparent',
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- Agents --}}
            <div class="rounded-2xl border border-orange-100 dark:border-orange-900/20 bg-gradient-to-br from-orange-50/40 to-white dark:from-orange-900/5 dark:to-gray-900 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="flex items-center justify-center rounded-xl bg-orange-500/10 flex-shrink-0" style="width:32px;height:32px">
                            <x-heroicon-m-identification style="width:15px;height:15px" class="text-orange-500" />
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Agents terrain</h3>
                            <p class="text-[11px] text-gray-400">Classification des agents</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-orange-100 dark:bg-orange-900/30 text-xs font-bold text-orange-700 dark:text-orange-400">
                        {{ array_sum($agentCounts) }} agents
                    </span>
                </div>

                <div class="space-y-3">
                    @foreach($agentClasses as $key => $class)
                        @php
                            $count  = $agentCounts[$key] ?? 0;
                            $pct    = round($count / $totalAgents * 100);
                            $colors = $colorMap[$class['color']] ?? $colorMap['slate'];
                        @endphp
                        <div class="p-3 rounded-xl bg-white/70 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800">
                            <div class="flex items-center justify-between mb-2 gap-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $colors['badge'] }} min-w-0 truncate max-w-[70%]">
                                    {{ $class['emoji'] }} {{ $class['label'] }}
                                </span>
                                <div class="flex items-baseline gap-1 flex-shrink-0">
                                    <span class="text-base font-black {{ $colors['num'] }}">{{ $count }}</span>
                                    <span class="text-[10px] text-gray-400 font-normal">/ {{ $pct }}%</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700/60 rounded-full h-2 overflow-hidden">
                                <div class="{{ $colors['bar'] }} h-full rounded-full transition-all duration-700 ease-out"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Citoyens --}}
            <div class="rounded-2xl border border-emerald-100 dark:border-emerald-900/20 bg-gradient-to-br from-emerald-50/40 to-white dark:from-emerald-900/5 dark:to-gray-900 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="flex items-center justify-center rounded-xl bg-emerald-500/10 flex-shrink-0" style="width:32px;height:32px">
                            <x-heroicon-m-user-circle style="width:15px;height:15px" class="text-emerald-500" />
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Citoyens</h3>
                            <p class="text-[11px] text-gray-400">Classification des citoyens</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-xs font-bold text-emerald-700 dark:text-emerald-400">
                        {{ array_sum($citoyenCounts) }} citoyens
                    </span>
                </div>

                <div class="space-y-3">
                    @foreach($citoyenClasses as $key => $class)
                        @php
                            $count  = $citoyenCounts[$key] ?? 0;
                            $pct    = round($count / $totalCitoyens * 100);
                            $colors = $colorMap[$class['color']] ?? $colorMap['slate'];
                        @endphp
                        <div class="p-3 rounded-xl bg-white/70 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800">
                            <div class="flex items-center justify-between mb-2 gap-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $colors['badge'] }} min-w-0 truncate max-w-[70%]">
                                    {{ $class['emoji'] }} {{ $class['label'] }}
                                </span>
                                <div class="flex items-baseline gap-1 flex-shrink-0">
                                    <span class="text-base font-black {{ $colors['num'] }}">{{ $count }}</span>
                                    <span class="text-[10px] text-gray-400 font-normal">/ {{ $pct }}%</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700/60 rounded-full h-2 overflow-hidden">
                                <div class="{{ $colors['bar'] }} h-full rounded-full transition-all duration-700 ease-out"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
