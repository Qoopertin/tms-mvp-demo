@extends('layouts.app')

@section('title', $load->reference_no)

@section('content')
<div x-data="{ activeTab: 'overview' }">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $load->reference_no }}</h1>
            <x-status-pill :status="$load->status" />
        </div>
        
        @if(auth()->user()->hasAnyRole(['Admin', 'Dispatcher']))
            <div class="flex space-x-2">
                @if($load->status !== 'delivered')
                    <button onclick="document.getElementById('assignModal').classList.remove('hidden')" 
                            class="btn-primary">
                        Assign Driver
                    </button>
                @endif
            </div>
        @endif
    </div>

    <!-- Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex space-x-8">
            <button @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900'"
                    class="pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Overview
            </button>
            <button @click="activeTab = 'documents'"
                    :class="activeTab === 'documents' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900'"
                    class="pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Documents ({{ $load->documents->count() }})
            </button>
            <button @click="activeTab = 'tracking'"
                    :class="activeTab === 'tracking' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600 hover:text-gray-900'"
                    class="pb-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Tracking
            </button>
        </nav>
    </div>

    <!-- Overview Tab -->
    <div x-show="activeTab === 'overview'" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Pickup -->
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Pickup Location</h3>
                <p class="text-gray-700 mb-2">{{ $load->pickup_address }}</p>
                @if($load->pickup_lat && $load->pickup_lng)
                    <p class="text-sm text-gray-500">{{ $load->pickup_lat }}, {{ $load->pickup_lng }}</p>
                @endif
            </div>

            <!-- Delivery -->
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Delivery Location</h3>
                <p class="text-gray-700 mb-2">{{ $load->delivery_address }}</p>
                @if($load->delivery_lat && $load->delivery_lng)
                    <p class="text-sm text-gray-500">{{ $load->delivery_lat }}, {{ $load->delivery_lng }}</p>
                @endif
            </div>
        </div>

        <!-- Load Info -->
        <div class="card p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Load Information</h3>
            <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <dt class="text-sm text-gray-500">Status</dt>
                    <dd class="mt-1"><x-status-pill :status="$load->status" /></dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Assigned Driver</dt>
                    <dd class="mt-1 font-medium">{{ $load->driver->name ?? 'Not assigned' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Created</dt>
                    <dd class="mt-1">{{ $load->created_at->format('M d, Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Documents Tab -->
    <div x-show="activeTab === 'documents'">
        @if(auth()->user()->can('upload documents'))
            <div class="card p-6 mb-6">
                <h3 class="font-semibold text-gray-900 mb-4">Upload Document</h3>
                <form method="POST" action="{{ route('documents.upload', $load) }}" enctype="multipart/form-data" class="flex gap-4">
                    @csrf
                    <div class="flex-1">
                        <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <select name="type" required class="px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="POD">POD</option>
                        <option value="PHOTO">Photo</option>
                        <option value="OTHER">Other</option>
                    </select>
                    <button type="submit" class="btn-primary">Upload</button>
                </form>
            </div>
        @endif

        <div class="card overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Filename</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Uploaded By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($load->documents as $doc)
                        <tr>
                            <td class="px-6 py-4">
                                <span class="status-pill {{ $doc->type_badge_color == 'green' ? 'bg-green-100 text-green-800' : ($doc->type_badge_color == 'blue' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $doc->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $doc->filename }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $doc->formatted_size }}</td>
                            <td class="px-6 py-4 text-sm">{{ $doc->uploader->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $doc->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right text-sm space-x-2">
                                <a href="{{ route('documents.download', $doc) }}" class="text-blue-600 hover:text-blue-800">Download</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">No documents uploaded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tracking Tab -->
    <div x-show="activeTab === 'tracking'">
        <div id="map" class="card h-96 overflow-hidden"></div>
        
        @if($load->breadcrumbs->count() > 0)
            <div class="card mt-6 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Recent Locations</h3>
                <div class="space-y-2">
                    @foreach($load->breadcrumbs->take(10) as $crumb)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-700">{{ $crumb->lat }}, {{ $crumb->lng }}</span>
                            <span class="text-gray-500">{{ $crumb->captured_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="card p-8 mt-6 text-center text-gray-500">
                No tracking data available yet.
            </div>
        @endif
    </div>
</div>

<!-- Assign Driver Modal -->
<div id="assignModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-semibold mb-4">Assign Driver</h3>
        <form method="POST" action="{{ route('loads.assign', $load) }}">
            @csrf
            @method('PUT')
            <select name="driver_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-4">
                <option value="">Select Driver</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ $load->assigned_driver_id == $driver->id ? 'selected' : '' }}>
                        {{ $driver->name }}
                    </option>
                @endforeach
            </select>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('assignModal').classList.add('hidden')" 
                        class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">Assign</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Initialize map if tracking tab
    let map = null;
    
    function initMap() {
        if (map) return;
        
        map = L.map('map').setView([{{ $load->pickup_lat ?? 39.8283 }}, {{ $load->pickup_lng ?? -98.5795 }}], 4);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        @if($load->pickup_lat && $load->pickup_lng)
            L.marker([{{ $load->pickup_lat }}, {{ $load->pickup_lng }}])
                .bindPopup('Pickup').addTo(map);
        @endif
        
        @if($load->delivery_lat && $load->delivery_lng)
            L.marker([{{ $load->delivery_lat }}, {{ $load->delivery_lng }}])
                .bindPopup('Delivery').addTo(map);
        @endif
        
        @if($load->breadcrumbs->count() > 0)
            const breadcrumbs = @json($load->breadcrumbs->map(fn($b) => [$b->lat, $b->lng]));
            L.polyline(breadcrumbs, {color: 'blue', weight: 3}).addTo(map);
        @endif
    }
    
    // Initialize map when tracking tab is shown
    document.addEventListener('alpine:init', () => {
        setTimeout(() => {
            const trackingTab = document.querySelector('[\\@click="activeTab = \'tracking\'"]');
            if (trackingTab) {
                trackingTab.addEventListener('click', () => {
                    setTimeout(initMap, 100);
                });
            }
        }, 100);
    });
</script>
@endpush
@endsection
