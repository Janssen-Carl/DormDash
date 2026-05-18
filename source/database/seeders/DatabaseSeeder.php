<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Address;
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
        $address = Address::firstOrCreate([
            'street' => 'Default Street',
            'city' => 'Batangas',
            'province_state' => 'Batangas',
            'postal_code' => '4200',
            'phone' => '09123456789',
            'email' => 'default@vendor.com',
            'country' => 'Philippines',
            'user_id' => 1, // adjust if needed
        ]);

        // Vendor 1
        $user1 = User::firstOrCreate(
            ['username' => 'bumblebee_vendor'],
            [
                'email' => 'bumblebee@example.com',
                'password' => bcrypt('password123'),
                'role' => 'vendor',
            ]
        );

        Vendor::firstOrCreate(
            ['vendor_id' => $user1->user_id],
            [
                'name' => 'Bumble Bee',
                'phone' => '09123456789',
                'website' => 'https://bumblebee.com',
                'address_id' => $address->address_id,
                'active' => true,
            ]
        );

        // Vendor 2
        $user2 = User::firstOrCreate(
            ['username' => 'dorm_essentials'],
            [
                'email' => 'dorm@example.com',
                'password' => bcrypt('password123'),
                'role' => 'vendor',
            ]
        );

        Vendor::firstOrCreate(
            ['vendor_id' => $user2->user_id],
            [
                'name' => 'Dorm Essentials',
                'phone' => '09987654321',
                'website' => 'https://dormessentials.com',
                'address_id' => $address->address_id,
                'active' => true,
            ]
        );
    }
}
