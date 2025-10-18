<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            My QR Code
        </h2>
    </x-slot>

    <div class="flex flex-col items-center gap-6 py-8">
        {{-- QR image --}}
        <div class="bg-white p-4 rounded shadow">
            {!! QrCode::size(200)->generate($url) !!}
        </div>

        {{-- Fallback URL --}}
        <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
            Share across the internet!<br>
        </p>

        {{-- Share buttons --}}
        @php
            $url       = route('transfers.send', ['recipient' => auth()->user()->username]);
            $shareText = "Send me money via Wallety: {$url}";
            $encoded   = urlencode($shareText);
        @endphp

        <div class="grid grid-cols-3 lg:flex gap-4">
            {{-- WhatsApp --}}
            <a
                href="https://wa.me/?text={{ $encoded }}"
                target="_blank" rel="noopener"
                class="flex items-center justify-center size-10 lg:size-12 bg-green-500 rounded-full hover:bg-green-600"
                title="Share on WhatsApp"
            >
                <i class="fab fa-whatsapp fa-lg text-white"></i>
            </a>

            {{-- Facebook --}}
            <a
                href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}"
                target="_blank" rel="noopener"
                class="flex items-center justify-center size-10 lg:size-12 bg-blue-600 rounded-full hover:bg-blue-700"
                title="Share on Facebook"
            >
                <i class="fab fa-facebook-f fa-lg text-white"></i>
            </a>

            {{-- Telegram --}}
            <a
                href="https://t.me/share/url?url={{ urlencode($url) }}&text={{ urlencode('Send me money via Wallety') }}"
                target="_blank" rel="noopener"
                class="flex items-center justify-center size-10 lg:size-12 bg-blue-400 rounded-full hover:bg-blue-500"
                title="Share on Telegram"
            >
                <i class="fab fa-telegram-plane fa-xl text-white"></i>
            </a>

            {{-- Twitter --}}
            <a
                href="https://twitter.com/intent/tweet?text={{ urlencode('Send me money via Wallety') }}&url={{ urlencode($url) }}"
                target="_blank" rel="noopener"
                class="flex items-center justify-center size-10 lg:size-12 bg-blue-300 rounded-full hover:bg-blue-400"
                title="Share on Twitter"
            >
                <i class="fab fa-twitter fa-lg text-white"></i>
            </a>

            {{-- Email --}}
            <a
                href="mailto:?subject={{ urlencode('Send Me Money via Wallety') }}&body={{ $encoded }}"
                class="flex items-center justify-center size-10 lg:size-12 bg-gray-600 rounded-full hover:bg-gray-700"
                title="Share via Email"
            >
                <i class="fas fa-envelope fa-lg text-white"></i>
            </a>
        </div>

    </div>
</x-app-layout>
