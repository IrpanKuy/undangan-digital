<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admins
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@antigravity.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'subscription' => 'premium',
            'no_hp' => '081111111111',
        ]);

        User::create([
            'name' => 'Admin Support',
            'email' => 'support@antigravity.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'subscription' => 'free',
            'no_hp' => '082222222222',
        ]);

        // Regular Users (Free & Premium)
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "User Gratis $i",
                'email' => "user.free$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'user',
                'subscription' => 'free',
                'no_hp' => '0812345678' . $i,
            ]);

            User::create([
                'name' => "User Premium $i",
                'email' => "user.premium$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'user',
                'subscription' => 'premium',
                'no_hp' => '0898765432' . $i,
            ]);
        }
    }
}
