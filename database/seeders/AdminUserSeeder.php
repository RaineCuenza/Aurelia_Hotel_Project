<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@aurelia.local'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'phone' => '+63 917 000 0001',
                'role' => 'admin',
                'email_verified_at' => now(),
                'password' => Hash::make('123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@aurelia.local'],
            [
                'first_name' => 'Staff',
                'last_name' => 'User',
                'phone' => '+63 917 000 0002',
                'role' => 'staff',
                'email_verified_at' => now(),
                'password' => Hash::make('123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@aurelia.local'],
            [
                'first_name' => 'Customer',
                'last_name' => 'User',
                'phone' => '+63 917 000 0003',
                'role' => 'customer',
                'email_verified_at' => now(),
                'password' => Hash::make('123'),
            ]
        );
    }
}
