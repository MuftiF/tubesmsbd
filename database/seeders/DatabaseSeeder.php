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
            'role'     => 'user',
            'password' => Hash::make('password123'),
        ]);

        // Multiple users
        $users = [
            [
                'name'     => 'Admin User',
                'role'     => 'admin',
                'password' => Hash::make('admin123'),
            ],
            [
                'name'     => 'Regular User',
                'role'     => 'user',
                'password' => Hash::make('user1234'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
