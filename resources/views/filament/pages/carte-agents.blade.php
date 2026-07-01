<x-filament-panels::page>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .ca-row:hover { background:rgba(0,0,0,.025); }
    .dark .ca-row:hover { background:rgba(255,255,255,.04); }
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
    .ca-filt-btn { padding:4px 10px; border-radius:6px; font-size:11px; font-weight:600; transition:all .15s; cursor:pointer; border:none; }
    .sidebar-enter { transition:all .3s cubic-bezier(.4,0,.2,1); }
    .sidebar-enter.sidebar-closed { max-width:0 !important; overflow:hidden; padding:0 !important; margin:0 !important; opacity:0; }
</style>

@php $s = $this->getStats(); @endphp

{{-- Stats cards --}}
<div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-4">
    @php
        $statItems = [
            ['label'=>'Disponibles', 'val'=>$s['agentsActifs'], 'icon'=>'heroicon-m-check-circle', 'color'=>'emerald'],
            ['label'=>'Occupés',     'val'=>$s['agentsOccupes'], 'icon'=>'heroicon-m-briefcase',   'color'=>'red'],
            ['label'=>'Sur la carte','val'=>$s['agentsLocalises'], 'icon'=>'heroicon-m-map-pin',  'color'=>'indigo'],
            ['label'=>'En attente',  'val'=>$s['sigEnAttente'], 'icon'=>'heroicon-m-clock',       'color'=>'amber'],
            ['label'=>'En cours',    'val'=>$s['sigEnCours'],   'icon'=>'heroicon-m-arrow-path',  'color'=>'cyan'],
        ];
        $colors = [
            'emerald' => ['bg'=>'bg-emerald-50 dark:bg-emerald-900/20','dot'=>'bg-emerald-500','text'=>'text-emerald-600 dark:text-emerald-400','border'=>'border-emerald-200 dark:border-emerald-800','num'=>'text-emerald-600 dark:text-emerald-300'],
            'red'     => ['bg'=>'bg-red-50 dark:bg-red-900/20','dot'=>'bg-red-500','text'=>'text-red-600 dark:text-red-400','border'=>'border-red-200 dark:border-red-800','num'=>'text-red-600 dark:text-red-300'],
            'indigo'  => ['bg'=>'bg-indigo-50 dark:bg-indigo-900/20','dot'=>'bg-indigo-500','text'=>'text-indigo-600 dark:text-indigo-400','border'=>'border-indigo-200 dark:border-indigo-800','num'=>'text-indigo-600 dark:text-indigo-300'],
            'amber'   => ['bg'=>'bg-amber-50 dark:bg-amber-900/20','dot'=>'bg-amber-500','text'=>'text-amber-600 dark:text-amber-400','border'=>'border-amber-200 dark:border-amber-800','num'=>'text-amber-600 dark:text-amber-300'],
            'cyan'    => ['bg'=>'bg-cyan-50 dark:bg-cyan-900/20','dot'=>'bg-cyan-500','text'=>'text-cyan-600 dark:text-cyan-400','border'=>'border-cyan-200 dark:border-cyan-800','num'=>'text-cyan-600 dark:text-cyan-300'],
        ];
    @endphp
    @foreach($statItems as $st)
        @php $c = $colors[$st['color']]; @endphp
        <div class="rounded-xl border {{ $c['border'] }} {{ $c['bg'] }} p-3.5 transition-shadow hover:shadow-md">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[11px] font-semibold uppercase tracking-wider {{ $c['text'] }}">{{ $st['label'] }}</span>
                <span class="w-2 h-2 rounded-full {{ $c['dot'] }} flex-shrink-0"></span>
            </div>
            <p class="text-2xl font-black {{ $c['num'] }} leading-none">{{ $st['val'] }}</p>
        </div>
    @endforeach
</div>

