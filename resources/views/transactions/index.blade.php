<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transactions') }}
        </h2>
    </x-slot>
    <div>
         @if($transactions->isEmpty())
             <h2 class="text-xl text-center">{{ "Make a transaction and it will appear here" }}</h2>
        @else
                <x-container class="flex-col gap-2 items-center ml-auto mt-6 lg:mt-14 bg-base-bgLight dark:bg-base-bg rounded p-0">
                    <h2 class="p-4">All Transactions</h2>
                    <div class="w-full flex flex-col divide-y-2 divide-neutral-500">
                        @foreach($transactions as $transaction)
                            <x-transaction-record :transaction="$transaction"/>
                        @endforeach
                    </div>
                </x-container>
        @endif

             <div class="mt-5">
                 {{ $transactions->links() }}
             </div>
    </div>
</x-app-layout>
