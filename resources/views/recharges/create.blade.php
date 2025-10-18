<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Recharge') }}
        </h2>
    </x-slot>
    <div class="flex items-center justify-center">
        <x-info-card x-data="{method: ''}" class="p-4">
            <form action="{{ route('recharges.store') }}" method="POST">
                @csrf

                <x-input-label class="pl-1 text-xl">Currency:</x-input-label>
                @php
                    // Build a map from currency_id → Wallet model, for quick lookup
                    $userWallets = auth()->user()->wallets->keyBy('currency_id');
                @endphp

                <select
                    name="currency_id"
                    id="fromWalletSelect"
                    required
                    class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                >
                    <option value="" disabled {{ old('currency_id') ? '' : 'selected' }}>Select currency</option>

                    @foreach($currencies as $currency)
                        @php
                            // If the user already has a wallet for this currency, pull its balance; otherwise zero
                            $wallet = $userWallets->get($currency->id);
                            $balance = $wallet ? $wallet->balance : 0;
                        @endphp

                        <option
                            value="{{ $currency->id }}"
                            data-rate="{{ $currency->exchange_rate }}"
                            data-currency-id="{{ $currency->id }}"
                            {{ old('currency_id') == $currency->id ? 'selected' : '' }}
                        >
                            {{ $currency->currency_code }}
                            —
                            {{ $currency->currency_symbol . number_format($balance, 2) }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('wallet_id')" class="mt-2" />

                <div class="block mt-4 space-y-1">
                    <x-input-label class="pl-1 text-xl">Method:</x-input-label>
                    <select x-model="method" name="method" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="paypal">Paypal</option>
                        <option value="visaCard">Visa Card</option>
                        <option value="masterCard">Master Card</option>
                        <option value="bankTransfer">Bank Transfer</option>
                    </select>
                    <x-input-error :messages="$errors->get('method')" class="mt-2" />
                </div>
                {{--            Not being stored in the database yet--}}
                <div x-show="method === 'bankTransfer'" x-transition x-cloak>
                    <div class="block mt-4 space-y-1">
                        <x-input-label class="pl-1 text-xl">Account Number:</x-input-label>
                        <x-text-input name="accountNumber" type="text" class="w-full text-lg" placeholder="128432734242"
                                      value="{{ old('accountNumber') }}"/>
                        <x-input-error :messages="$errors->get('accountNumber')" class="mt-2" />
                    </div>
                </div>
                <div class="block mt-4 space-y-1">
                    <x-input-label class="pl-1 text-xl">Amount:</x-input-label>
                    <x-text-input name="amount" type="text" class="w-full text-lg" placeholder="$10,000" value="{{ old('amount') }}" required/>
                    <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                </div>
                <div class="block mt-4 space-y-1">
                    <x-primary-button type="submit" class="w-full">Recharge</x-primary-button>
                </div>
            </form>
        </x-info-card>
    </div>
</x-app-layout>
