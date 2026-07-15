<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div style="display:flex;align-items:center;justify-content:space-between;width:100%">
                <div style="display:flex;align-items:center;gap:0.75rem">
                    <div style="display:flex;align-items:center;justify-content:center;border-radius:0.75rem;background:rgba(59,130,246,0.1);flex-shrink:0;width:36px;height:36px">
                        <x-heroicon-m-bell-alert style="width:18px;height:18px;color:#3b82f6" />
                    </div>
                    <div>
                        <p style="font-size:0.875rem;font-weight:700;color:#111827;line-height:1.25">Journal d'activité</p>
                        <p style="font-size:0.75rem;color:#9ca3af;line-height:1.25">Toutes les actions</p>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:0.375rem;padding:0.375rem 0.75rem;border-radius:9999px;background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.2);flex-shrink:0">
                    <span style="width:0.5rem;height:0.5rem;border-radius:9999px;background:#10b981;animation:pulse 2s cubic-bezier(0.4,0,0.6,1) infinite;flex-shrink:0"></span>
                    <span style="font-size:0.75rem;font-weight:600;color:#059669">Live</span>
                </div>
            </div>
        </x-slot>

        @php $activites = $this->getActivites(); @endphp

        <div style="display:flex;flex-direction:column;gap:0.5rem">
            @forelse($activites as $a)
            @php
                $isCreated   = str_contains($a['statut'], 'created');
                $isAttrib    = str_contains($a['statut'], 'attributed');
                $isStatus    = str_contains($a['statut'], 'status_changed');
                $isValid     = str_contains($a['statut'], 'valide');
                $isRefuse    = str_contains($a['statut'], 'refuse');
                $isRapport   = str_contains($a['statut'], 'generated');
                $borderClr = $isCreated ? 'rgba(245,158,11,0.3)' : ($isAttrib || $isStatus ? 'rgba(6,182,212,0.3)' : ($isValid || $isRapport ? 'rgba(16,185,129,0.3)' : 'rgba(239,68,68,0.3)'));
                $bgGrad = $isCreated ? 'linear-gradient(to right,rgba(255,251,235,0.6),rgba(255,251,235,0.1))' : ($isAttrib || $isStatus ? 'linear-gradient(to right,rgba(236,254,255,0.6),rgba(236,254,255,0.1))' : ($isValid || $isRapport ? 'linear-gradient(to right,rgba(236,253,245,0.6),rgba(236,253,245,0.1))' : 'linear-gradient(to right,rgba(254,242,242,0.6),rgba(254,242,242,0.1))'));
                $iconBg = $isCreated ? 'rgba(245,158,11,0.1)' : ($isAttrib || $isStatus ? 'rgba(6,182,212,0.1)' : ($isValid || $isRapport ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)'));
                $iconClr = $isCreated ? '#f59e0b' : ($isAttrib || $isStatus ? '#06b6d4' : ($isValid || $isRapport ? '#10b981' : '#ef4444'));
                $badgeBg = $isCreated ? 'rgba(245,158,11,0.1)' : ($isAttrib || $isStatus ? 'rgba(6,182,212,0.1)' : ($isValid || $isRapport ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)'));
                $badgeClr = $isCreated ? '#d97706' : ($isAttrib || $isStatus ? '#0891b2' : ($isValid || $isRapport ? '#059669' : '#dc2626'));
                $badgeTxt = $isCreated ? 'Nouveau' : ($isAttrib ? 'Attribué' : ($isStatus ? 'Modifié' : ($isValid ? 'Validé' : ($isRapport ? 'Généré' : 'Refusé'))));
            @endphp
            <div style="display:flex;align-items:flex-start;gap:0.75rem;padding:0.75rem;border-radius:0.75rem;border:1px solid {{ $borderClr }};background:{{ $bgGrad }};transition:box-shadow 0.15s">
                <div style="display:flex;align-items:center;justify-content:center;border-radius:0.75rem;background:{{ $iconBg }};width:34px;height:34px;flex-shrink:0;margin-top:1px">
                    @if($isCreated)
                        <x-heroicon-m-clock style="width:15px;height:15px;color:{{ $iconClr }}" />
                    @elseif($isAttrib || $isStatus)
                        <x-heroicon-m-arrow-path style="width:15px;height:15px;color:{{ $iconClr }}" />
                    @elseif($isValid || $isRapport)
                        <x-heroicon-m-check-circle style="width:15px;height:15px;color:{{ $iconClr }}" />
                    @elseif($isRefuse)
                        <x-heroicon-m-x-circle style="width:15px;height:15px;color:{{ $iconClr }}" />
                    @else
                        <x-heroicon-m-bell style="width:15px;height:15px;color:#9ca3af" />
                    @endif
                </div>
                <div style="min-width:0;flex:1">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.5rem">
                        <div style="min-width:0">
                            <p style="font-size:0.8125rem;font-weight:600;color:#111827;line-height:1.25">{{ $a['label'] }}</p>
                            @if($a['citoyen'] ?? null)
                            <p style="font-size:0.6875rem;color:#9ca3af;margin-top:0.125rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                <span style="color:#d1d5db">par</span> <span style="font-weight:500;color:#6b7280">{{ $a['citoyen'] }}</span>
                            </p>
                            @endif
                            @if(($a['sub'] ?? null) && strlen($a['sub']) > 80)
                            <p style="font-size:0.6875rem;color:#9ca3af;margin-top:0.125rem" title="{{ $a['sub'] }}">{{ Str::limit($a['sub'], 80) }}</p>
                            @endif
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:0.375rem;flex-shrink:0;margin-left:0.25rem">
                            <span style="display:inline-flex;align-items:center;gap:0.25rem;border-radius:9999px;padding:0.125rem 0.5rem;font-size:0.625rem;font-weight:600;background:{{ $badgeBg }};color:{{ $badgeClr }};white-space:nowrap">{{ $badgeTxt }}</span>
                            <span style="font-size:0.625rem;color:#9ca3af;white-space:nowrap">{{ $a['time'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div style="padding:3.5rem 0;display:flex;flex-direction:column;align-items:center;gap:0.75rem">
                <div style="display:flex;align-items:center;justify-content:center;border-radius:1rem;background:#f3f4f6;width:56px;height:56px">
                    <x-heroicon-o-inbox style="width:24px;height:24px;color:#9ca3af" />
                </div>
                <p style="font-size:0.875rem;font-weight:500;color:#9ca3af">Aucune activité récente</p>
            </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
