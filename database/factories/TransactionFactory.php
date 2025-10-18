<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'receiver_id' => User::factory(),
            'amount' => $this->faker->randomFloat(0, 10, 1000),
            'message' => $this->faker->paragraph(mt_rand(1, 3), true),
            'currency_id' => $this->faker->randomElement([1, 2]),
            'wallet_id' => $this->faker->randomElement([1, 2]),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
