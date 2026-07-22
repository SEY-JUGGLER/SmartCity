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
                    <div class="flex gap-2 mt-1">
                        <input wire:model="position" id="position-input"
                            class="flex-1 rounded-xl border-slate-300 dark:border-gray-600 dark:bg-gray-800"
                            placeholder="Ex: Rue de la République, Dakar">
                        <button type="button" id="btn-geolocate" onclick="localiserPosition()"
                            class="px-3 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-sm whitespace-nowrap flex items-center gap-1.5 transition disabled:opacity-60">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            <span id="geo-label">Ma position</span>
                        </button>
                    </div>
                    @error('position') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
                    <input type="file" wire:model="photos" id="photos-input" multiple accept="image/*" class="w-full mt-1 text-sm">
                    <div id="photo-preview" class="grid grid-cols-3 gap-2 mt-2"></div>
                    @error('photos.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-500 text-white font-medium">Envoyer le signalement</button>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-4 border border-slate-200 dark:border-gray-800 h-fit">
                <p class="text-sm text-slate-600 mb-3">Utilisez "Ma position" ou cliquez sur la carte.</p>
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
    var mapPicker = null, mapMarker = null;
    var GEOCODE_URL = "{{ route('geocode.reverse') }}";

    function initMapPicker() {
        if (typeof L === 'undefined') {
            setTimeout(initMapPicker, 200);
            return;
         }
        var el = document.getElementById('map-picker');
        if (!el || el._map) return;
        mapPicker = L.map(el).setView([14.7167, -17.4677], 12);
        el._map = mapPicker;
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(mapPicker);
        mapPicker.on('click', function (e) {
            var lat = e.latlng.lat, lng = e.latlng.lng;
            if (mapMarker) mapMarker.setLatLng(e.latlng);
            else mapMarker = L.marker(e.latlng).addTo(mapPicker);
            document.getElementById('lat-display').textContent = lat.toFixed(6);
            document.getElementById('lng-display').textContent = lng.toFixed(6);
            @this.set('latitude', lat);
            @this.set('longitude', lng);
            fetch(GEOCODE_URL + '?lat=' + lat + '&lon=' + lng)
            .then(function (r) { return r.json(); })
            .then(function (d) { if (d.display_name) @this.set('position', d.display_name); })
            .catch(function () {});
        });
        setTimeout(function () { mapPicker.invalidateSize(); }, 100);
    }

    function localiserPosition() {
        if (!navigator.geolocation) {
            alert('La géolocalisation n\'est pas supportée par votre navigateur.');
            return;
        }
        var btn   = document.getElementById('btn-geolocate');
        var label = document.getElementById('geo-label');
        btn.disabled   = true;
        label.textContent = 'Localisation...';

        navigator.geolocation.getCurrentPosition(
            function (pos) {
                var lat = pos.coords.latitude;
                var lng = pos.coords.longitude;

                @this.set('latitude', lat);
                @this.set('longitude', lng);

                if (mapPicker) {
                    if (mapMarker) mapMarker.setLatLng([lat, lng]);
                    else mapMarker = L.marker([lat, lng]).addTo(mapPicker);
                    mapPicker.setView([lat, lng], 16);
                } else {
                    initMapPicker();
                    setTimeout(function () {
                        if (mapPicker) {
                            if (mapMarker) mapMarker.setLatLng([lat, lng]);
                            else mapMarker = L.marker([lat, lng]).addTo(mapPicker);
                            mapPicker.setView([lat, lng], 16);
                        }
                    }, 500);
                }

                document.getElementById('lat-display').textContent = lat.toFixed(6);
                document.getElementById('lng-display').textContent = lng.toFixed(6);

                @this.set('position', lat.toFixed(6) + ', ' + lng.toFixed(6));

                fetch(GEOCODE_URL + '?lat=' + lat + '&lon=' + lng)
                .then(function (r) { return r.json(); })
                .then(function (d) {
                    if (d && d.display_name) {
                        @this.set('position', d.display_name);
                    }
                })
                .catch(function () {
                    console.warn('Géocodage indisponible, coordonnées utilisées.');
                })
                .finally(function () {
                    btn.disabled      = false;
                    label.textContent = 'Ma position';
                });
            },
            function (err) {
                btn.disabled      = false;
                label.textContent = 'Ma position';
                var msg = 'Position non disponible. Veuillez cliquer sur la carte pour indiquer votre emplacement.';
                if (err.code === 1) msg = 'Autorisation de géolocalisation refusée. Activez-la dans votre navigateur.';
                else if (err.code === 2) msg = 'Position indisponible. Vérifiez votre connexion GPS.';
                else if (err.code === 3) msg = 'Délai de géolocalisation dépassé. Réessayez.';
                alert(msg);
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
        );
    }

    document.addEventListener('DOMContentLoaded', function () {
        initMapPicker();

        var photosInput = document.getElementById('photos-input');
        if (photosInput) {
            photosInput.addEventListener('change', function () {
                var preview = document.getElementById('photo-preview');
                preview.innerHTML = '';
                Array.from(this.files).forEach(function (file) {
                    if (!file.type.startsWith('image/')) return;
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'h-20 w-full object-cover rounded-lg border border-slate-200 dark:border-gray-700';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            });
        }
    });
    </script>
    @endpush
</div>
