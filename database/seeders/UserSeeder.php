<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Imran',
            'last_name' => 'Shaikh',
            'password' => 'admin',
            'email' => 'admin@admin.com',
            'role' => 'admin',
        ]);
    }
}
