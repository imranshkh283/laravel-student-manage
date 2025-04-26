<?php

namespace Database\Seeders;

use App\Models\Classess;
use Illuminate\Database\Seeder;

class ClassessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classess::factory()->create([
            'class_name' => 'Class 1',
            'division' => 'A',
        ]);
    }
}
