<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        $currencyId = Currency::query()->firstOrCreate(
            ['currency_code' => 'USD'],
            [
                'currency_symbol' => '$',
                'currency_fullname' => 'US Dollar',
                'currency_type' => 0,
                'exchange_rate' => 1,
                'is_default' => 1,
                'status' => 1,
            ],
        )->id;

        return [
            'user_id' => User::factory(),
            'receiver_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'message' => $this->faker->realText(120),
            'currency_id' => $currencyId,
            'wallet_id' => fn (array $attributes) => Wallet::query()->firstOrCreate(
                [
                    'user_id' => $attributes['user_id'],
                    'currency_id' => $attributes['currency_id'],
                ],
                ['balance' => 1000],
            )->id,
            'status' => 'paid',
        ];
    }
}
