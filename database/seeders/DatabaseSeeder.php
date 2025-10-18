<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'majd',
                'username' => 'user01',
                'email' => 'user@ex.co',
                'phone' => '123456789',
                'country' => 'UAE',
                'country_code' => '+971',
                'password' => bcrypt('passdodo'),
                'completed_profile' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'user02',
                'username' => 'user02',
                'email' => 'user02@ex.co',
                'phone' => '3211234567890',
                'country' => 'UAE',
                'country_code' => '+971',
                'password' => bcrypt('passdodo'),
                'completed_profile' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        $this->call(DefaultSeeder::class);
    }
}
