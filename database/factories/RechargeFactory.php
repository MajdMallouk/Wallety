<?php

namespace Database\Factories;

use App\Models\Recharge;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class RechargeFactory extends Factory
{
    protected $model = Recharge::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomNumber(),
            'method' => $this->faker->word(),
            'balance' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
