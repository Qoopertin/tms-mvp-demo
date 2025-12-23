@props(['status', 'load' => null])

@php
    $colors = [
        'created' => 'bg-gray-100 text-gray-800',
        'assigned' => 'bg-blue-100 text-blue-800',
        'in_transit' => 'bg-yellow-100 text-yellow-800',
        'delivered' => 'bg-green-100 text-green-800',
    ];
    
    $labels = [
        'created' => 'Created',
        'assigned' => 'Assigned',
        'in_transit' => 'In Transit',
        'delivered' => 'Delivered',
    ];
    
    $color = $colors[$status] ?? $colors['created'];
    $label = $labels[$status] ?? ucfirst($status);
@endphp

<span {{ $attributes->merge(['class' => "status-pill {$color}"]) }}>
    {{ $label }}
</span>
