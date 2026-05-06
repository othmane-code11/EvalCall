<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin One',
            'email' => 'admin@evalcall.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Admin Two',
            'email' => 'admin2@evalcall.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Manager One',
            'email' => 'manager1@evalcall.com',
            'password' => Hash::make('password123'),
            'role' => 'manager',
        ]);

        User::create([
            'name' => 'Manager Two',
            'email' => 'manager2@evalcall.com',
            'password' => Hash::make('password123'),
            'role' => 'manager',
        ]);

        User::create([
            'name' => 'Conseiller One',
            'email' => 'user1@evalcall.com',
            'password' => Hash::make('password123'),
            'role' => 'conseiller',
        ]);

        User::create([
            'name' => 'Conseiller Two',
            'email' => 'user2@evalcall.com',
            'password' => Hash::make('password123'),
            'role' => 'conseiller',
        ]);
    }
}