<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenants;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'tenant_id' => Tenants::inRandomOrder()->value('id'),
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);
        User::create([
            'tenant_id' => Tenants::inRandomOrder()->value('id'),
            'name' => 'Ripon',
            'email' => 'ripon@gmail.com',
            'password' => bcrypt('password'),
        ]);
    }
}
