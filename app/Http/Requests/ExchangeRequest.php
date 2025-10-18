<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Wallet;

class ExchangeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // 1) “From Wallet” must exist and belong to current user
            'wallet_id' => [
                'required',
                'integer',
                'exists:wallets,id',
                function($attribute, $value, $fail) {
                    $wallet = Wallet::find($value);
                    if (!$wallet || $wallet->user_id !== $this->user()->id) {
                        $fail('Invalid wallet selection.');
                    }
                },
            ],

            // 2) “To Currency” must exist
            'target_currency_id' => [
                'required',
                'integer',
                'exists:currencies,id',
                function($attribute, $value, $fail) {
                    // We only need to check “not same currency” here if wallet_id is valid
                    $walletId = $this->input('wallet_id');
                    if (!$walletId) {
                        return; // skip if wallet_id invalid or missing; wallet_id rule will catch it
                    }
                    $wallet = Wallet::find($walletId);
                    if ($wallet && $wallet->currency_id === (int) $value) {
                        $fail('You cannot exchange into the same currency.');
                    }
                },
            ],

            // 3) Amount must be numeric, ≥ 0.01, ≤ wallet balance
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                function($attribute, $value, $fail) {
                    $walletId = $this->input('wallet_id');
                    if (!$walletId) {
                        return; // if wallet_id missing, that rule will handle it
                    }
                    $wallet = Wallet::find($walletId);
                    if (!$wallet) {
                        return; // wallet_id rule already fails
                    }
                    if ($value > $wallet->balance) {
                        $fail('Insufficient balance in the selected wallet.');
                    }
                },
            ],
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
