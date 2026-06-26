<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div x-data="zoneMap()" x-init="initMap($wire)" wire:ignore class="mt-1">
    <div style="position:relative;border-radius:10px;overflow:hidden;border:1px solid var(--gray-200,#e5e7eb);">
        <div x-ref="map" style="height:380px;width:100%;z-index:1;"></div>

        {{-- Coordonnées affichées en overlay --}}
        <div x-show="coordsText" x-cloak
             style="position:absolute;top:8px;right:8px;z-index:9999;background:rgba(255,255,255,0.95);border-radius:6px;padding:4px 10px;font-family:monospace;font-size:11px;color:#374151;box-shadow:0 1px 6px rgba(0,0,0,0.12);pointer-events:none;"
             x-text="coordsText"></div>

        {{-- Info bas --}}
        <div style="position:absolute;bottom:8px;left:8px;z-index:9999;background:rgba(255,255,255,0.92);border-radius:6px;padding:4px 10px;font-size:11px;color:#6b7280;box-shadow:0 1px 6px rgba(0,0,0,0.10);display:flex;align-items:center;gap:6px;pointer-events:none;">
            <svg style="width:12px;height:12px;color:#6366f1;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Cliquez sur la carte ou glissez le marqueur
        </div>
    </div>
</div>

@once
<script>
function zoneMap() {
    return {
        map:       null,
        marker:    null,
        coordsText:'',

        initMap(wire) {
            var self = this;
            var el   = this.$refs.map;
            if (!el || el._leaflet_id) return;
            if (typeof L === 'undefined') { setTimeout(function(){ self.initMap(wire); }, 300); return; }

            self.map = L.map(el, {
                center: [14.5, -14.5],
                zoom:   7,
                minZoom: 6,
                maxBounds: [[12,-18],[17,-11]],
                maxBoundsViscosity: 1,
                zoomControl: true,
                attributionControl: true,
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap',
                maxZoom: 19,
            }).addTo(self.map);

            function makeIcon() {
                return L.divIcon({
                    html: '<div style="width:16px;height:16px;background:#6366f1;border:3px solid #fff;border-radius:50%;box-shadow:0 0 0 3px rgba(99,102,241,0.3),0 2px 6px rgba(0,0,0,0.2)"></div>',
                    iconSize:[16,16], iconAnchor:[8,8], className:''
                });
            }

            function placeMarker(lat, lng) {
                if (self.marker) self.map.removeLayer(self.marker);
                self.marker = L.marker([lat, lng], { draggable:true, icon: makeIcon() }).addTo(self.map);
                self.coordsText = lat.toFixed(6) + ' , ' + lng.toFixed(6);
                self.marker.on('dragend', function() {
                    var p = self.marker.getLatLng();
                    self.coordsText = p.lat.toFixed(6) + ' , ' + p.lng.toFixed(6);
                    wire.call('setCoordinates', p.lat.toFixed(6), p.lng.toFixed(6));
                });
                self.map.setView([lat, lng], 13, { animate:true });
            }

            // Clic sur carte → placer marqueur + appeler méthode Livewire
            self.map.on('click', function(e) {
                var lat = parseFloat(e.latlng.lat.toFixed(6));
                var lng = parseFloat(e.latlng.lng.toFixed(6));
                placeMarker(lat, lng);
                wire.call('setCoordinates', lat.toFixed(6), lng.toFixed(6));
            });

            // Écoute de l'événement émis par EditZone::afterFill()
            Livewire.on('zone-map-init', function(data) {
                var d = Array.isArray(data) ? data[0] : data;
                if (d && d.lat && d.lng) {
                    placeMarker(parseFloat(d.lat), parseFloat(d.lng));
                }
            });

            // Pour CreateZone : coordonnées déjà saisies (rechargement Livewire)
            try {
                var lat0 = wire.get('data.latitude');
                var lng0 = wire.get('data.longitude');
                if (lat0 && lng0 && parseFloat(lat0) !== 0) {
                    placeMarker(parseFloat(lat0), parseFloat(lng0));
                }
            } catch(e) {}

            setTimeout(function(){ self.map && self.map.invalidateSize(); }, 400);
        },
    };
}
</script>
@endonce
