<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class TransactionRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        // merge current user’s id into the incoming data
        $this->merge([
            'user_id' => $this->user()->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'wallet_id'   => ['required', 'exists:wallets,id'],
            'receiver_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $normalized = Str::lower($value);
                    $userExists = User::where('email', $normalized)
                        ->orWhere('username', $normalized)
                        ->exists();

                    if (!$userExists) {
                        $fail('The recipient username or email isn\'t registered.');
                    }
                },
            ],
            'amount'      => [
                'required',
                'numeric',
                'min:10',
                'max:100000000',
                function ($attribute, $value, $fail) {
                    $wallet = Wallet::where('id', $this->input('wallet_id'))
                        ->where('user_id', $this->user()->id)
                        ->first();

                    if (! $wallet || $wallet->balance < $value) {
                        $fail('Insufficient funds in the selected wallet.');
                    }
                },
            ],
            'message' => ['nullable','string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
    public function messages(): array
    {
        return [
            'receiver_id.exists' => 'That recipient username or email isn’t registered.',
            'amount.min' => 'The minimum transfer amount is :min.',
            'amount.max' => 'The maximum transfer amount is :max.',
        ];
    }

    // change the “receiver_id” label globally in all messages:
    public function attributes(): array
    {
        return [
            'receiver_id' => 'recipient',
            'amount' => 'transfer amount',
        ];
    }

}
