<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Langganan;
use App\Models\Admin\PoinLangganan;

class LanggananSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Paket Gratis
        $gratis = Langganan::create([
            'nama_paket' => 'Gratis',
            'harga' => 0,
            'diskon' => 0,
        ]);

        PoinLangganan::create(['langganan_id' => $gratis->id, 'judul' => 'Akses 1 Template', 'is_checklis' => true]);
        PoinLangganan::create(['langganan_id' => $gratis->id, 'judul' => 'Masa Aktif 7 Hari', 'is_checklis' => true]);
        PoinLangganan::create(['langganan_id' => $gratis->id, 'judul' => 'No Custom Domain', 'is_checklis' => false]);

        // 2. Paket Premium
        $premium = Langganan::create([
            'nama_paket' => 'Premium Pro',
            'harga' => 150000,
            'diskon' => 20,
        ]);

        PoinLangganan::create(['langganan_id' => $premium->id, 'judul' => 'Akses Semua Template', 'is_checklis' => true]);
        PoinLangganan::create(['langganan_id' => $premium->id, 'judul' => 'Masa Aktif Selamanya', 'is_checklis' => true]);
        PoinLangganan::create(['langganan_id' => $premium->id, 'judul' => 'Custom Music & Video', 'is_checklis' => true]);
        PoinLangganan::create(['langganan_id' => $premium->id, 'judul' => 'Manajemen RSVP', 'is_checklis' => true]);

        // 3. Paket Bisnis
        $bisnis = Langganan::create([
            'nama_paket' => 'Bisnis Enterprise',
            'harga' => 500000,
            'diskon' => 0,
        ]);

        PoinLangganan::create(['langganan_id' => $bisnis->id, 'judul' => 'White Label', 'is_checklis' => true]);
        PoinLangganan::create(['langganan_id' => $bisnis->id, 'judul' => 'Priority Support', 'is_checklis' => true]);
    }
}
