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
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'langganan_id' => 1,
            'no_hp' => '081111111111',
            'profile_path' => 'assets/handphone.png',
        ]);

        User::create([
            'name' => 'Admin Support',
            'email' => 'support@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'langganan_id' => 1,
            'no_hp' => '082222222222',
            'profile_path' => 'handphone.png',
        ]);

        // Regular Users (Free & Premium)
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "User Gratis $i",
                'email' => "free$i@gmail.com",
                'password' => Hash::make('password'),
                'role' => 'user',
                'langganan_id' => 1,
                'no_hp' => '0812345678' . $i,
                'profile_path' => 'handphone.png',
            ]);

            User::create([
                'name' => "User Premium $i",
                'email' => "premium$i@gmail.com",
                'password' => Hash::make('password'),
                'role' => 'user',
                'langganan_id' => 2,
                'no_hp' => '0898765432' . $i,
                'profile_path' => 'handphone.png',
            ]);
        }
    }
}
