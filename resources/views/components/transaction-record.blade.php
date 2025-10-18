@props(['transaction'])

<a href="{{ route('transactions.show', $transaction->id) }}" class="flex justify-between items-center p-4 lg:px-8 hover:bg-accent-400/80 transition">
    <div class="flex items-center gap-2 w-full">
        <x-transaction-arrow :isReceived="$transaction->isReceived()" />
        <div class="flex flex-col px-1 lg:px-4">
            <span class="text-xl">{{ $transaction->isReceived() ? 'Received' : 'Sent' }}</span>
            <span class="text-sm">{{ $transaction->created_at}}</span>
        </div>
        <h2 class="hidden lg:block line-clamp-2 text-wrap w-1/2 text-neutral-300">{{ $transaction->message }}</h2>
    </div>
    <h2 class="text-xl font-semibold">{{ $transaction->currency->currency_symbol . $transaction->amount }}</h2>
</a>
