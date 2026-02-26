<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'wallet_id'   => [
                'required',
                Rule::exists('wallets', 'id')->where(fn ($query) => $query->where('user_id', $this->user()->id)),
            ],
            'receiver_id' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $normalized = Str::lower(trim((string) $value));
                    $recipient = User::where('email', $normalized)
                        ->orWhere('username', $normalized)
                        ->first();

                    if (! $recipient) {
                        $fail('The recipient username or email isn\'t registered.');

                        return;
                    }

                    if ($recipient->id === $this->user()->id) {
                        $fail('You can\'t send money to yourself.');
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
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
    public function messages(): array
    {
        return [
            'wallet_id.exists' => 'Selected wallet is invalid.',
            'amount.min' => 'The minimum transfer amount is :min.',
            'amount.max' => 'The maximum transfer amount is :max.',
            'message.max' => 'Message can\'t be longer than :max characters.',
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
