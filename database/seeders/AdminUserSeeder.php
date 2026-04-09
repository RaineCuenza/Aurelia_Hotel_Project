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
                'name' => 'Admin User',
                'role' => 'admin',
                'password' => Hash::make('123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@stayease.local'],
            [
                'name' => 'Staff User',
                'role' => 'staff',
                'password' => Hash::make('123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@stayease.local'],
            [
                'name' => 'Customer User',
                'role' => 'customer',
                'password' => Hash::make('123'),
            ]
        );
    }
}
