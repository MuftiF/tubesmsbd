<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User tunggal
        User::create([
            'name'     => 'Test User',
            'email'    => 'test@gmail.com',
            'no_hp'    => '081111111111',
            'role'     => 'user',
            'password' => Hash::make('12345678'),
        ]);

        // Multiple users
        $users = [
            [
                'name'     => 'Admin User',
                'email'    => 'admin@gmail.com',
                'no_hp'    => '082222222222',
                'role'     => 'admin',
                'password' => Hash::make('12345678'),
            ],
            [
                'name'     => 'Regular User',
                'email'    => 'user@gmail.com',
                'no_hp'    => '083333333333',
                'role'     => 'user',
                'password' => Hash::make('12345678'),
            ],
            [
                'name'     => 'Manager User',
                'email'    => 'manager@gmail.com',
                'no_hp'    => '084444444444',
                'role'     => 'manager',
                'password' => Hash::make('12345678'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}