<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LanggananSeeder::class,
            UserSeeder::class,
            KategoriUndanganSeeder::class,
            UndanganSeeder::class,
            TemplateUndanganSeeder::class,
            TransactionSeeder::class,
            FeedbackUserSeeder::class,
            PanduanPenggunaanSeeder::class,
        ]);
    }
}
