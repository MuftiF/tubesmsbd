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
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'role' => 'user',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        // Atau untuk multiple users
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'remember_token' => \Illuminate\Support\Str::random(10),
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@gmail.com',
                'role' => 'user',
                'email_verified_at' => now(),
                'password' => Hash::make('user1234'),
                'remember_token' => \Illuminate\Support\Str::random(10),
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
