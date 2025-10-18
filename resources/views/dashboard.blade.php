<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg lg:text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <a href="#">
            <div class="flex gap-2 lg:gap-5">
                <h2>$1 =</h2>
                <p class="text-green-500">
                    <small>£</small>10000<sup>Buy</sup>
                </p>
                <p class="text-red-500">
                    <small>£</small> 9800<sup>Sell</sup>
                </p>
            </div>
        </a>
    </x-slot>
    <div class="main-content flex flex-col lg:flex-row gap-5">
        <div class="flex flex-col lg:w-3/4 gap-5 items-end">
            <x-container class="justify-center lg:justify-end">

                @foreach(auth()->user()->wallets as $wallet)
                    <x-info-card>
                        <img class="ml-2 w-16 text-[#ff0000]" src="{{ asset('img/icons/wallet-46.svg') }}"
                             alt="wallet"/>

                        <h2 class="ml-2 text-lg lg:text-2xl">
                            {{ $wallet->currency->currency_symbol . $wallet->balance }}
                        </h2>
                        <hr>
                        <a href="{{ route('transactions.create') }}"
                           class="flex items-end gap-1 ml-2 mb-3 lg:hover:gap-3 lg:hover:font-semibold transition cursor-pointer text-sm lg:text-base">Transfer
                            money <i class="fa-solid fa-arrow-right"></i> </a>
                    </x-info-card>
                @endforeach
            </x-container>
            <x-container
                class="flex-col gap-2 items-center ml-auto bg-base-bgLight dark:bg-base-bg shadow-sm rounded p-4">
                <h2>Quick links</h2>
                <x-container class="justify-center gap-8">
                    <x-quick-link href="{{ route('recharges.create') }}">
                        <i class="fa-solid fa-wallet text-accent-400 dark:text-white text-3xl"></i>
                        <x-slot name="quickLinkName">
                            <span class="text-sm text-center text-neutral-900 dark:text-neutral-300">deposit</span>
                        </x-slot>
                    </x-quick-link>
                    <x-quick-link href="{{ route('user.qr') }}">
                        <i class="fa-solid fa-qrcode text-accent-400 dark:text-white text-3xl"></i>
                        <x-slot name="quickLinkName">
                            <span class="text-sm text-center text-neutral-900 dark:text-neutral-300">my QR</span>
                        </x-slot>
                    </x-quick-link>
                    <x-quick-link href="{{ route('exchanges.create') }}">
                        <i class="fa-solid fa-arrow-right-arrow-left text-accent-400 dark:text-white text-3xl"></i>
                        <x-slot name="quickLinkName">
                            <span class="text-sm text-center text-neutral-900 dark:text-neutral-300">exchange</span>
                        </x-slot>
                    </x-quick-link>
                    <x-quick-link href="#" class="pointer-events-none opacity-50">
                        <i class="fa-solid fa-cart-shopping text-accent-400 dark:text-white text-3xl"></i>
                        <x-slot name="quickLinkName">
                            <span class="text-sm text-center text-neutral-900 dark:text-neutral-300">pay</span>
                        </x-slot>
                    </x-quick-link>
                    <x-quick-link href="#" class="pointer-events-none opacity-50">
                        <i class="fa-solid fa-receipt text-accent-400 dark:text-white text-3xl"></i>
                        <x-slot name="quickLinkName">
                            <span class="text-sm text-center text-neutral-900 dark:text-neutral-300">invoice</span>
                        </x-slot>
                    </x-quick-link>
                </x-container>
            </x-container>
            <x-container
                class="relative flex-col gap-2 items-center ml-auto mt-6 lg:mt-14 bg-base-bgLight dark:bg-base-bg shadow-sm rounded p-0">
                <h2 class="p-4">Latest Transactions</h2>
                <a href="{{ route('transactions.index') }} " class="absolute top-2 right-2 p-2 underline">See all</a>
                <div class="w-full flex flex-col divide-y-2 divide-neutral-500">
                    @if($transactions->isEmpty())
                        <div class="flex justify-center items-center p-4 lg:px-8 select-none text-neutral-400/80">No
                            data
                        </div>
                    @endif
                    @foreach($transactions as $transaction)
                        <x-transaction-record :transaction="$transaction"/>
                    @endforeach
                </div>
            </x-container>
        </div>
        <div class="side-menu flex flex-col gap-5 h-full lg:w-1/4">
            <div class="flex flex-col gap-5 bg-base-bgLight dark:bg-base-bg shadow-sm rounded p-4">
                <div class="flex items-center justify-center gap-2 text-center h-fit pt-3">
                    <x-application-logo class="w-8"/>
                    <h2 class="text-2xl">{{ env("APP_NAME") }}</h2></div>
                <div class="flex-flex-col gap-2">
                    <h4 class="text-neutrals-400">Total</h4>
                    @php
                        $user = auth()->user();

                        $defaultWallet = $user->wallets->first(fn($w) => $w->currency->is_default);

                        $total = 0;

                        if ($defaultWallet) {
                            $defaultRate = $defaultWallet->currency->exchange_rate;

                            $total = $user->wallets->reduce(function ($carry, $wallet) use ($defaultRate) {
                                $walletRate = $wallet->currency->exchange_rate;

                                // Avoid division by zero
                                if ($walletRate == 0 || $defaultRate == 0) return $carry;

                                // Convert wallet balance to default currency
                                $converted = $wallet->balance * ($walletRate / $defaultRate);
                                return $carry + $converted;
                            }, 0);
                        }
                    @endphp

                    @if($defaultWallet)
                        <h2 class="text-4xl">
                            {{ $defaultWallet->currency->currency_symbol . number_format($total, 2, '.', '') }}
                        </h2>
                    @endif
                </div>
                <div class="flex-flex-col gap-2">
                    <h4 class="text-neutrals-400">Available</h4>
                    <div class="flex flex-col divide-y-2 divide-neutral-50 divide-dashed bg-accent-300/40 rounded p-2">
                        @foreach(auth()->user()->wallets as $wallet)
                            <h2 class="text-lg p-1">
                                {{ $wallet->currency->currency_code }}
                                : {{ $wallet->currency->currency_symbol . $wallet->balance }}
                            </h2>
                        @endforeach
                    </div>
                </div>
                <x-primary-link href="{{ route('transactions.create') }}" class="mb-4">Transfer Money</x-primary-link>
            </div>
            <div class="flex justify-between">
                <h2 class="text-xl">Stats</h2>
                <div class="flex gap-2 items-center text-neutral-200"><h2 class="text-lg">filter by </h2>
                    <p class="text-sm">v</p></div>
            </div>
            <div class="flex flex-col gap-5 bg-base-bgLight dark:bg-base-bg shadow-sm rounded p-4">
                <div class="flex gap-1">
                    <h2 class="text-xl">Deposits</h2>
                    <p class="text-neutrals-300">(last 7 days)</p>
                </div>
                <div class="flex-flex-col gap-2">
                    <h4 class="text-neutrals-400">Total</h4>
                    <h2 class="text-4xl">@money(auth()->user()->recharges->sum('amount'))</h2>
                </div>
                <x-primary-link href="{{ route('recharges.create') }}" class="mb-4">Deposit Money</x-primary-link>
            </div>
            <div class="flex flex-col gap-5 bg-base-bgLight dark:bg-base-bg shadow-sm rounded p-4">
                <div class="flex gap-1"><h2 class="text-xl">Withdrawals</h2>
                    <p class="text-neutrals-300">(last 7 days)</p></div>
                <div class="flex-flex-col gap-2">
                    <h4 class="text-neutrals-400">Total</h4>
                    <h2 class="text-4xl">---</h2>
                </div>
                <x-primary-link href="#" class="mb-4 pointer-events-none opacity-50">Withdraw Money</x-primary-link>
            </div>
        </div>
    </div>
</x-app-layout>
