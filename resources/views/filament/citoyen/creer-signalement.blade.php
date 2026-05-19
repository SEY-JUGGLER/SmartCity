<x-filament-panels::page>
    @pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endPushOnce

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <form wire:submit="submit">
                {{ $this->form }}
                <div class="mt-6 flex gap-3">
                    {{ $this->submitAction }}
                </div>
            </form>
        </div>
        <div class="lg:col-span-1">
            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">Localisation</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Cliquez sur la carte pour définir la position exacte du problème.</p>
                <div wire:ignore id="map-picker" class="h-64 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600"></div>
                <div class="mt-3 grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Latitude</label>
                        <input type="text" id="lat-display" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-white" readonly placeholder="--">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Longitude</label>
                        <input type="text" id="lng-display" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-white" readonly placeholder="--">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @pushOnce('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    (function () {
        var map, marker;

        function setCoordinates(lat, lng) {
            var latFixed = lat.toFixed(6);
            var lngFixed = lng.toFixed(6);

            var latDisplay = document.getElementById('lat-display');
            var lngDisplay = document.getElementById('lng-display');

            if (latDisplay) latDisplay.value = latFixed;
            if (lngDisplay) lngDisplay.value = lngFixed;

            if (typeof @this !== 'undefined') {
                @this.set('data.latitude', lat);
                @this.set('data.longitude', lng);
            }
        }

        function initMapPicker() {
            if (typeof L === 'undefined') {
                setTimeout(initMapPicker, 200);
                return;
            }

            var mapEl = document.getElementById('map-picker');
            if (!mapEl || mapEl._map) {
                return;
            }

            map = L.map(mapEl).setView([14.7167, -17.4677], 12);
            mapEl._map = map;

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap',
                maxZoom: 18,
            }).addTo(map);

            map.on('click', function (e) {
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }

                setCoordinates(e.latlng.lat, e.latlng.lng);
            });

            setTimeout(function () {
                map.invalidateSize();
            }, 100);
        }

        document.addEventListener('DOMContentLoaded', initMapPicker);
        document.addEventListener('livewire:navigated', initMapPicker);
    })();
    </script>
    @endPushOnce
</x-filament-panels::page>
