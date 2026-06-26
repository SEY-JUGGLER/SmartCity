<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2.5">
                <div class="flex items-center justify-center rounded-lg bg-violet-500/10 flex-shrink-0" style="width:28px;height:28px">
                    <x-heroicon-m-chart-pie style="width:14px;height:14px" class="text-violet-500" />
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white leading-tight">Classifications</p>
                    <p class="text-[10px] text-gray-400 leading-tight">Distribution des profils agents et citoyens</p>
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
                'emerald' => ['bar' => 'bg-emerald-500', 'badge' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-300 dark:ring-emerald-700'],
                'blue'    => ['bar' => 'bg-blue-500',    'badge' => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200 dark:bg-blue-900/20 dark:text-blue-300 dark:ring-blue-700'],
                'amber'   => ['bar' => 'bg-amber-500',   'badge' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200 dark:bg-amber-900/20 dark:text-amber-300 dark:ring-amber-700'],
                'violet'  => ['bar' => 'bg-violet-500',  'badge' => 'bg-violet-50 text-violet-700 ring-1 ring-violet-200 dark:bg-violet-900/20 dark:text-violet-300 dark:ring-violet-700'],
                'red'     => ['bar' => 'bg-red-500',     'badge' => 'bg-red-50 text-red-700 ring-1 ring-red-200 dark:bg-red-900/20 dark:text-red-300 dark:ring-red-700'],
                'slate'   => ['bar' => 'bg-slate-400',   'badge' => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:ring-slate-700'],
            ];
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Agents --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-2 h-2 rounded-full bg-orange-500 flex-shrink-0"></span>
                    <h3 class="text-[10px] font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Agents</h3>
                    <span class="ml-auto text-[10px] font-semibold text-gray-400">{{ array_sum($agentCounts) }} total</span>
                </div>
                <div class="space-y-2.5">
                    @foreach($agentClasses as $key => $class)
                        @php
                            $count  = $agentCounts[$key] ?? 0;
                            $pct    = round($count / $totalAgents * 100);
                            $colors = $colorMap[$class['color']] ?? $colorMap['slate'];
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1 gap-2">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium {{ $colors['badge'] }} min-w-0 truncate">
                                    {{ $class['emoji'] }} {{ $class['label'] }}
                                </span>
                                <span class="text-[10px] font-semibold text-gray-600 dark:text-gray-300 flex-shrink-0">
                                    {{ $count }}<span class="text-gray-400 font-normal ml-0.5">({{ $pct }}%)</span>
                                </span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5 overflow-hidden">
                                <div class="{{ $colors['bar'] }} h-full rounded-full transition-all duration-700 ease-out"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Citoyens --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                    <h3 class="text-[10px] font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Citoyens</h3>
                    <span class="ml-auto text-[10px] font-semibold text-gray-400">{{ array_sum($citoyenCounts) }} total</span>
                </div>
                <div class="space-y-2.5">
                    @foreach($citoyenClasses as $key => $class)
                        @php
                            $count  = $citoyenCounts[$key] ?? 0;
                            $pct    = round($count / $totalCitoyens * 100);
                            $colors = $colorMap[$class['color']] ?? $colorMap['slate'];
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1 gap-2">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium {{ $colors['badge'] }} min-w-0 truncate">
                                    {{ $class['emoji'] }} {{ $class['label'] }}
                                </span>
                                <span class="text-[10px] font-semibold text-gray-600 dark:text-gray-300 flex-shrink-0">
                                    {{ $count }}<span class="text-gray-400 font-normal ml-0.5">({{ $pct }}%)</span>
                                </span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5 overflow-hidden">
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
