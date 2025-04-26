<?php

namespace Database\Seeders;

use App\Models\Students;
use Illuminate\Database\Seeder;

class StudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Students::factory()->create([
            'name' => 'Student 1',
            'class_id' => 1,
            'roll_number' => '1',
        ]);
    }
}
