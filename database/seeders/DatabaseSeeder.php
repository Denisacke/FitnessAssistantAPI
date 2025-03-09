<?php

namespace Database\Seeders;

use App\Http\Enums\ActivityLevel;
use App\Http\Enums\Sex;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password'),
            'sex' => Sex::MALE,
            'weight' => 75.5,
            'age' => 30,
            'activity_level' => ActivityLevel::MODERATE,
        ]);

        User::create([
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            'password' => Hash::make('password'),
            'sex' => Sex::FEMALE,
            'weight' => 65.2,
            'age' => 28,
            'activity_level' => ActivityLevel::ACTIVE,
        ]);
    }
}
