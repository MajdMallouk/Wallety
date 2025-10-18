<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Send Money') }}
        </h2>
    </x-slot>
    <div class="flex items-center justify-center">
        <x-info-card class="p-4">
            <form action="{{ route('transactions.store') }}" method="POST">
                @csrf
                <div class="block mt-4 space-y-1">
                    <x-input-label class="pl-1 text-xl">From Wallet:</x-input-label>
                    <select name="wallet_id" required class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="" disabled selected>Select wallet</option>
                        @foreach(auth()->user()->wallets as $wallet)
                            <option value="{{ $wallet->id }}"
                            {{ old('wallet_id') == $wallet->id ? 'selected' : '' }}>
                                {{ $wallet->currency->currency_code }} — {{ $wallet->currency->currency_symbol . number_format($wallet->balance, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('wallet_id')" class="mt-2" />
                </div>
                @isset($recipient)
                    <div class="block mt-4 space-y-1">
                        <x-input-label class="pl-1 text-xl">Send to:</x-input-label>
                        <x-text-input name="receiver_id" type="text" class="w-full text-lg !text-accent-600 dark:!text-accent-400" value="{{ $recipient->username }}" readonly/>
                        <x-input-error :messages="$errors->get('receiver_id')" class="mt-2" />
                    </div>
                @else
                    <div class="block mt-4">
                        <x-input-label class="pl-1 text-xl">Send to:</x-input-label>
                        <div class="relative group">
                            <x-text-input
                                id="receiverInput"
                                name="receiver_id"
                                type="text"
                                class="w-full pr-12 text-lg"
                                placeholder="username / e-mail"
                                value="{{ old('receiver_id') }}"
                                required
                            />
                            <button
                                type="button"
                                id="scanQrBtn"
                                class="absolute right-0 -top-1/2 translate-y-1/2 h-full px-2 bg-gray-200 dark:bg-gray-700 text-accent-400 dark:text-white border-accent-500 rounded-r-md "
                                title="Scan QR"
                            >
                                <i class="fa-solid fa-expand fa-lg"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('receiver_id')" class="mt-2" />
                    </div>
                @endisset
                <div class="block mt-4 space-y-1">
                    <x-input-label class="pl-1 text-xl">Amount:</x-input-label>
                    <x-text-input name="amount" type="text" class="w-full text-lg" placeholder="0.00" value="{{ old('amount') }}" required/>
                    <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                </div>
                <div class="block mt-4 space-y-1">
                    <x-input-label class="pl-1 text-xl">Message <span class="text-sm">(optional)</span> :</x-input-label>
                    <textarea
                        name="message" type="text"
                        class="w-full h-24 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm resize-none"
                        placeholder="Happy birthday">{{ old('message') }}</textarea>
                    <x-input-error :messages="$errors->get('message')" class="mt-2" />
                </div>
                <div class="block mt-4 space-y-1">
                    <x-primary-button type="submit" class="w-full border-none">Send</x-primary-button>
                </div>
            </form>
        </x-info-card>
    </div>


    {{-- modal/overlay --}}
    <div
        id="qrScannerModal"
        class="fixed flex hidden inset-0 bg-black bg-opacity-50 items-center justify-center z-50"
    >
        <div class="bg-white p-4 rounded shadow-lg">
            <div id="qr-reader" style="width:300px"></div>
            <button id="closeQrBtn" class="mt-2 px-4 py-2 bg-red-500 text-white rounded">Close</button>
        </div>
    </div>
    {{-- include html5-qrcode --}}
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        const modal    = document.getElementById('qrScannerModal');
        const openBtn  = document.getElementById('scanQrBtn');
        const closeBtn = document.getElementById('closeQrBtn');
        const input    = document.getElementById('receiverInput');
        let scanner;

        if (openBtn) {
            openBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
                scanner = new Html5Qrcode("qr-reader");

                scanner.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: 250 },
                    decoded => {
                        let username;
                        try {
                            const urlObj = new URL(decoded);
                            const parts = urlObj.pathname.split('/').filter(Boolean);
                            username = parts.pop();
                        } catch(e) {
                            const m = decoded.match(/\/send\/([^\/]+)$/);
                            username = m ? m[1] : decoded;
                        }
                        input.value = username;
                        scanner.stop().catch(()=>{});
                        modal.classList.add('hidden');
                    },
                    err => {
                        // ignore decode errors
                    }
                )
                    .catch(err => {
                        console.error("Cannot start QR scanner:", err);
                        modal.classList.add('hidden');
                        alert("Camera access denied or unavailable. Please enter username manually.");
                    });
            });
        }

        closeBtn.addEventListener('click', () => {
            if (scanner) {
                scanner.stop().catch(()=>{});
                scanner = null;
            }
            modal.classList.add('hidden');
        });
    </script>

</x-app-layout>