{{-- Map + Sidebar --}}
<div class="flex gap-4">
    {{-- Map container --}}
    <div class="flex-1 min-w-0 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden shadow-sm">
        <div class="px-3 py-2 border-b border-gray-100 dark:border-gray-800 flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-1.5 flex-wrap">
                <span class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Signalements :</span>
                @foreach(['actifs'=>'Actifs','attente'=>'Attente','cours'=>'En cours','tous'=>'Tous'] as $val=>$label)
                    <button wire:click="setFiltreStatut('{{ $val }}')"
                        class="ca-filt-btn {{ $filtreStatut===$val ? 'text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700' }}"
                        style="{{ $filtreStatut===$val ? 'background:#6366f1;color:#fff' : '' }}">{{ $label }}</button>
                @endforeach
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="toggleSidebar"
                    class="flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                    title="Afficher/Masquer le panneau">
                    <svg style="width:14px;height:14px;color:#6b7280" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sidebarOuverte ? 'M9 5l7 7-7 7' : 'M15 19l-7-7 7-7' }}"/>
                    </svg>
                </button>
                <div class="flex items-center gap-1.5 text-[11px] text-gray-500 dark:text-gray-400" wire:poll.10s="refreshMapData">
                    <span class="relative flex w-2 h-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full w-2 h-2 bg-emerald-500"></span>
                    </span>
                    Temps réel
                </div>
            </div>
        </div>
        <div wire:ignore id="ca-map"></div>
        <div class="px-3 py-1.5 border-t border-gray-100 dark:border-gray-800 flex flex-wrap gap-x-4 gap-y-0.5">
            <span class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 mr-1 uppercase">Légende :</span>
            <span class="flex items-center gap-1 text-[10px] text-gray-500 dark:text-gray-400"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>Dispo</span>
            <span class="flex items-center gap-1 text-[10px] text-gray-500 dark:text-gray-400"><span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>Occupé</span>
            <span class="flex items-center gap-1 text-[10px] text-gray-500 dark:text-gray-400"><span class="w-2.5 h-2.5 rounded-full bg-amber-500 border-2 border-amber-600 inline-block"></span>Attente</span>
            <span class="flex items-center gap-1 text-[10px] text-gray-500 dark:text-gray-400"><span class="w-2.5 h-2.5 rounded-full bg-cyan-500 border-2 border-cyan-600 inline-block"></span>En cours</span>
            <span class="flex items-center gap-1 text-[10px] text-gray-500 dark:text-gray-400"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 border-2 border-emerald-600 inline-block"></span>Terminé</span>
            <span class="flex items-center gap-1 text-[10px] text-gray-500 dark:text-gray-400"><span class="w-2.5 h-2.5 rounded-full bg-white border-2 border-red-500 inline-block"></span>Critique</span>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="sidebar-enter {{ $sidebarOuverte ? 'w-72' : 'sidebar-closed w-0' }} flex-shrink-0">
        <div class="flex flex-col gap-3 w-72">
            {{-- Agents --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden shadow-sm">
                <div class="px-3 py-2.5 border-b border-gray-100 dark:border-gray-800">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-gray-900 dark:text-white flex items-center gap-1.5">
                            <x-heroicon-m-user-group style="width:14px;height:14px" class="text-indigo-500" />
                            Agents
                        </span>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300">{{ $s['agentsLocalises'] }}</span>
                    </div>
                    <div class="relative">
                        <input wire:model.live.debounce.300ms="rechercheAgent" type="text" placeholder="Rechercher..."
                            class="w-full pl-7 pr-2 py-1.5 text-xs rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-all">
                        <svg style="width:12px;height:12px;position:absolute;left:7px;top:50%;transform:translateY(-50%);color:#9ca3af" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
                <div class="ca-scroll max-h-64 overflow-y-auto divide-y divide-gray-50 dark:divide-gray-800">
                    @php $agents = $this->getAgentsPosition(); @endphp
                    @forelse($agents as $a)
                        <div class="ca-row px-3 py-2 flex items-center gap-2.5 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors"
                             onclick="caFocusAgent({{ $a['lat'] }},{{ $a['lng'] }})">
                            <div class="relative flex-shrink-0">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-[10px] font-bold"
                                     style="background:{{ $a['disponible']?'#10b981':'#ef4444' }}">
                                    {{ strtoupper(substr($a['prenom']??'A',0,1)) }}
                                </div>
                                <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full border-2 border-white dark:border-gray-900"
                                      style="background:{{ $a['disponible']?'#10b981':'#ef4444' }}"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-900 dark:text-white truncate leading-tight">{{ $a['prenom'] }} {{ $a['nom'] }}</p>
                                <p class="text-[10px] text-gray-400 truncate leading-tight">{{ $a['zone'] }}</p>
                                <select x-on:change="$wire.affecterZone({{ $a['id'] }}, $el.value)" onclick="event.stopPropagation()"
                                    class="mt-0.5 w-full text-[10px] rounded border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 py-0.5 px-1 focus:outline-none focus:border-indigo-400 cursor-pointer">
                                    <option value="">— Zone —</option>
                                    @foreach($this->getZones() as $z)
                                        <option value="{{ $z->id }}" @selected($a['zone_id']===$z->id)>{{ $z->nomZone }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded {{ $a['disponible'] ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300' }}">
                                {{ $a['disponible'] ? 'Dispo' : 'Occ' }}
                            </span>
                        </div>
                    @empty
                        <div class="py-10 text-center text-xs text-gray-400 dark:text-gray-500">Aucun agent localisé</div>
                    @endforelse
                </div>
            </div>

            {{-- Signalements --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden shadow-sm">
                <div class="px-3 py-2.5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-900 dark:text-white flex items-center gap-1.5">
                        <x-heroicon-m-flag style="width:14px;height:14px" class="text-amber-500" />
                        Signalements
                    </span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300">{{ $this->getSignalementsPosition()->count() }}</span>
                </div>
                <div class="ca-scroll max-h-64 overflow-y-auto divide-y divide-gray-50 dark:divide-gray-800">
                    @php $sigs = $this->getSignalementsPosition(); @endphp
                    @forelse($sigs as $sig)
                        <div class="ca-row px-3 py-2.5 flex items-start gap-2.5 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors"
                             onclick="caFocusSig({{ $sig['lat'] }},{{ $sig['lng'] }},{{ $sig['id'] }})">
                            <div class="mt-0.5 flex-shrink-0 w-2.5 h-2.5 rounded-full"
                                 style="background:{{ $sig['statut']==='enAttente'?'#f59e0b':($sig['statut']==='enCours'?'#06b6d4':'#10b981') }};{{ $sig['priorite']==='critique' ? 'box-shadow:0 0 0 2px #dc2626' : '' }}">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-900 dark:text-white truncate leading-tight">{{ $sig['position'] }}</p>
                                <p class="text-[10px] text-gray-500 truncate leading-tight">{{ $sig['categorie'] }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    @if($sig['priorite']==='critique')
                                        <span class="text-[9px] font-bold px-1 py-0.5 rounded bg-red-500 text-white">CRITIQUE</span>
                                    @endif
                                    <span class="text-[9px] font-medium px-1.5 py-0.5 rounded-full"
                                          style="background:{{ $sig['statut']==='enAttente'?'#fef3c7':($sig['statut']==='enCours'?'#cffafe':'#d1fae5') }};color:{{ $sig['statut']==='enAttente'?'#92400e':($sig['statut']==='enCours'?'#0e7490':'#065f46') }}">
                                        {{ match($sig['statut']) { 'enAttente'=>'Attente', 'enCours'=>'En cours', 'terminer'=>'Terminé', default=>$sig['statut'] } }}
                                    </span>
                                </div>
                                @if($sig['agent'])
                                    <p class="text-[10px] font-medium text-cyan-600 dark:text-cyan-400 mt-0.5 truncate">{{ $sig['agent'] }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center text-xs text-gray-400 dark:text-gray-500">Aucun signalement</div>
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