<x-filament-panels::page>
    @pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .agent-pulse { animation: pulse 2s infinite; }
        @keyframes pulse { 0%{opacity:1} 50%{opacity:0.5} 100%{opacity:1} }
    </style>
    @endPushOnce

    @php $missions = $this->getMissions(); @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2">
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Carte des missions</h3>
                    <div class="flex items-center gap-2">
                        @if($partageActif)
                            <span class="flex items-center gap-1 text-xs text-emerald-600">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 agent-pulse"></span> Partage actif
                            </span>
                        @endif
                        <button wire:click="$refresh" class="text-xs text-gray-500 hover:text-gray-700">⟳</button>
                    </div>
                </div>
                <div wire:ignore id="agent-map" class="h-[450px]"></div>
            </div>
        </div>

        <div class="space-y-3">
            {{-- Partage de position --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Ma position</h3>
                @if($lat && $lng)
                    <p class="text-xs text-gray-500 mb-2">
                        Lat : {{ number_format($lat, 4) }}<br>
                        Lng : {{ number_format($lng, 4) }}
                    </p>
                @endif
                <div class="flex gap-2">
                    @if(!$partageActif)
                        <button wire:click="activerPartage" onclick="demarrerEnvoiPosition()" class="flex-1 px-3 py-2 bg-primary-500 hover:bg-primary-600 text-white text-xs font-medium rounded-lg transition-all">
                            Activer le partage
                        </button>
                    @else
                        <button wire:click="desactiverPartage" onclick="arreterEnvoiPosition()" class="flex-1 px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-lg transition-all">
                            Arrêter le partage
                        </button>
                    @endif
                </div>
            </div>

            {{-- Missions actives --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Missions ({{ count($missions) }})</h3>
                @forelse($missions as $m)
                <div class="flex items-start gap-2 py-2 border-b border-gray-50 dark:border-gray-800 last:border-0">
                    <span class="w-2 h-2 mt-1.5 rounded-full shrink-0" style="background: {{ match($m['priorite']) { 'critique' => '#ef4444', 'moyenne' => '#f59e0b', 'faible' => '#10b981', default => '#6b7280' } }}"></span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ $m['position'] }}</p>
                        <p class="text-xs text-gray-500">{{ $m['categorie'] }} · {{ $m['priorite'] }}</p>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-500 text-center py-4">Aucune mission active</p>
                @endforelse
            </div>
        </div>
    </div>

    @pushOnce('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    var map, agentMarker, watchId = null;

    function renderMap() {
        if (typeof L === 'undefined') { setTimeout(renderMap, 200); return; }
        var mapEl = document.getElementById('agent-map');
        if (!mapEl || mapEl._map) return;

        map = L.map(mapEl).setView([14.7167, -17.4677], 12);
        mapEl._map = map;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap',
            maxZoom: 18,
        }).addTo(map);

        var missions = @json($missions);
        var bounds = [];

        missions.forEach(function (m) {
            if (!m.lat || !m.lng) return;
            var latlng = [m.lat, m.lng];
            bounds.push(latlng);

            var color = m.priorite === 'critique' ? '#ef4444'
                     : m.priorite === 'moyenne' ? '#f59e0b'
                     : '#10b981';

            var icon = L.divIcon({
                html: '<div style="width:14px;height:14px;background:'+color+';border:2px solid white;border-radius:50%;box-shadow:0 1px 3px rgba(0,0,0,0.3)"></div>',
                iconSize: [14, 14],
                iconAnchor: [7, 7],
                className: '',
            });

            var marker = L.marker(latlng, { icon: icon }).addTo(map);
            marker.bindPopup(
                '<b>' + m.position + '</b><br>' +
                m.categorie + ' — ' + m.priorite
            );
        });

        @if($lat && $lng)
            var agentIcon = L.divIcon({
                html: '<div style="width:16px;height:16px;background:#3b82f6;border:3px solid white;border-radius:50%;box-shadow:0 0 0 3px rgba(59,130,246,0.3)"><span class="agent-pulse"></span></div>',
                iconSize: [16, 16],
                iconAnchor: [8, 8],
                className: '',
            });
            agentMarker = L.marker([{{ $lat }}, {{ $lng }}], { icon: agentIcon }).addTo(map);
            agentMarker.bindPopup('<b>Ma position</b>');
            bounds.push([{{ $lat }}, {{ $lng }}]);
        @endif

        if (bounds.length) {
            map.fitBounds(bounds, { padding: [40, 40] });
        }

        document.addEventListener('livewire:navigated', function () {
            if (map) map.invalidateSize();
        });
    }

    function demarrerEnvoiPosition() {
        if (!navigator.geolocation) {
            alert('La géolocalisation n\'est pas supportée par votre navigateur.');
            return;
        }
        watchId = navigator.geolocation.watchPosition(function (pos) {
            var lat = pos.coords.latitude;
            var lng = pos.coords.longitude;
            @this.sauvegarderPosition(lat, lng);

            if (agentMarker) {
                agentMarker.setLatLng([lat, lng]);
            } else {
                var agentIcon = L.divIcon({
                    html: '<div style="width:16px;height:16px;background:#3b82f6;border:3px solid white;border-radius:50%;box-shadow:0 0 0 3px rgba(59,130,246,0.3)"></div>',
                    iconSize: [16, 16],
                    iconAnchor: [8, 8],
                    className: '',
                });
                agentMarker = L.marker([lat, lng], { icon: agentIcon }).addTo(map);
                agentMarker.bindPopup('<b>Ma position</b>');
            }
        }, function (err) {
            console.warn('Erreur géolocalisation:', err.message);
        }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 30000 });
    }

    function arreterEnvoiPosition() {
        if (watchId !== null) {
            navigator.geolocation.clearWatch(watchId);
            watchId = null;
        }
    }

    document.addEventListener('DOMContentLoaded', renderMap);
    </script>
    @endPushOnce
</x-filament-panels::page>
