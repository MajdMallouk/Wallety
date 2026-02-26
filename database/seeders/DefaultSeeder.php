<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class DefaultSeeder extends Seeder
{
    public function run(): void
    {
        $defaultCurrency = Currency::query()->firstOrCreate(
            ['currency_code' => 'USD'],
            [
                'currency_symbol' => '$',
                'currency_fullname' => 'US Dollar',
                'currency_type' => 0,
                'exchange_rate' => 1,
                'is_default' => 1,
                'status' => 1,
            ],
        );

        Currency::query()->firstOrCreate(
            ['currency_code' => 'SYP'],
            [
                'currency_symbol' => '£',
                'currency_fullname' => 'Syrian Lira',
                'currency_type' => 0,
                'exchange_rate' => 0.0001,
                'is_default' => 0,
                'status' => 1,
            ],
        );

        User::query()
            ->whereIn('id', [1, 2])
            ->each(function (User $user) use ($defaultCurrency): void {
                Wallet::query()->firstOrCreate([
                    'user_id' => $user->id,
                    'currency_id' => $defaultCurrency->id,
                ], [
                    'balance' => 0,
                ]);
            });
    }
}
