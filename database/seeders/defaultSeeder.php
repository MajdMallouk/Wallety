<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class defaultSeeder extends Seeder
{
    public function run(): void
    {
        Transaction::factory(10)->create();

        DB::table('currencies')->insert([
            [
                'currency_code' => 'USD',
                'currency_symbol' => '$',
                'currency_fullname' => 'US Dollar',
                'currency_type' => 0, // 0 = FIAT
                'exchange_rate' => 1.0, // Base currency
                'is_default' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'currency_code' => 'SYP',
                'currency_symbol' => '£',
                'currency_fullname' => 'Syrian Lira',
                'currency_type' => 0, // 0 = FIAT
                'exchange_rate' => 0.0001,
                'is_default' => 0,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);


        // Create default wallets

        $defaultCurrency = Currency::where('is_default', true)->first();
        User::find(1)->wallets()->create([
            'currency_id' => $defaultCurrency->id,
            'balance'     => 0,
        ]);
        User::find(2)->wallets()->create([
            'currency_id' => $defaultCurrency->id,
            'balance'     => 0,
        ]);
    }
}
