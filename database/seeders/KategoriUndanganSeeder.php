<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\KategoriUndangan;

class KategoriUndanganSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Pernikahan', 'status' => true],
            ['name' => 'Ulang Tahun', 'status' => true],
            ['name' => 'Khitanan', 'status' => true],
            ['name' => 'Aqiqah', 'status' => true],
            ['name' => 'Wisuda', 'status' => true],
            ['name' => 'Event Kantor', 'status' => false], // Non-aktif status
            ['name' => 'Workshop', 'status' => false],     // Non-aktif status
        ];

        foreach ($categories as $cat) {
            KategoriUndangan::create($cat);
        }
    }
}
