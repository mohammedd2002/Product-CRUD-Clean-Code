<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubscriotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subscriptions')->insert([
    [
        'name' => 'Yearly',
        'price' => 6000,
        'duration_days' => 365,
        'features' => 'Unlimited Labs',
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'name' => '3 Months',
        'price' => 2400,
        'duration_days' => 90,
        'features' => 'Unlimited Labs',
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'name' => '1 Month',
        'price' => 2400,
        'duration_days' => 30,
        'features' => 'Unlimited Labs',
        'created_at' => now(),
        'updated_at' => now()
    ],
]);

    }
}
