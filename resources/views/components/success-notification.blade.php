@props(['type' => 'info']) {{-- Default type if none provided --}}

@php
    if ($type === "error") {
        $classes = 'bg-red-500';
    } elseif ($type === "warning") {
        $classes = 'bg-yellow-500';
    } elseif ($type === "success") {
        $classes = 'bg-green-500';
    } elseif ($type === "info") {
        $classes = 'bg-blue-500';
    }
@endphp
<div
    x-data="{ show: true }"
    x-init="
            setTimeout(() => show = false, 3500);
            // kick off the progress‐bar animation
            $nextTick(() => $refs.progress.style.width = '0%');"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-10"
    x-transition:enter-end="opacity-100 translate-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-0"
    x-transition:leave-end="opacity-0 -translate-y-10"
    {{ $attributes
        ->class([
        'transform
        text-sm lg:text-xl text-white px-4 py-2
        rounded shadow-lg overflow-hidden whitespace-nowrap',
        $classes
            ]) }}
>
    {{ $slot }}

    {{-- progress bar --}}
    <div
        x-ref="progress"
        class="absolute left-0 bottom-0 h-1 bg-white"
        style="width: 100%; transition: width 3.5s linear;"
    ></div>
</div>
