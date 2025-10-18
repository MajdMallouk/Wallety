
@props(['isReceived'])


@php
    $classes = $isReceived
        ? 'rotate-45 bg-green-100 text-green-600'
        : '-rotate-45 bg-red-100 text-red-600';
@endphp

<i class="fa-solid fa-arrow-right {{ $classes }} py-3 px-[13.5px] rounded-full text-2xl"></i>
