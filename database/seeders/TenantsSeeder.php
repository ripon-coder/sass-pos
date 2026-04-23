<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\Tenants;

class TenantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenants::create([
            'plan_id' => Plan::inRandomOrder()->value('id'),
            'name' => 'EZP',
            'domain' => 'ezpos',
            'is_active' => rand(0,1),
        ]);

        Tenants::create([
            'plan_id' => Plan::inRandomOrder()->value('id'),
            'name' => 'EZP1',
            'domain' => 'ezpos1',
            'is_active' => rand(0,1),
        ]);

        Tenants::create([
            'plan_id' => Plan::inRandomOrder()->value('id'),
            'name' => 'EZP2',
            'domain' => 'ezpos2',
            'is_active' => rand(0,1),
        ]);
    }
}
