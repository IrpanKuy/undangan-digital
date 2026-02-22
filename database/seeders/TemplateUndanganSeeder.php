<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\TemplateUndangan;
use App\Models\Admin\KategoriUndangan;
use App\Models\User\Undangan\Undangan;

class TemplateUndanganSeeder extends Seeder
{
    public function run(): void
    {
        $kategoriList = KategoriUndangan::where('status', true)->get();
        $undanganTemplates = Undangan::where('for_template', true)->get();

        foreach ($undanganTemplates as $index => $undangan) {
            $kat = $kategoriList[$index % $kategoriList->count()];

            // Mixed Boolean statuses
            TemplateUndangan::create([
                'undangan_id' => $undangan->id,
                'kategori_undangan_id' => $kat->id,
                'thumbnail_template' => $undangan->thumbnail_path,
                'judul_undangan' => $undangan->judul,
                'template_premium' => ($index % 2 == 0), // alternating premium status
                'file_name' => "template-view-$index",
                'is_active' => ($index != 2), // testing inactive status
                'music_url' => 'https://example.com/music.mp3',
                'usage' => rand(0, 100),
            ]);
        }
    }
}
