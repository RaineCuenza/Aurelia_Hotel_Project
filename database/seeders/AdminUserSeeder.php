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
            ['email' => 'admin@stayease.local'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'role' => 'admin',
                'password' => Hash::make('123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@stayease.local'],
            [
                'first_name' => 'Staff',
                'last_name' => 'User',
                'role' => 'staff',
                'password' => Hash::make('123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@stayease.local'],
            [
                'first_name' => 'Customer',
                'last_name' => 'User',
                'role' => 'customer',
                'password' => Hash::make('123'),
            ]
        );
    }
}
