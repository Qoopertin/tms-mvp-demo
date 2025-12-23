@extends('layouts.app')

@section('title', 'Loads')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-gray-900">Loads</h1>
    <a href="{{ route('loads.create') }}" class="btn-primary">
        + Create Load
    </a>
</div>

<!-- Filters -->
<div class="card p-4 mb-6">
    <form method="GET" action="{{ route('loads.index') }}" class="flex gap-4">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">All Statuses</option>
                <option value="created" {{ request('status') == 'created' ? 'selected' : '' }}>Created</option>
                <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
            </select>
        </div>

        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Driver</label>
            <select name="driver_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">All Drivers</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                        {{ $driver->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('loads.index') }}" class="ml-2 btn-secondary">Clear</a>
        </div>
    </form>
</div>

<!-- Loads Table -->
<div class="card overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Reference</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Pickup</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Delivery</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Driver</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($loads as $load)
                <tr class="table-row">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('loads.show', $load) }}" class="font-medium text-blue-600 hover:text-blue-800">
                            {{ $load->reference_no }}
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ Str::limit($load->pickup_address, 40) }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ Str::limit($load->delivery_address, 40) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-status-pill :status="$load->status" />
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $load->driver->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('loads.show', $load) }}" class="text-blue-600 hover:text-blue-800">
                            View
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        No loads found. Create your first load to get started.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $loads->links() }}
</div>
@endsection
