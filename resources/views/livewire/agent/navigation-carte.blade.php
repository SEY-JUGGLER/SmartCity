<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-950 dark:to-gray-900">
    @include('livewire.partials.agent-nav')
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush
    <main class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4 text-slate-900 dark:text-white">Carte interactive</h1>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl border border-slate-200 dark:border-gray-800 overflow-hidden">
                <div wire:ignore id="agent-map" class="h-[450px]"></div>
            </div>
            <div class="space-y-4">
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-4 border border-slate-200 dark:border-gray-800">
                    <h3 class="font-semibold mb-2">Ma position</h3>
                    @if($lat && $lng)<p class="text-xs text-slate-500 mb-2">Lat: {{ number_format($lat, 4) }}, Lng: {{ number_format($lng, 4) }}</p>@endif
                    @if(!$partageActif)
                        <button wire:click="activerPartage" onclick="demarrerEnvoiPosition()" class="w-full px-3 py-2 bg-orange-500 text-white text-sm rounded-lg">Activer le partage</button>
                    @else
                        <button wire:click="desactiverPartage" onclick="arreterEnvoiPosition()" class="w-full px-3 py-2 bg-red-500 text-white text-sm rounded-lg">Arrêter le partage</button>
                    @endif
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-4 border border-slate-200 dark:border-gray-800">
                    <h3 class="font-semibold mb-2">Missions ({{ count($missions) }})</h3>
                    @forelse($missions as $m)
                        <p class="text-xs py-1 border-b border-slate-100 dark:border-gray-800">{{ $m['position'] }} — {{ $m['categorie'] }}</p>
                    @empty
                        <p class="text-xs text-slate-500">Aucune mission active</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    var map, agentMarker, watchId = null;
    function renderMap() {
        if (typeof L === 'undefined') { setTimeout(renderMap, 200); return; }
        var mapEl = document.getElementById('agent-map');
        if (!mapEl || mapEl._map) return;
        map = L.map(mapEl).setView([14.7167, -17.4677], 12);
        mapEl._map = map;
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap', maxZoom: 18 }).addTo(map);
        var missions = @json($missions);
        missions.forEach(function (m) {
            if (!m.lat || !m.lng) return;
            L.marker([m.lat, m.lng]).addTo(map).bindPopup('<b>' + m.position + '</b>');
        });
        @if($lat && $lng)
            agentMarker = L.marker([{{ $lat }}, {{ $lng }}]).addTo(map).bindPopup('Ma position');
        @endif
    }
    function demarrerEnvoiPosition() {
        if (!navigator.geolocation) return;
        watchId = navigator.geolocation.watchPosition(function (pos) {
            @this.sauvegarderPosition(pos.coords.latitude, pos.coords.longitude);
            if (agentMarker) agentMarker.setLatLng([pos.coords.latitude, pos.coords.longitude]);
        }, null, { enableHighAccuracy: true });
    }
    function arreterEnvoiPosition() { if (watchId) navigator.geolocation.clearWatch(watchId); }
    document.addEventListener('DOMContentLoaded', renderMap);
    </script>
    @endpush
</div>
