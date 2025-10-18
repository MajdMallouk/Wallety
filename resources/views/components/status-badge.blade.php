@props(['status'])

@php
    $colors = [
        'paid' => 'bg-green-500/70 text-green-100',
        'waiting' => 'bg-orange-500/70 text-orange-100',
        'failed' => 'bg-red-500/70 text-red-100',
        'rejected' => 'bg-red-500/70 text-red-100',
    ];
    $color = $colors[$status] ?? 'bg-gray-300/50 text-gray-800';
@endphp

<span {{ $attributes->merge(['class' => "px-2 py-1 rounded text-sm font-medium $color"]) }}>
    {{ ucfirst($status) }}
</span>
