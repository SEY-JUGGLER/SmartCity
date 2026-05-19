<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-950 dark:to-gray-900">
    @include('livewire.partials.citoyen-nav')
    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush
    <main class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6 text-slate-900 dark:text-white">Créer un signalement</h1>
        <form wire:submit="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4 bg-white dark:bg-gray-900 rounded-2xl p-6 border border-slate-200 dark:border-gray-800">
                <div>
                    <label class="text-sm font-medium">Adresse *</label>
                    <input wire:model="position" class="w-full mt-1 rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800" placeholder="Ex: Rue de la République, Dakar">
                    @error('position') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Catégorie *</label>
                        <select wire:model="categorie_id" class="w-full mt-1 rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800">
                            <option value="">Choisir</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Urgence *</label>
                        <select wire:model="priorite" class="w-full mt-1 rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800">
                            <option value="faible">Faible</option>
                            <option value="moyenne">Moyenne</option>
                            <option value="critique">Critique</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium">Zone</label>
                    <select wire:model="zone_id" class="w-full mt-1 rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800">
                        <option value="">Optionnel</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->nomZone }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium">Description *</label>
                    <textarea wire:model="description" rows="4" class="w-full mt-1 rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800"></textarea>
                    @error('description') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium">Photos</label>
                    <input type="file" wire:model="photos" multiple accept="image/*" class="w-full mt-1 text-sm">
                </div>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-500 text-white font-medium">Envoyer le signalement</button>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-4 border border-slate-200 dark:border-gray-800 h-fit">
                <p class="text-sm text-slate-600 mb-3">Cliquez sur la carte pour la position GPS.</p>
                <div wire:ignore id="map-picker" class="h-64 rounded-lg overflow-hidden border"></div>
                <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                    <div><span class="text-slate-500">Lat</span><br><span id="lat-display">{{ $latitude ?? '—' }}</span></div>
                    <div><span class="text-slate-500">Lng</span><br><span id="lng-display">{{ $longitude ?? '—' }}</span></div>
                </div>
            </div>
        </form>
    </main>
    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    (function () {
        var map, marker;
        function initMap() {
            if (typeof L === 'undefined') { setTimeout(initMap, 200); return; }
            var el = document.getElementById('map-picker');
            if (!el || el._map) return;
            map = L.map(el).setView([14.7167, -17.4677], 12);
            el._map = map;
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(map);
            map.on('click', function (e) {
                if (marker) marker.setLatLng(e.latlng); else marker = L.marker(e.latlng).addTo(map);
                document.getElementById('lat-display').textContent = e.latlng.lat.toFixed(6);
                document.getElementById('lng-display').textContent = e.latlng.lng.toFixed(6);
                @this.set('latitude', e.latlng.lat);
                @this.set('longitude', e.latlng.lng);
            });
            setTimeout(function () { map.invalidateSize(); }, 100);
        }
        document.addEventListener('DOMContentLoaded', initMap);
    })();
    </script>
    @endpush
</div>
