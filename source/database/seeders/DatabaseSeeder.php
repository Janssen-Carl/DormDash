<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test users
        User::factory(5)->create();

        // Create specific test users
        User::firstOrCreate(
            ['username' => 'johndoe'],
            [
                'email' => 'john@example.com',
                'password' => bcrypt('password123'),
                'role' => 'customer'
            ]
        );

        User::firstOrCreate(
            ['username' => 'vendor_store'],
            [
                'email' => 'vendor@example.com',
                'password' => bcrypt('password123'),
                'role' => 'vendor'
            ]
        );

    }
}
