<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(10)->create([
            'latitude' => fake()->latitude(40.7, 40.8),
            'longitude' => fake()->longitude(-74.0, -73.9)
        ]);
    }
}
