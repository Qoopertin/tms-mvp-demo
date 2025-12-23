@extends('layouts.app')

@section('title', 'Dispatch Map')

@section('content')
<div x-data="dispatchMap()">
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Live Driver Tracking</h1>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-600">Last updated: <span x-text="lastUpdate"></span></span>
            <div class="w-2 h-2 rounded-full" :class="isLoading ? 'bg-yellow-400 animate-pulse' : 'bg-green-400'"></div>
        </div>
    </div>

    <div id="map" class="card overflow-hidden" style="height: calc(100vh - 200px);"></div>
</div>

@push('scripts')
<script>
function dispatchMap() {
    return {
        map: null,
        markers: {},
        isLoading: false,
        lastUpdate: 'Never',
        
        init() {
            // Initialize map
            this.map = L.map('map').setView([39.8283, -98.5795], 4);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(this.map);
            
            // Load initial positions
            this.loadDriverLocations();
            
            // Poll every 5 seconds
            setInterval(() => this.loadDriverLocations(), 5000);
        },
        
        loadDriverLocations() {
            this.isLoading = true;
            
            fetch('{{ route('api.driver-locations') }}')
                .then(r => r.json())
                .then(locations => {
                    this.updateMarkers(locations);
                    this.lastUpdate = new Date().toLocaleTimeString();
                    this.isLoading = false;
                })
                .catch(err => {
                    console.error('Failed to load driver locations:', err);
                    this.isLoading = false;
                });
        },
        
        updateMarkers(locations) {
            // Track which markers we've seen
            const activeMarkers = new Set();
            
            locations.forEach(driver => {
                activeMarkers.add(driver.id);
                
                if (this.markers[driver.id]) {
                    // Update existing marker
                    this.markers[driver.id].setLatLng([driver.lat, driver.lng]);
                    this.markers[driver.id].getPopup().setContent(`
                        <strong>${driver.name}</strong><br>
                        Last update: ${driver.last_update}
                    `);
                } else {
                    // Create new marker
                    const marker = L.marker([driver.lat, driver.lng], {
                        icon: L.divIcon({
                            className: 'custom-marker',
                            html: `<div class="bg-blue-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold shadow-lg">
                                     🚛
                                   </div>`,
                            iconSize: [40, 40]
                        })
                    }).addTo(this.map);
                    
                    marker.bindPopup(`
                        <strong>${driver.name}</strong><br>
                        Last update: ${driver.last_update}
                    `);
                    
                    this.markers[driver.id] = marker;
                }
            });
            
            // Remove markers for drivers no longer active
            Object.keys(this.markers).forEach(id => {
                if (!activeMarkers.has(parseInt(id))) {
                    this.map.removeLayer(this.markers[id]);
                    delete this.markers[id];
                }
            });
            
            // Auto-fit bounds if we have markers
            if (locations.length > 0) {
                const bounds = L.latLngBounds(locations.map(d => [d.lat, d.lng]));
                this.map.fitBounds(bounds, { padding: [50, 50] });
            }
        }
    }
}
</script>
@endpush
@endsection
