<a {{ $attributes->merge(['class' => 'flex flex-col group items-center']) }}>
    <div class="border border-accent-400 rounded p-2 m-1 group-hover:bg-accent-200 dark:group-hover:bg-accent-400 transition duration-300 w-fit h-fit">
        {{ $slot }}
    </div>
    {{ $quickLinkName ?? '' }}
</a>
