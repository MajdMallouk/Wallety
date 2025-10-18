{{-- resources/views/profile/complete.blade.php --}}

<x-app-layout>
    @php
        // Get location data based on client IP without `use` statements
        $location = \Stevebauman\Location\Facades\Location::get(request()->ip());
        $countryName = $location?->countryName ?? '';
        $isoCode     = $location?->countryCode  ?? '';
        $dialCode = config("country_dial_codes.$isoCode", '');
    @endphp

    <div class="w-1/2 lg:w-1/4 mx-auto mt-12">
        <h2 class="text-lg lg:text-2xl text-center font-semibold mb-6">Complete Profile</h2>

        <form method="POST" action="{{ route('profile.complete.submit') }}">
            @csrf

            {{-- Username --}}
            <div class="mb-4">
                <x-input-label for="username" :value="__('Username')" />
                <x-text-input
                    id="username"
                    class="block mt-1 w-full"
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    autofocus
                />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            {{-- Phone (with auto‐filled dial code) --}}
            <div class="mb-4">
                <x-input-label for="phone" :value="__('Phone Number')" />
                <div class="flex">
                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                        {{ $dialCode }}
                    </span>
                    <x-text-input
                        id="phone"
                        class="block w-full rounded-l-none"
                        type="tel"
                        name="phone"
                        value="{{ old('phone') }}"
                        required
                    />
                </div>
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            {{-- Hidden country & country_code --}}
            <input
                type="hidden"
                name="country"
                value="{{ old('country', $countryName) }}"
            />
            <input
                type="hidden"
                name="country_code"
                value="{{ old('country_code', $dialCode) }}"
            />

            <div class="mt-6 flex justify-end">
                <x-primary-button class="w-full">
                    {{ __('Save Profile') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
