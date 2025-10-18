@props([
    'transaction'
])

<td {{ $attributes->class(['hidden lg:block py-4']) }}>{{ $transaction->created_at->format('Y-M-j') }}</td>
