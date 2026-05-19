<x-filament-panels::page>
    @pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .pulse-dot { animation: pulse 2s infinite; }
        @keyframes pulse { 0%{opacity:1} 50%{opacity:0.4} 100%{opacity:1} }
    </style>
    @endPushOnce

    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Agents en mission</h3>
            <span class="text-xs text-gray-500" wire:poll.10s>⟳ Mise à jour automatique</span>
        </div>
        <div wire:ignore id="admin-agents-map" class="h-[550px]"></div>
    </div>

    {{-- Liste des agents --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3" wire:poll.10s>
        @php $agents = $this->getAgentsPosition(); @endphp
        @forelse($agents as $agent)
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3.5 py-3 flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full {{ $agent['disponible'] ? 'bg-emerald-500 pulse-dot' : 'bg-red-500' }} shrink-0"></span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $agent['prenom'] }} {{ $agent['nom'] }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $agent['zone'] }} · {{ $agent['date'] }}</p>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-8 text-sm text-gray-500">
                Aucun agent n'a partagé sa position pour le moment
            </div>
        @endforelse
    </div>

    @pushOnce('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    var adminMap = null;
    var agentMarkers = {};

    function initAdminMap() {
        if (typeof L === 'undefined') { setTimeout(initAdminMap, 300); return; }
        var el = document.getElementById('admin-agents-map');
        if (!el || el._map) return;

        adminMap = L.map(el).setView([14.7167, -17.4677], 12);
        el._map = adminMap;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap',
            maxZoom: 18,
        }).addTo(adminMap);

        placerAgents();
    }

    function placerAgents() {
        var agents = @json($this->getAgentsPosition());
        var bounds = [];

        agents.forEach(function (a) {
            var latlng = [a.lat, a.lng];
            bounds.push(latlng);

            var icon = L.divIcon({
                html: '<div style="width:16px;height:16px;background:' + (a.disponible ? '#10b981' : '#ef4444') + ';border:3px solid white;border-radius:50%;box-shadow:0 0 0 3px rgba(16,185,129,0.2)"><span class="pulse-dot"></span></div>',
                iconSize: [16, 16],
                iconAnchor: [8, 8],
                className: '',
            });

            if (agentMarkers[a.id]) {
                agentMarkers[a.id].setLatLng(latlng);
            } else {
                var marker = L.marker(latlng, { icon: icon }).addTo(adminMap);
                marker.bindPopup(
                    '<b>' + a.prenom + ' ' + a.nom + '</b><br>' +
                    'Zone: ' + a.zone + '<br>' +
                    'Dernière position: ' + a.date
                );
                agentMarkers[a.id] = marker;
            }
        });

        if (bounds.length) {
            adminMap.fitBounds(bounds, { padding: [30, 30] });
        }
    }

    document.addEventListener('DOMContentLoaded', initAdminMap);

    document.addEventListener('livewire:navigated', function () {
        if (adminMap) setTimeout(function() { adminMap.invalidateSize(); placerAgents(); }, 100);
    });

    setInterval(function() {
        if (adminMap) placerAgents();
    }, 10000);
    </script>
    @endPushOnce
</x-filament-panels::page>
