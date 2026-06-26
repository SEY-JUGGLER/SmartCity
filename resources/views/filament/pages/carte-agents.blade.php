<x-filament-panels::page>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .ca-stat { transition:box-shadow .15s; }
    .ca-stat:hover { box-shadow:0 4px 14px rgba(0,0,0,.08); }
    .ca-row:hover  { background:rgba(0,0,0,.025); }
    .dark .ca-row:hover { background:rgba(255,255,255,.04); }
    .ca-scroll { scrollbar-width:thin; scrollbar-color:#d1d5db transparent; }
    .ca-scroll::-webkit-scrollbar { width:3px; }
    .ca-scroll::-webkit-scrollbar-thumb { background:#d1d5db; border-radius:4px; }
    .dark .ca-scroll { scrollbar-color:#4b5563 transparent; }
    .dark .ca-scroll::-webkit-scrollbar-thumb { background:#4b5563; }
    .ca-live::after { content:''; display:inline-block; width:7px; height:7px; border-radius:50%; background:#10b981; animation:liveRing 1.8s ease-out infinite; }
    @keyframes liveRing { 0%{transform:scale(1);opacity:.8} 100%{transform:scale(2.6);opacity:0} }
    #ca-map { height:520px; width:100%; }
    .leaflet-popup-content-wrapper { border-radius:10px !important; box-shadow:0 6px 24px rgba(0,0,0,.12) !important; }
    .leaflet-popup-content { margin:10px 14px !important; font-size:12px !important; }
    .dark .leaflet-popup-content-wrapper { background:#1f2937 !important; }
    .dark .leaflet-popup-content { color:#e5e7eb !important; }
    .ca-filt-btn { padding:4px 10px; border-radius:6px; font-size:11px; font-weight:600; transition:all .15s; cursor:pointer; border:none; }
</style>

@php $s = $this->getStats(); @endphp

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-4">
    @php
        $statItems = [
            ['label'=>'Disponibles',  'val'=>$s['agentsActifs'],   'color'=>'#10b981'],
            ['label'=>'Occupés',      'val'=>$s['agentsOccupes'],  'color'=>'#ef4444'],
            ['label'=>'Sur la carte',   'val'=>$s['agentsLocalises'],'color'=>'#6366f1'],
            ['label'=>'En attente',   'val'=>$s['sigEnAttente'],   'color'=>'#f59e0b'],
            ['label'=>'En cours',     'val'=>$s['sigEnCours'],     'color'=>'#06b6d4'],
        ];
    @endphp
    @foreach($statItems as $st)
        <div class="ca-stat rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 px-4 py-3">
            <div class="flex items-center gap-2 mb-1">
                <span style="width:8px;height:8px;border-radius:50%;background:{{ $st['color'] }};display:inline-block;flex-shrink:0"></span>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $st['label'] }}</span>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $st['val'] }}</p>
        </div>
    @endforeach
</div>

{{-- Layout --}}
<div class="grid grid-cols-1 lg:grid-cols-4 gap-4">

    {{-- Map --}}
    <div class="lg:col-span-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden">

        {{-- Toolbar --}}
        <div class="px-3 py-2 border-b border-gray-100 dark:border-gray-800 flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-1.5 flex-wrap">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Signalements :</span>
                @foreach(['actifs'=>'Actifs','attente'=>'Attente','cours'=>'En cours','tous'=>'Tous'] as $val=>$label)
                    <button wire:click="setFiltreStatut('{{ $val }}')"
                        class="ca-filt-btn {{ $filtreStatut===$val ? 'text-white' : 'text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700' }}"
                        style="{{ $filtreStatut===$val ? 'background:#6366f1;color:#fff' : '' }}">{{ $label }}</button>
                @endforeach
            </div>
            <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400" wire:poll.10s="refreshMapData">
                <span class="ca-live" style="width:7px;height:7px;border-radius:50%;background:#10b981;display:inline-block"></span>
                Temps réel
            </div>
        </div>

        <div wire:ignore id="ca-map"></div>

        {{-- Légende --}}
        <div class="px-3 py-2 border-t border-gray-100 dark:border-gray-800 flex flex-wrap gap-x-4 gap-y-1">
            <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 mr-1">Légende :</span>
            <span class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400"><span style="width:9px;height:9px;border-radius:50%;background:#10b981;display:inline-block"></span>Dispo</span>
            <span class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400"><span style="width:9px;height:9px;border-radius:50%;background:#ef4444;display:inline-block"></span>Occupé</span>
            <span class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400"><span style="width:10px;height:10px;border-radius:50%;background:#f59e0b;border:2px solid #d97706;display:inline-block"></span>Attente</span>
            <span class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400"><span style="width:10px;height:10px;border-radius:50%;background:#06b6d4;border:2px solid #0891b2;display:inline-block"></span>En cours</span>
            <span class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400"><span style="width:10px;height:10px;border-radius:50%;background:#10b981;border:2px solid #059669;display:inline-block"></span>Terminé</span>
            <span class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400"><span style="width:10px;height:10px;border-radius:50%;background:#fff;border:2px solid #dc2626;display:inline-block"></span>Critique</span>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="flex flex-col gap-3">

        {{-- Agents --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden">
            <div class="px-3 py-2.5 border-b border-gray-100 dark:border-gray-800">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-gray-900 dark:text-white">Agents sur la carte</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300">{{ $s['agentsLocalises'] }}</span>
                </div>
                <div class="relative">
                    <input wire:model.live.debounce.300ms="rechercheAgent" type="text" placeholder="Rechercher..."
                        class="w-full pl-6 pr-2 py-1.5 text-xs rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-all">
                    <svg style="width:11px;height:11px;position:absolute;left:8px;top:50%;transform:translateY(-50%);color:#9ca3af" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
            <div class="ca-scroll max-h-64 overflow-y-auto divide-y divide-gray-50 dark:divide-gray-800">
                @php $agents = $this->getAgentsPosition(); @endphp
                @forelse($agents as $a)
                    <div class="ca-row px-3 py-2 flex items-center gap-2 cursor-pointer"
                         onclick="caFocusAgent({{ $a['lat'] }},{{ $a['lng'] }})">
                        {{-- Avatar --}}
                        <div style="position:relative;flex-shrink:0">
                            <div style="width:28px;height:28px;border-radius:50%;background:{{ $a['disponible']?'#10b981':'#ef4444' }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:11px;font-weight:700">
                                {{ strtoupper(substr($a['prenom']??'A',0,1)) }}
                            </div>
                            <span style="position:absolute;bottom:-1px;right:-1px;width:8px;height:8px;border-radius:50%;background:{{ $a['disponible']?'#10b981':'#ef4444' }};border:2px solid #fff;display:block"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $a['prenom'] }} {{ $a['nom'] }}</p>
                            <select x-on:change="$wire.affecterZone({{ $a['id'] }}, $el.value)" onclick="event.stopPropagation()"
                                class="mt-0.5 w-full text-xs rounded border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 py-0.5 px-1 focus:outline-none focus:border-indigo-400 cursor-pointer">
                                <option value="">— Zone —</option>
                                @foreach($this->getZones() as $z)
                                    <option value="{{ $z->id }}" @selected($a['zone_id']===$z->id)>{{ $z->nomZone }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-gray-400 dark:text-gray-500">Aucun agent localisé</div>
                @endforelse
            </div>
        </div>

        {{-- Signalements --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden">
            <div class="px-3 py-2.5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <span class="text-xs font-bold text-gray-900 dark:text-white">Signalements</span>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300">{{ $this->getSignalementsPosition()->count() }}</span>
            </div>
            <div class="ca-scroll max-h-64 overflow-y-auto divide-y divide-gray-50 dark:divide-gray-800">
                @php $sigs = $this->getSignalementsPosition(); @endphp
                @forelse($sigs as $sig)
                    <div class="ca-row px-3 py-2 flex items-start gap-2 cursor-pointer"
                         onclick="caFocusSig({{ $sig['lat'] }},{{ $sig['lng'] }},{{ $sig['id'] }})">
                        <span style="margin-top:3px;width:8px;height:8px;border-radius:50%;background:{{ $sig['statut']==='enAttente'?'#f59e0b':($sig['statut']==='enCours'?'#06b6d4':'#10b981') }};border:{{ $sig['priorite']==='critique'?'2px solid #dc2626':'none' }};display:inline-block;flex-shrink:0"></span>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $sig['position'] }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $sig['categorie'] }}</p>
                            @if($sig['agent'])<p class="text-xs font-medium truncate" style="color:#0891b2">{{ $sig['agent'] }}</p>@endif
                        </div>
                        @if($sig['priorite']==='critique')
                            <span class="text-xs font-bold px-1 py-0.5 rounded flex-shrink-0" style="background:#ef4444;color:#fff;font-size:9px">CRIT</span>
                        @endif
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-gray-400 dark:text-gray-500">Aucun signalement</div>
                @endforelse
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
        html: '<div style="width:13px;height:13px;background:'+c+';border:2.5px solid #fff;border-radius:50%;box-shadow:0 0 0 3px '+r+',0 2px 6px rgba(0,0,0,.15)"></div>',
        iconSize:[13,13], iconAnchor:[6,6], className:''
    });
}
function caSigIcon(statut, prio) {
    var cols = {enAttente:'#f59e0b', enCours:'#06b6d4', terminer:'#10b981'};
    var c    = cols[statut] || '#94a3b8';
    var sz   = prio==='critique' ? 15 : 12;
    var bord = prio==='critique' ? '2px solid #dc2626' : '2px solid #fff';
    return L.divIcon({
        html: '<div style="width:'+sz+'px;height:'+sz+'px;background:'+c+';border:'+bord+';border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,.18)"></div>',
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
            m.bindPopup('<b>'+a.prenom+' '+a.nom+'</b><br><small style="color:#6b7280">'+a.zone+'</small><br><span style="color:'+(a.disponible?'#059669':'#dc2626')+';font-size:11px;font-weight:600">'+(a.disponible?'Disponible':'Occupé')+'</span>'+(a.pointer?'<br><span style="color:#6366f1;font-size:11px">&#10003; Pointé</span>':''), {maxWidth:180});
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
            m.bindPopup('<b>'+s.position+'</b><br><small style="color:#6b7280">'+s.categorie+'</small>'+(s.agent?'<br><span style="color:#0891b2;font-size:11px">'+s.agent+'</span>':'')+'<br><small style="color:#9ca3af">'+s.date+'</small>', {maxWidth:200});
            caSigs[s.id] = m;
        }
    });
    Object.keys(caSigs).forEach(function(id){ if(!seen[id]){ caMap.removeLayer(caSigs[id]); delete caSigs[id]; } });
}

