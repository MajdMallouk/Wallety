<?php

namespace App\Http\Controllers;

use App\Http\Requests\RechargeRequest;
use App\Models\Currency;
use App\Models\Exchange;
use App\Models\Recharge;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RechargeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return response()->json([
            '(' => 'You should not be seeing this, should you?',
            'Your cheeky deposits' => Recharge::where('user_id', $user->id)->get()
        ]);
    }

    public function create()
    {
        $user = auth()->user()->load('wallets.currency');
        $currencies = Currency::all();
        return view('recharges.create', compact('user', 'currencies'));
    }
    public function store(RechargeRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        DB::transaction(function() use ($data, $user) {
            // 1) Fetch (or create) the selected wallet for this user
            $wallet = Wallet::firstOrCreate(
                [
                    'user_id'     => $user->id,
                    'currency_id' => $data['currency_id'],  // or: 'id' => $data['wallet_id'] if you passed wallet_id directly
                ],
                [
                    'balance' => 0,
                ]
            );

            // 2) (Payment gateway logic goes here...)

            // 3) Increment that wallet’s balance instead of the user's top‐level balance
            $wallet->increment('balance', $data['amount']);

            // 4) Record the recharge, linking to the wallet
            Recharge::create([
                'user_id'       => $user->id,
                'wallet_id'     => $wallet->id,
                'method'        => $data['method'],
                'amount'        => $data['amount'],
            ]);
        });

        return redirect()
            ->route('dashboard')
            ->with('success', 'Recharged successfully');
    }
}
