<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\PanduanPenggunaan;

class PanduanPenggunaanSeeder extends Seeder
{
    public function run(): void
    {
        $guides = [
            [
                'title' => 'Cara Membuat Undangan',
                'content' => 'Langkah-langkah membuat undangan digital sangat mudah...',
                'is_active' => true,
                'order_position' => 1,
                'view_count' => 100,
            ],
            [
                'title' => 'Cara Upgrade Premium',
                'content' => 'Ikuti panduan berikut untuk upgrade ke akun premium...',
                'is_active' => true,
                'order_position' => 2,
                'view_count' => 50,
            ],
            [
                'title' => 'Panduan (Draft)',
                'content' => 'Konten panduan yang belum dipublikasikan.',
                'is_active' => false,
                'order_position' => 3,
                'view_count' => 0,
            ],
        ];

        foreach ($guides as $guide) {
            PanduanPenggunaan::create($guide);
        }
    }
}
