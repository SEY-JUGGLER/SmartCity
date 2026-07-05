<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            @php $alertes = $this->getAlertes(); $hasDanger = collect($alertes)->contains('type', 'danger'); @endphp
            <div style="display:flex;align-items:center;justify-content:space-between;width:100%">
                <div style="display:flex;align-items:center;gap:0.75rem">
                    <div style="display:flex;align-items:center;justify-content:center;border-radius:0.75rem;{{ $hasDanger ? 'background:rgba(239,68,68,0.1)' : 'background:rgba(245,158,11,0.1)' }};flex-shrink:0;width:36px;height:36px">
                        <x-heroicon-m-exclamation-triangle style="width:18px;height:18px;{{ $hasDanger ? 'color:#ef4444' : 'color:#f59e0b' }}" />
                    </div>
                    <div>
                        <p style="font-size:0.875rem;font-weight:700;color:#111827;line-height:1.25">Alertes système</p>
                        <p style="font-size:0.75rem;color:#9ca3af;line-height:1.25">{{ count($alertes) }} alerte(s) active(s)</p>
                    </div>
                </div>
                @if($hasDanger)
                <span style="display:inline-flex;align-items:center;gap:0.375rem;border-radius:9999px;background:rgba(239,68,68,0.1);padding:0.25rem 0.75rem;font-size:0.75rem;font-weight:700;color:#dc2626;flex-shrink:0">
                    <span style="width:0.5rem;height:0.5rem;border-radius:9999px;background:#ef4444;animation:pulse 2s cubic-bezier(0.4,0,0.6,1) infinite"></span>
                    Critique
                </span>
                @endif
            </div>
        </x-slot>

        <div style="display:flex;flex-direction:column;gap:0.625rem">
            @foreach($alertes as $alerte)
            @php
                $isDanger = $alerte['type'] === 'danger';
                $isWarning = $alerte['type'] === 'warning';
                $isInfo = $alerte['type'] === 'info';
                $isSuccess = $alerte['type'] === 'success';
                $borderClr = $isDanger ? 'rgba(239,68,68,0.3)' : ($isWarning ? 'rgba(245,158,11,0.3)' : ($isInfo ? 'rgba(59,130,246,0.3)' : 'rgba(16,185,129,0.3)'));
                $bgGrad = $isDanger ? 'linear-gradient(to right,rgba(254,242,242,1),rgba(254,242,242,0.3))' : ($isWarning ? 'linear-gradient(to right,rgba(255,251,235,1),rgba(255,251,235,0.3))' : ($isInfo ? 'linear-gradient(to right,rgba(239,246,255,1),rgba(239,246,255,0.3))' : 'linear-gradient(to right,rgba(236,253,245,1),rgba(236,253,245,0.3))'));
                $iconBg = $isDanger ? 'rgba(239,68,68,0.1)' : ($isWarning ? 'rgba(245,158,11,0.1)' : ($isInfo ? 'rgba(59,130,246,0.1)' : 'rgba(16,185,129,0.1)'));
                $iconClr = $isDanger ? '#ef4444' : ($isWarning ? '#f59e0b' : ($isInfo ? '#3b82f6' : '#10b981'));
                $titleClr = $isDanger ? '#991b1b' : ($isWarning ? '#92400e' : ($isInfo ? '#1e40af' : '#065f46'));
                $dotClr = $isDanger ? '#ef4444' : ($isWarning ? '#f59e0b' : ($isInfo ? '#3b82f6' : '#10b981'));
                $badgeBg = $isDanger ? 'rgba(239,68,68,0.1)' : ($isWarning ? 'rgba(245,158,11,0.1)' : ($isInfo ? 'rgba(59,130,246,0.1)' : 'rgba(16,185,129,0.1)'));
                $badgeClr = $isDanger ? '#dc2626' : ($isWarning ? '#d97706' : ($isInfo ? '#2563eb' : '#059669'));
            @endphp
            <div style="display:flex;align-items:center;gap:0.875rem;padding:0.75rem 1rem;border-radius:1rem;border:1px solid {{ $borderClr }};background:{{ $bgGrad }}">
                <div style="display:flex;align-items:center;justify-content:center;border-radius:0.75rem;background:{{ $iconBg }};width:36px;height:36px;flex-shrink:0">
                    @if($alerte['icon'] === 'exclamation-triangle')
                        <x-heroicon-m-exclamation-triangle style="width:16px;height:16px;color:{{ $iconClr }}" />
                    @elseif($alerte['icon'] === 'user-minus')
                        <x-heroicon-m-user-minus style="width:16px;height:16px;color:{{ $iconClr }}" />
                    @elseif($alerte['icon'] === 'exclamation-circle')
                        <x-heroicon-m-exclamation-circle style="width:16px;height:16px;color:{{ $iconClr }}" />
                    @elseif($alerte['icon'] === 'map-pin')
                        <x-heroicon-m-map-pin style="width:16px;height:16px;color:{{ $iconClr }}" />
                    @elseif($alerte['icon'] === 'clock')
                        <x-heroicon-m-clock style="width:16px;height:16px;color:{{ $iconClr }}" />
                    @elseif($alerte['icon'] === 'check-circle')
                        <x-heroicon-m-check-circle style="width:16px;height:16px;color:{{ $iconClr }}" />
                    @else
                        <x-heroicon-m-bell style="width:16px;height:16px;color:{{ $iconClr }}" />
                    @endif
                </div>
                <div style="min-width:0;flex:1">
                    <p style="font-size:0.8125rem;font-weight:700;color:{{ $titleClr }};line-height:1.25;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $alerte['titre'] }}</p>
                    <p style="font-size:0.6875rem;color:{{ $isDanger ? 'rgba(220,38,38,0.7)' : ($isWarning ? 'rgba(217,119,6,0.7)' : ($isInfo ? 'rgba(37,99,235,0.7)' : 'rgba(5,150,105,0.7)')) }};margin-top:0.125rem;line-height:1.25">{{ $alerte['detail'] }}</p>
                </div>
                <span style="display:inline-flex;align-items:center;gap:0.375rem;border-radius:9999px;padding:0.25rem 0.625rem;font-size:0.625rem;font-weight:600;background:{{ $badgeBg }};color:{{ $badgeClr }};white-space:nowrap;flex-shrink:0">
                    <span style="width:0.375rem;height:0.375rem;border-radius:9999px;background:{{ $dotClr }};flex-shrink:0"></span>
                    {{ ucfirst($alerte['type']) }}
                </span>
            </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
