<x-filament-panels::page>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .ca-scroll { scrollbar-width:thin; scrollbar-color:#d1d5db transparent; }
    .ca-scroll::-webkit-scrollbar { width:3px; }
    .ca-scroll::-webkit-scrollbar-thumb { background:#d1d5db; border-radius:4px; }
    .dark .ca-scroll { scrollbar-color:#4b5563 transparent; }
    .dark .ca-scroll::-webkit-scrollbar-thumb { background:#4b5563; }
    #ca-map { height:100%; min-height:480px; width:100%; border-radius:0; }
    .leaflet-popup-content-wrapper { border-radius:10px !important; box-shadow:0 6px 24px rgba(0,0,0,.12) !important; }
    .leaflet-popup-content { margin:10px 14px !important; font-size:12px !important; }
    .dark .leaflet-popup-content-wrapper { background:#1f2937 !important; color:#e5e7eb !important; }
    .dark .leaflet-popup-content { color:#e5e7eb !important; }
    .sidebar-enter { transition:all .3s cubic-bezier(.4,0,.2,1); }
    .sidebar-enter.sidebar-closed { max-width:0 !important; overflow:hidden; padding:0 !important; margin:0 !important; opacity:0; }
</style>

@php $s = $this->getStats(); @endphp

<div style="display:flex;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.25rem">
    @php
        $cards = [
            ['label'=>'Disponibles', 'val'=>$s['agentsActifs'], 'icon'=>'heroicon-m-check-circle', 'color'=>'#10b981', 'bg'=>'rgba(16,185,129,0.1)', 'border'=>'rgba(16,185,129,0.3)', 'bar'=>'rgba(16,185,129,0.25)'],
            ['label'=>'Occupés', 'val'=>$s['agentsOccupes'], 'icon'=>'heroicon-m-briefcase', 'color'=>'#ef4444', 'bg'=>'rgba(239,68,68,0.1)', 'border'=>'rgba(239,68,68,0.3)', 'bar'=>'rgba(239,68,68,0.25)'],
            ['label'=>'Sur la carte', 'val'=>$s['agentsLocalises'], 'icon'=>'heroicon-m-map-pin', 'color'=>'#6366f1', 'bg'=>'rgba(99,102,241,0.1)', 'border'=>'rgba(99,102,241,0.3)', 'bar'=>'rgba(99,102,241,0.25)'],
            ['label'=>'En attente', 'val'=>$s['sigEnAttente'], 'icon'=>'heroicon-m-clock', 'color'=>'#f59e0b', 'bg'=>'rgba(245,158,11,0.1)', 'border'=>'rgba(245,158,11,0.3)', 'bar'=>'rgba(245,158,11,0.25)'],
            ['label'=>'En cours', 'val'=>$s['sigEnCours'], 'icon'=>'heroicon-m-arrow-path', 'color'=>'#06b6d4', 'bg'=>'rgba(6,182,212,0.1)', 'border'=>'rgba(6,182,212,0.3)', 'bar'=>'rgba(6,182,212,0.25)'],
        ];
    @endphp
    @foreach($cards as $card)
    <div style="flex:1 1 130px;min-width:110px;position:relative;overflow:hidden;border-radius:1rem;border:1px solid {{ $card['border'] }};background:#fff;padding:0.875rem;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.5rem;margin-bottom:0.375rem">
            <span style="font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em">{{ $card['label'] }}</span>
            <div style="display:flex;align-items:center;justify-content:center;border-radius:0.5rem;background:{{ $card['bg'] }};width:28px;height:28px;flex-shrink:0">
                <x-dynamic-component :component="$card['icon']" style="width:14px;height:14px;color:{{ $card['color'] }}" />
            </div>
        </div>
        <p style="font-size:1.625rem;font-weight:900;color:{{ $card['color'] }};line-height:1;margin:0">{{ $card['val'] }}</p>
        <div style="position:absolute;bottom:0;left:0;right:0;height:0.125rem;background:{{ $card['bar'] }}"></div>
    </div>
    @endforeach
</div>

<div style="display:flex;gap:1rem">
    <div style="flex:1;min-width:0;border-radius:1rem;border:1px solid #e5e7eb;background:#fff;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
        <div style="padding:0.625rem 0.875rem;border-bottom:1px solid #f3f4f6;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:0.5rem">
            <div style="display:flex;align-items:center;gap:0.375rem;flex-wrap:wrap">
                <span style="font-size:0.6875rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em">Signalements :</span>
                @foreach(['actifs'=>'Actifs','attente'=>'Attente','cours'=>'En cours','tous'=>'Tous'] as $val=>$label)
                    <button wire:click="setFiltreStatut('{{ $val }}')"
                        style="padding:0.25rem 0.625rem;border-radius:0.375rem;font-size:0.6875rem;font-weight:600;border:none;cursor:pointer;transition:all 0.15s;{{ $filtreStatut===$val ? 'background:#6366f1;color:#fff;box-shadow:0 1px 3px rgba(99,102,241,0.3)' : 'background:#f3f4f6;color:#4b5563' }}">{{ $label }}</button>
                @endforeach
            </div>
            <div style="display:flex;align-items:center;gap:0.5rem">
                <button wire:click="toggleSidebar"
                    style="display:flex;align-items:center;justify-content:center;width:1.75rem;height:1.75rem;border-radius:0.5rem;background:#f3f4f6;border:none;cursor:pointer;transition:background 0.15s"
                    title="Afficher/Masquer le panneau">
                    <svg style="width:0.75rem;height:0.75rem;color:#6b7280" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sidebarOuverte ? 'M9 5l7 7-7 7' : 'M15 19l-7-7 7-7' }}"/>
                    </svg>
                </button>
                <div style="display:flex;align-items:center;gap:0.375rem;font-size:0.6875rem;color:#6b7280" wire:poll.10s="refreshMapData">
                    <span style="position:relative;display:flex;width:0.5rem;height:0.5rem">
                        <span style="position:absolute;display:inline-flex;height:100%;width:100%;border-radius:9999px;background:#10b981;opacity:0.75;animation:ping 1s cubic-bezier(0,0,0.2,1) infinite"></span>
                        <span style="position:relative;display:inline-flex;border-radius:9999px;width:0.5rem;height:0.5rem;background:#10b981"></span>
                    </span>
                    Temps réel
                </div>
            </div>
        </div>
        <div wire:ignore id="ca-map"></div>
        <div style="padding:0.375rem 0.875rem;border-top:1px solid #f3f4f6;display:flex;flex-wrap:wrap;gap:0.75rem;align-items:center">
            <span style="font-size:0.625rem;font-weight:600;color:#9ca3af;text-transform:uppercase">Légende :</span>
            <span style="display:flex;align-items:center;gap:0.25rem;font-size:0.625rem;color:#6b7280"><span style="width:0.5rem;height:0.5rem;border-radius:9999px;background:#10b981;display:inline-block"></span>Dispo</span>
            <span style="display:flex;align-items:center;gap:0.25rem;font-size:0.625rem;color:#6b7280"><span style="width:0.5rem;height:0.5rem;border-radius:9999px;background:#ef4444;display:inline-block"></span>Occupé</span>
            <span style="display:flex;align-items:center;gap:0.25rem;font-size:0.625rem;color:#6b7280"><span style="width:0.625rem;height:0.625rem;border-radius:9999px;background:#f59e0b;border:2px solid #d97706;display:inline-block"></span>Attente</span>
            <span style="display:flex;align-items:center;gap:0.25rem;font-size:0.625rem;color:#6b7280"><span style="width:0.625rem;height:0.625rem;border-radius:9999px;background:#06b6d4;border:2px solid #0891b2;display:inline-block"></span>En cours</span>
            <span style="display:flex;align-items:center;gap:0.25rem;font-size:0.625rem;color:#6b7280"><span style="width:0.625rem;height:0.625rem;border-radius:9999px;background:#10b981;border:2px solid #059669;display:inline-block"></span>Terminé</span>
            <span style="display:flex;align-items:center;gap:0.25rem;font-size:0.625rem;color:#6b7280"><span style="width:0.625rem;height:0.625rem;border-radius:9999px;background:#fff;border:2px solid #ef4444;display:inline-block"></span>Critique</span>
        </div>
    </div>

    <div class="sidebar-enter {{ $sidebarOuverte ? '' : 'sidebar-closed' }}" style="flex-shrink:0;{{ $sidebarOuverte ? 'width:18rem' : 'width:0' }}">
        <div style="display:flex;flex-direction:column;gap:0.75rem;width:18rem">
            <div style="border-radius:1rem;border:1px solid #e5e7eb;background:#fff;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <div style="padding:0.625rem 0.75rem;border-bottom:1px solid #f3f4f6">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.5rem">
                        <span style="font-size:0.75rem;font-weight:700;color:#111827;display:flex;align-items:center;gap:0.375rem">
                            <x-heroicon-m-user-group style="width:14px;height:14px;color:#6366f1" />
                            Agents
                        </span>
                        <span style="font-size:0.75rem;font-weight:700;padding:0.125rem 0.5rem;border-radius:9999px;background:rgba(99,102,241,0.1);color:#6366f1">{{ $s['agentsLocalises'] }}</span>
                    </div>
                    <div style="position:relative">
                        <input wire:model.live.debounce.300ms="rechercheAgent" type="text" placeholder="Rechercher..."
                            style="width:100%;padding:0.375rem 0.5rem 0.375rem 1.75rem;font-size:0.75rem;border-radius:0.5rem;border:1px solid #e5e7eb;background:#f9fafb;color:#111827;outline:none;transition:all 0.15s">
                        <svg style="width:0.75rem;height:0.75rem;position:absolute;left:0.5rem;top:50%;transform:translateY(-50%);color:#9ca3af" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
                <div class="ca-scroll" style="max-height:16rem;overflow-y:auto">
                    @php $agents = $this->getAgentsPosition(); @endphp
                    @forelse($agents as $a)
                        @php $isDispo = $a['disponible']; @endphp
                        <div style="display:flex;align-items:center;gap:0.625rem;padding:0.5rem 0.75rem;cursor:pointer;transition:background 0.15s;border-bottom:1px solid #f9fafb"
                             onclick="caFocusAgent({{ $a['lat'] }},{{ $a['lng'] }})">
                            <div style="position:relative;flex-shrink:0">
                                <div style="width:1.75rem;height:1.75rem;border-radius:9999px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.625rem;font-weight:700;background:{{ $isDispo ? '#10b981' : '#ef4444' }}">
                                    {{ strtoupper(substr($a['prenom']??'A',0,1)) }}
                                </div>
                                <span style="position:absolute;bottom:-0.125rem;right:-0.125rem;width:0.625rem;height:0.625rem;border-radius:9999px;border:2px solid #fff;background:{{ $isDispo ? '#10b981' : '#ef4444' }}"></span>
                            </div>
                            <div style="flex:1;min-width:0">
                                <p style="font-size:0.75rem;font-weight:600;color:#111827;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;line-height:1.25">{{ $a['prenom'] }} {{ $a['nom'] }}</p>
                                <p style="font-size:0.625rem;color:#9ca3af;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;line-height:1.25">{{ $a['zone'] }}</p>
                                <select x-on:change="$wire.affecterZone({{ $a['id'] }}, $el.value)" onclick="event.stopPropagation()"
                                    style="margin-top:0.125rem;width:100%;font-size:0.625rem;border-radius:0.25rem;border:1px solid #e5e7eb;background:#fff;color:#4b5563;padding:0.125rem 0.25rem;outline:none;cursor:pointer">
                                    <option value="">— Zone —</option>
                                    @foreach($this->getZones() as $z)
                                        <option value="{{ $z->id }}" @selected($a['zone_id']===$z->id)>{{ $z->nomZone }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span style="font-size:0.625rem;font-weight:500;padding:0.125rem 0.375rem;border-radius:0.25rem;white-space:nowrap;{{ $isDispo ? 'background:rgba(16,185,129,0.1);color:#059669' : 'background:rgba(239,68,68,0.1);color:#dc2626' }}">
                                {{ $isDispo ? 'Dispo' : 'Occ' }}
                            </span>
                        </div>
                    @empty
                        <div style="padding:2.5rem 0;text-align:center;font-size:0.75rem;color:#9ca3af">Aucun agent localisé</div>
                    @endforelse
                </div>
            </div>

            <div style="border-radius:1rem;border:1px solid #e5e7eb;background:#fff;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.06)">
                <div style="padding:0.625rem 0.75rem;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between">
                    <span style="font-size:0.75rem;font-weight:700;color:#111827;display:flex;align-items:center;gap:0.375rem">
                        <x-heroicon-m-flag style="width:14px;height:14px;color:#f59e0b" />
                        Signalements
                    </span>
                    <span style="font-size:0.75rem;font-weight:700;padding:0.125rem 0.5rem;border-radius:9999px;background:rgba(245,158,11,0.1);color:#d97706">{{ $this->getSignalementsPosition()->count() }}</span>
                </div>
                <div class="ca-scroll" style="max-height:16rem;overflow-y:auto">
                    @php $sigs = $this->getSignalementsPosition(); @endphp
                    @forelse($sigs as $sig)
                        @php
                            $st = $sig['statut'];
                            $dotClr = $st === 'enAttente' ? '#f59e0b' : ($st === 'enCours' ? '#06b6d4' : '#10b981');
                            $badgeBg = $st === 'enAttente' ? '#fef3c7' : ($st === 'enCours' ? '#cffafe' : '#d1fae5');
                            $badgeTxt = $st === 'enAttente' ? '#92400e' : ($st === 'enCours' ? '#0e7490' : '#065f46');
                            $badgeLbl = $st === 'enAttente' ? 'Attente' : ($st === 'enCours' ? 'En cours' : 'Terminé');
                        @endphp
                        <div style="display:flex;align-items:flex-start;gap:0.625rem;padding:0.625rem 0.75rem;cursor:pointer;transition:background 0.15s;border-bottom:1px solid #f9fafb"
                             onclick="caFocusSig({{ $sig['lat'] }},{{ $sig['lng'] }},{{ $sig['id'] }})">
                            <div style="margin-top:0.25rem;flex-shrink:0;width:0.625rem;height:0.625rem;border-radius:9999px;background:{{ $dotClr }};{{ $sig['priorite']==='critique' ? 'box-shadow:0 0 0 2px #dc2626' : '' }}"></div>
                            <div style="flex:1;min-width:0">
                                <p style="font-size:0.75rem;font-weight:600;color:#111827;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;line-height:1.25">{{ $sig['position'] }}</p>
                                <p style="font-size:0.625rem;color:#6b7280;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;line-height:1.25">{{ $sig['categorie'] }}</p>
                                <div style="display:flex;align-items:center;gap:0.375rem;margin-top:0.125rem">
                                    @if($sig['priorite']==='critique')
                                        <span style="font-size:0.5625rem;font-weight:700;padding:0.125rem 0.375rem;border-radius:0.25rem;background:#ef4444;color:#fff">CRITIQUE</span>
                                    @endif
                                    <span style="font-size:0.5625rem;font-weight:500;padding:0.125rem 0.375rem;border-radius:9999px;background:{{ $badgeBg }};color:{{ $badgeTxt }}">{{ $badgeLbl }}</span>
                                </div>
                                @if($sig['agent'])
                                    <p style="font-size:0.625rem;font-weight:500;color:#0891b2;margin-top:0.125rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $sig['agent'] }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="padding:2.5rem 0;text-align:center;font-size:0.75rem;color:#9ca3af">Aucun signalement</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
var caMap = null, caAgents = {}, caSigs = {};
var _agents = @json($this->getAgentsPosition());
var _sigs   = @json($this->getSignalementsPosition());

function caAgentIcon(dispo) {
    var c = dispo ? '#10b981' : '#ef4444';
    var r = dispo ? 'rgba(16,185,129,.35)' : 'rgba(239,68,68,.35)';
    return L.divIcon({
        html: '<div style="width:14px;height:14px;background:'+c+';border:3px solid #fff;border-radius:50%;box-shadow:0 0 0 3px '+r+',0 2px 8px rgba(0,0,0,.18)"></div>',
        iconSize:[14,14], iconAnchor:[7,7], className:''
    });
}

function caSigIcon(statut, prio) {
    var cols = {enAttente:'#f59e0b', enCours:'#06b6d4', terminer:'#10b981'};
    var c    = cols[statut] || '#94a3b8';
    var sz   = prio==='critique' ? 16 : 13;
    var bord = prio==='critique' ? '3px solid #dc2626' : '2.5px solid #fff';
    return L.divIcon({
        html: '<div style="width:'+sz+'px;height:'+sz+'px;background:'+c+';border:'+bord+';border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,.2)"></div>',
        iconSize:[sz,sz], iconAnchor:[sz/2,sz/2], className:''
    });
}

function updateAgents(list) {
    if (!caMap) return;
    var seen = {};
    list.forEach(function(a) {
        seen[a.id] = 1;
        if (caAgents[a.id]) { caAgents[a.id].setLatLng([a.lat,a.lng]).setIcon(caAgentIcon(a.disponible)); }
        else {
            var m = L.marker([a.lat,a.lng],{icon:caAgentIcon(a.disponible),zIndexOffset:1000}).addTo(caMap);
            m.bindPopup(
                '<div style="font-family:system-ui,sans-serif;line-height:1.4">'+
                '<b style="font-size:13px">'+a.prenom+' '+a.nom+'</b><br>'+
                '<span style="color:#6b7280;font-size:11px">'+a.zone+'</span><br>'+
                '<span style="color:'+(a.disponible?'#059669':'#dc2626')+';font-size:11px;font-weight:600">'+(a.disponible?'Disponible':'Occupé')+'</span>'+
                (a.pointer?'<br><span style="color:#6366f1;font-size:10px">&#10003; Pointé aujourd\'hui</span>':'')+
                '</div>', {maxWidth:200, className:''});
            caAgents[a.id] = m;
        }
    });
    Object.keys(caAgents).forEach(function(id){ if(!seen[id]){ caMap.removeLayer(caAgents[id]); delete caAgents[id]; } });
}

function updateSigs(list) {
    if (!caMap) return;
    var seen = {};
    list.forEach(function(s) {
        seen[s.id] = 1;
        if (caSigs[s.id]) { caSigs[s.id].setLatLng([s.lat,s.lng]).setIcon(caSigIcon(s.statut,s.priorite)); }
        else {
            var m = L.marker([s.lat,s.lng],{icon:caSigIcon(s.statut,s.priorite)}).addTo(caMap);
            var lbl = {enAttente:'En attente',enCours:'En cours',terminer:'Terminé'};
            m.bindPopup(
                '<div style="font-family:system-ui,sans-serif;line-height:1.4">'+
                '<b style="font-size:13px">'+s.position+'</b><br>'+
                '<span style="color:#6b7280;font-size:11px">'+s.categorie+'</span>'+
                (s.agent?'<br><span style="color:#0891b2;font-size:11px">→ '+s.agent+'</span>':'')+
                '<br><span style="color:#9ca3af;font-size:10px">'+s.date+'</span>'+
                '</div>', {maxWidth:220, className:''});
            caSigs[s.id] = m;
        }
    });
    Object.keys(caSigs).forEach(function(id){ if(!seen[id]){ caMap.removeLayer(caSigs[id]); delete caSigs[id]; } });
}

function caFocusAgent(lat, lng) { if(caMap) caMap.setView([lat,lng],15,{animate:true}); }

function caFocusSig(lat,lng,id)  { if(!caMap) return; caMap.setView([lat,lng],15,{animate:true}); setTimeout(function(){ if(caSigs[id]) caSigs[id].openPopup(); },350); }

function initCaMap() {
    if (typeof L === 'undefined') { setTimeout(initCaMap, 200); return; }
    var el = document.getElementById('ca-map');
    if (!el || el._leaflet_id) return;
    caMap = L.map(el, {center:[14.5,-14.5],zoom:7,minZoom:6,maxBounds:[[12,-18],[17,-11]],maxBoundsViscosity:1});
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'&copy; OpenStreetMap',maxZoom:19}).addTo(caMap);
    updateAgents(_agents);
    updateSigs(_sigs);
    var pts = [];
    _agents.forEach(function(a){ if(a.lat&&a.lng) pts.push([a.lat,a.lng]); });
    _sigs.forEach(function(s){ if(s.lat&&s.lng) pts.push([s.lat,s.lng]); });
    if (pts.length) caMap.fitBounds(pts,{padding:[50,50],maxZoom:13});
    setTimeout(function(){ caMap && caMap.invalidateSize(); }, 250);
}

document.addEventListener('DOMContentLoaded', initCaMap);
document.addEventListener('livewire:navigated', function(){ caMap=null; caAgents={}; caSigs={}; setTimeout(initCaMap,180); });
Livewire.on('mapDataRefreshed', function(d){
    var data = Array.isArray(d)?d[0]:d;
    if(data&&data.agents)       updateAgents(data.agents);
    if(data&&data.signalements) updateSigs(data.signalements);
});
</script>

</x-filament-panels::page>