function caFocusAgent(lat, lng) { if(caMap) caMap.setView([lat,lng],15,{animate:true}); }
function caFocusSig(lat,lng,id)  { if(!caMap) return; caMap.setView([lat,lng],15,{animate:true}); setTimeout(function(){ if(caSigs[id]) caSigs[id].openPopup(); },300); }

function initCaMap() {
    if (typeof L === 'undefined') { setTimeout(initCaMap, 200); return; }
    var el = document.getElementById('ca-map');
    if (!el || el._leaflet_id) return;
    caMap = L.map(el, {center:[14.5,-14.5],zoom:7,minZoom:6,maxBounds:[[12,-18],[17,-11]],maxBoundsViscosity:1});
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'© OpenStreetMap',maxZoom:19}).addTo(caMap);
    updateAgents(_agents);
    updateSigs(_sigs);
    var pts = [];
    _agents.forEach(function(a){ if(a.lat&&a.lng) pts.push([a.lat,a.lng]); });
    _sigs.forEach(function(s){ if(s.lat&&s.lng) pts.push([s.lat,s.lng]); });
    if (pts.length) caMap.fitBounds(pts,{padding:[50,50],maxZoom:13});
    setTimeout(function(){ caMap && caMap.invalidateSize(); }, 200);
}

document.addEventListener('DOMContentLoaded', initCaMap);
document.addEventListener('livewire:navigated', function(){ caMap=null; caAgents={}; caSigs={}; setTimeout(initCaMap,150); });
Livewire.on('mapDataRefreshed', function(d){
    var data = Array.isArray(d)?d[0]:d;
    if(data&&data.agents)       updateAgents(data.agents);
    if(data&&data.signalements) updateSigs(data.signalements);
});
</script>

</x-filament-panels::page>
