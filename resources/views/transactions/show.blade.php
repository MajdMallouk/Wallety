<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transaction Details') }}
        </h2>
    </x-slot>

    <x-container class="max-w-3xl mx-auto flex-col gap-2 items-center ml-auto mt-6 lg:mt-14 bg-base-bgLight dark:bg-base-bg rounded p-0">
        <h2 class="p-4">{{ $transaction->created_at->format('Y-M-j - h:i A') }}</h2>
        <div class="w-full flex flex-col">
            <a class="flex justify-between items-center p-4 lg:px-8">
                <div class="flex items-center gap-2 w-full">
                    <x-transaction-arrow :isReceived="$transaction->isReceived()" />
                    <span class="text-xl">{{ $transaction->isReceived() ? 'Received' : 'Sent' }}</span>
                    <h2 class="hidden lg:block line-clamp-2 text-wrap w-1/2 text-neutral-300">{{ $transaction->message }}</h2>
                </div>
                <h2 class="text-xl font-semibold">@money($transaction->amount)</h2>
            </a>
            <div class="h-64 bg-gray-200 dark:bg-gray-700 rounded-b p-2 lg:p-8">
                <div class="flex text-center items-center">
                    <!-- Header -->
                    <div class="flex flex-col items-start justify-center text-left">
                        <div class="p-2 font-semibold">Transaction ID :</div>
                        @if($transaction->isReceived())
                            <div class="p-2 font-semibold">Sender :</div>
                        @endif
                        <div class="p-2 font-semibold">Sender E-mail :</div>
                        <div class="p-2 font-semibold">Message :</div>
                    </div>

                    <!-- Rows -->
                    <div class="flex flex-col items-start justify-center text-left">
                        <div class="p-2">#{{ $transaction->id }}</div>
                        @if($transaction->isReceived())
                            <div class="p-2">{{ $transaction->isReceived() ? $transaction->user->name : '' }}</div>
                        @endif
                        <div class="p-2">{{ $transaction->isReceived() ? $transaction->user->email : 'You' }}</div>
                        <div class="p-2">
                            {!! $transaction->message ? e($transaction->message) : '<p class="text-neutral-500/80 select-none">Empty</p>' !!}
                        </div>
                        {{--                        <code class="p-2">maybe a full amount (before fees)</code>--}}

                    </div>
                </div>
            </div>
        </div>
    </x-container>
</x-app-layout>
