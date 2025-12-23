@extends('layouts.app')

@section('title', 'Create Load')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Create New Load</h1>
</div>

<div class="card p-6 max-w-3xl">
    <form method="POST" action="{{ route('loads.store') }}">
        @csrf

        <div class="mb-6">
            <label for="reference_no" class="block text-sm font-medium text-gray-700 mb-2">
                Reference Number <span class="text-red-500">*</span>
            </label>
            <input type="text" name="reference_no" id="reference_no" value="{{ old('reference_no') }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            @error('reference_no')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="pickup_address" class="block text-sm font-medium text-gray-700 mb-2">
                    Pickup Address <span class="text-red-500">*</span>
                </label>
                <textarea name="pickup_address" id="pickup_address" rows="3" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('pickup_address') }}</textarea>
                @error('pickup_address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">
                    Delivery Address <span class="text-red-500">*</span>
                </label>
                <textarea name="delivery_address" id="delivery_address" rows="3" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('delivery_address') }}</textarea>
                @error('delivery_address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pickup Coordinates (Optional)</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="number" name="pickup_lat" step="0.0000001" placeholder="Latitude"
                           value="{{ old('pickup_lat') }}"
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <input type="number" name="pickup_lng" step="0.0000001" placeholder="Longitude"
                           value="{{ old('pickup_lng') }}"
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Coordinates (Optional)</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="number" name="delivery_lat" step="0.0000001" placeholder="Latitude"
                           value="{{ old('delivery_lat') }}"
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <input type="number" name="delivery_lng" step="0.0000001" placeholder="Longitude"
                           value="{{ old('delivery_lng') }}"
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('loads.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Create Load</button>
        </div>
    </form>
</div>
@endsection
