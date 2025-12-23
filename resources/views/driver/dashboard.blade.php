@extends('layouts.app')

@section('title', 'Driver Dashboard')

@section('content')
<div x-data="{
    tracking: false,
    loadId: {{ $activeLoad ? $activeLoad->id : 'null' }},
    watchId: null,
    startTracking() {
        if (!this.loadId) return;
        this.tracking = true;
        this.watchId = navigator.geolocation.watchPosition(
            (position) => this.sendLocation(position),
            (error) => console.error('Geolocation error:', error),
            { enableHighAccuracy: true, maximumAge: 0 }
        );
        this.sendLocationInterval = setInterval(() => {
            navigator.geolocation.getCurrentPosition((pos) => this.sendLocation(pos));
        }, 7000);
    },
    stopTracking() {
        this.tracking = false;
        if (this.watchId) {
            navigator.geolocation.clearWatch(this.watchId);
        }
        if (this.sendLocationInterval) {
            clearInterval(this.sendLocationInterval);
        }
    },
    sendLocation(position) {
        if (!this.tracking || !this.loadId) return;
        
        fetch('{{ route('driver.storeLocation') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                lat: position.coords.latitude,
                lng: position.coords.longitude,
                load_id: this.loadId
            })
        }).then(r => r.json()).then(data => {
            console.log('Location sent:', data);
        });
    },
    updateStatus(status) {
        if (!this.loadId) return;
        
        fetch('{{ route('driver.updateStatus') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                load_id: this.loadId,
                status: status
            })
        }).then(r => r.json()).then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    }
}">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Driver Dashboard</h1>
        <p class="text-gray-600 mt-1">Welcome, {{ auth()->user()->name }}</p>
    </div>

    @if($activeLoad)
        <!-- Active Load Card -->
        <div class="card p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $activeLoad->reference_no }}</h2>
                    <x-status-pill :status="$activeLoad->status" class="mt-2" />
                </div>
                <a href="{{ route('loads.show', $activeLoad) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    View Details →
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-medium text-gray-900 mb-2">📍 Pickup</h3>
                    <p class="text-gray-700">{{ $activeLoad->pickup_address }}</p>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900 mb-2">📍 Delivery</h3>
                    <p class="text-gray-700">{{ $activeLoad->delivery_address }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="border-t pt-4">
                <h3 class="font-medium text-gray-900 mb-3">Load Actions</h3>
                <div class="flex flex-wrap gap-3">
                    @if($activeLoad->status === 'assigned')
                        <button @click="updateStatus('in_transit')" class="btn-primary">
                            🚛 Start Transit
                        </button>
                    @endif

                    @if($activeLoad->status === 'in_transit')
                        <button @click="updateStatus('delivered')" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                            ✅ Mark Delivered
                        </button>
                    @endif

                    @if($activeLoad->status === 'in_transit')
                        <button @click="tracking ? stopTracking() : startTracking()" 
                                :class="tracking ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700'"
                                class="text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            <span x-text="tracking ? '⏸ Stop Tracking' : '📍 Start Tracking'"></span>
                        </button>
                        
                        <div x-show="tracking" class="flex items-center text-sm text-green-600">
                            <svg class="animate-pulse w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <circle cx="10" cy="10" r="6"></circle>
                            </svg>
                            Tracking active
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Documents -->
        @if($activeLoad->documents->count() > 0)
            <div class="card p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Documents ({{ $activeLoad->documents->count() }})</h3>
                <div class="space-y-2">
                    @foreach($activeLoad->documents->take(5) as $doc)
                        <div class="flex justify-between items-center py-2">
                            <div class="flex items-center">
                                <span class="status-pill text-xs {{ $doc->type_badge_color == 'green' ? 'bg-green-100 text-green-800' : ($doc->type_badge_color == 'blue' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $doc->type }}
                                </span>
                                <span class="ml-3 text-sm text-gray-900">{{ $doc->filename }}</span>
                            </div>
                            <a href="{{ route('documents.download', $doc) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                Download
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @else
        <!-- No Active Load -->
        <div class="card p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No Active Loads</h3>
            <p class="mt-2 text-gray-500">You don't have any active loads assigned at the moment.</p>
        </div>
    @endif
</div>
@endsection
