<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExchangeRequest;
use App\Models\Currency;
use App\Models\Exchange;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class ExchangeController extends Controller
{
    public function index()
    {
        return Exchange::all();
    }

    public function create()
    {
        $user = auth()->user()->load('wallets.currency');
        $currencies = Currency::all();
        $ownRates = $user->wallets
            ->pluck('currency.exchange_rate', 'currency.currency_code')
            ->toArray();
        return view('exchanges.create', compact('user', 'currencies', 'ownRates'));
    }

    public function store(ExchangeRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();
        DB::transaction(function() use ($data, $user) {
            // 1. Fetch the “from” wallet and ensure it belongs to the user
            $fromWallet = Wallet::where('id', $data['wallet_id'])
                ->where('user_id', $user->id)
                ->with('currency')
                ->firstOrFail();

            // 2. Fetch the target currency
            $toCurrency = Currency::findOrFail($data['target_currency_id']);

            // 3. Prevent exchanging into the same currency
            if ($fromWallet->currency_id === $toCurrency->id) {
                throw new \Exception('Cannot exchange to the same currency.');
            }

            // 4. Find or create the user’s wallet in the target currency
            $toWallet = Wallet::firstOrCreate(
                [
                    'user_id'     => $user->id,
                    'currency_id' => $toCurrency->id,
                ],
                [
                    'balance' => 0,
                ]
            );

            // 5. Snapshot exchange rates
            $rateFrom = $fromWallet->currency->exchange_rate;
            $rateTo   = $toCurrency->exchange_rate;

            if ($rateFrom <= 0 || $rateTo <= 0) {
                throw new \Exception('Invalid exchange rate.');
            }

            $fromAmount = $data['amount'];
            $toAmount   = $fromAmount * ($rateFrom / $rateTo);

            // 6. Ensure sufficient balance
            if ($fromWallet->balance < $fromAmount) {
                throw new \Exception('Insufficient balance in the selected wallet.');
            }

            // 7. Update balances
            $fromWallet->decrement('balance', $fromAmount);
            $toWallet->increment('balance', $toAmount);

            // 8. Create exchange record
            Exchange::create([
                'user_id'          => $user->id,
                'from_wallet_id'   => $fromWallet->id,
                'to_wallet_id'     => $toWallet->id,
                'from_currency_id' => $fromWallet->currency_id,
                'to_currency_id'   => $toCurrency->id,
                'from_amount'      => $fromAmount,
                'to_amount'        => $toAmount,
                'rate'             => $rateFrom / $rateTo,
            ]);
        });

        return redirect()
            ->route('dashboard')
            ->with('success', 'Exchange completed successfully.');
    }
    public function show(Exchange $exchange)
    {
        return $exchange;
    }

    public function update(ExchangeRequest $request, Exchange $exchange)
    {
        $exchange->update($request->validated());

        return $exchange;
    }

    public function destroy(Exchange $exchange)
    {
        $exchange->delete();

        return response()->json();
    }
}
