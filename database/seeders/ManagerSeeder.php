<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
            'role' => 'manager',
            'password' => Hash::make('12345678'),
        ]);
    }
}