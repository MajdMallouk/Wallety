<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Exchange') }}
        </h2>
    </x-slot>

    <div class="flex items-center justify-center">
        <x-info-card class="p-4">

            <form action="{{ route('exchanges.store') }}" method="POST" id="exchangeForm">
                @csrf

                {{-- From Wallet --}}
                <div class="block mt-4 space-y-1">
                    <x-input-label class="pl-1 text-xl">From Wallet:</x-input-label>
                    <select
                        name="wallet_id"
                        id="fromWalletSelect"
                        required
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    >
                        <option value="" disabled {{ old('wallet_id') ? '' : 'selected' }}>Select wallet</option>
                        @foreach(auth()->user()->wallets as $wallet)
                            <option
                                value="{{ $wallet->id }}"
                                data-rate="{{ $wallet->currency->exchange_rate }}"
                                data-currency-id="{{ $wallet->currency->id }}"
                                {{ old('wallet_id') == $wallet->id ? 'selected' : '' }}
                            >
                                {{ $wallet->currency->currency_code }} —
                                {{ $wallet->currency->currency_symbol . number_format($wallet->balance, 2) }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('wallet_id')" class="mt-2" />
                </div>

                {{-- Amount --}}
                <div class="block mt-4 space-y-1">
                    <x-input-label class="pl-1 text-xl">Amount:</x-input-label>
                    <x-text-input
                        name="amount"
                        type="text"
                        id="amountInput"
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        placeholder="0.00"
                        value="{{ old('amount') }}"
                        required
                    />
                    <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                </div>

                {{-- To Currency --}}
                <div class="block mt-4 space-y-1">
                    <x-input-label class="pl-1 text-xl">To Currency:</x-input-label>
                    <select
                        name="target_currency_id"
                        id="toCurrencySelect"
                        required
                        class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    >
                        <option value="" disabled {{ old('target_currency_id') ? '' : 'selected' }}>Select currency</option>
                        @foreach($currencies as $currency)
                            <option
                                value="{{ $currency->id }}"
                                data-rate="{{ $currency->exchange_rate }}"
                                {{ old('target_currency_id') == $currency->id ? 'selected' : '' }}
                            >
                                {{ $currency->currency_code }} — {{ $currency->currency_symbol }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('target_currency_id')" class="mt-2" />
                </div>

                {{-- You will get --}}
                <div class="block mt-4 space-y-1">
                    <x-input-label class="pl-1 text-xl">You will get:</x-input-label>
                    <p
                        id="youWillGet"
                        class="w-full p-2 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                    >0.00</p>
                </div>

                {{-- Submit --}}
                <div class="block mt-4 space-y-1">
                    <x-primary-button type="submit" class="w-full">Exchange</x-primary-button>
                </div>
            </form>

            {{-- (Optional) Show “base” exchange rates as before --}}
            @php
                $defaultCurrency = auth()->user()->wallets
                  ->first(fn($w) => $w->currency->is_default)?->currency;
            @endphp

            @if ($defaultCurrency)
                <div class="mb-4 p-2 rounded bg-gray-100 dark:bg-gray-800 mt-6">
                    <h3 class="text-lg font-semibold mb-2">
                        Exchange Rates (Base: {{ $defaultCurrency->currency_code }})
                    </h3>
                    <ul class="text-sm">
                        @foreach(auth()->user()->wallets as $wallet)
                            @if($wallet->currency->id !== $defaultCurrency->id)
                                <li>
                                    1 {{ $wallet->currency->currency_code }} =
                                    {{ number_format($wallet->currency->exchange_rate / $defaultCurrency->exchange_rate, 4) }}
                                    {{ $defaultCurrency->currency_code }}
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif

        </x-info-card>
    </div>

    {{-- Plain JavaScript at the bottom --}}
    <script>
        (function(){
            const fromWalletSelect = document.getElementById('fromWalletSelect');
            const toCurrencySelect = document.getElementById('toCurrencySelect');
            const amountInput      = document.getElementById('amountInput');
            const youWillGet       = document.getElementById('youWillGet');

            // Hide/disable the option in "To Currency" that matches the selected "From" currency.
            function filterToCurrencyOptions() {
                const selectedFromOption = fromWalletSelect.options[fromWalletSelect.selectedIndex];
                const fromCurrencyId = selectedFromOption
                    ? selectedFromOption.dataset.currencyId
                    : null;

                // First, show/enable all options
                Array.from(toCurrencySelect.options).forEach(opt => {
                    opt.hidden = false;
                    opt.disabled = false;
                });

                // Then hide/disable the matching one
                if (fromCurrencyId) {
                    Array.from(toCurrencySelect.options).forEach(opt => {
                        if (opt.value === fromCurrencyId) {
                            opt.hidden = true;
                            opt.disabled = true;

                            // If the user had previously selected that, reset to default
                            if (opt.selected) {
                                toCurrencySelect.value = '';
                            }
                        }
                    });
                }
            }

            // Calculate "You will get"
            function recalc() {
                const fromOption = fromWalletSelect.options[fromWalletSelect.selectedIndex];
                const toOption   = toCurrencySelect.options[toCurrencySelect.selectedIndex];
                const amtString  = amountInput.value.trim();

                if (!fromOption || !toOption || amtString === '') {
                    youWillGet.textContent = '0.00';
                    return;
                }

                const rateFrom = parseFloat(fromOption.dataset.rate || '0');
                const rateTo   = parseFloat(toOption.dataset.rate || '0');
                const amt      = parseFloat(amtString.replace(/,/g, ''));

                if (isNaN(amt) || isNaN(rateFrom) || isNaN(rateTo) || rateFrom <= 0 || rateTo <= 0) {
                    youWillGet.textContent = '0.00';
                    return;
                }

                const out = amt * (rateFrom / rateTo);
                youWillGet.textContent = out.toFixed(2);
            }

            // Whenever "From Wallet" changes:
            fromWalletSelect.addEventListener('change', () => {
                filterToCurrencyOptions();
                recalc();
            });

            // Whenever "To Currency" changes:
            toCurrencySelect.addEventListener('change', recalc);

            // Whenever amount is typed:
            amountInput.addEventListener('input', recalc);

            // On initial load, apply the filter (for old() values) and calc:
            window.addEventListener('DOMContentLoaded', () => {
                filterToCurrencyOptions();
                recalc();
            });
        })();
    </script>
</x-app-layout>
