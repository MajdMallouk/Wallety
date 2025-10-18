    <a {{ $attributes->merge(['class' => 'inline-flex items-center justify-center w-fit lg:w-full px-4 py-2 bg-accent-400 border border-transparent rounded-md font-semibold text-xs text-white dark:text-white uppercase tracking-widest hover:bg-accent-500 focus:bg-accent-600 active:bg-accent-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</a>
