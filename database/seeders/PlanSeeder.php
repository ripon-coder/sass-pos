<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Starter',
            'price' => 9.99,
            'max_branches' => 3,
            'max_users' => 5,
        ]);

        Plan::create([
            'name' => 'Growth',
            'price' => 19.99,
            'max_branches' => 5,
            'max_users' => 10,
        ]);

        Plan::create([
            'name' => 'Pro',
            'price' => 29.99,
            'max_branches' => 10,
            'max_users' => 20,
        ]);
    }
}
