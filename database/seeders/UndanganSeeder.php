<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\Undangan\Undangan;
use App\Models\User\Undangan\TemplateUndanganPernikahan;
use App\Models\User\Undangan\DataMempelai;
use App\Models\User\Undangan\Acara;
use App\Models\User\Undangan\GalleryUndangan;
use App\Models\User\Undangan\KisahCinta;
use App\Models\User\Undangan\PengaturanUndangan;
use App\Models\User\Undangan\KomentarUndangan;
use App\Models\User\Undangan\ReservasiUndangan;
use App\Models\User\Undangan\PengunjungUndangan;
use App\Models\User;

class UndanganSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $users = User::where('role', 'user')->get();

        // 1. Buat Template (for_template = true)
        for ($i = 1; $i <= 3; $i++) {
            $template = Undangan::create([
                'user_id' => $admin->id,
                'judul' => "Master Template #$i",
                'url' => "master-template-$i",
                'thumbnail_path' => "templates/thumbs/template-$i.jpg",
                'salam_pembuka' => 'Assalamu`alaikum',
                'text_pembuka' => "Ini adalah konten dasar untuk template nomor $i.",
                'for_template' => true,
            ]);
            $this->seedUndanganDetails($template);
        }

        // 2. Buat Undangan User (for_template = false)
        foreach ($users as $index => $user) {
            $undangan = Undangan::create([
                'user_id' => $user->id,
                'judul' => "Undangan Pernikahan $user->name",
                'url' => "wedding-" . str_replace(' ', '-', strtolower($user->name)),
                'thumbnail_path' => "user/thumbs/wedding-$index.jpg",
                'salam_pembuka' => 'Salam Hangat',
                'text_pembuka' => "KAMI BERMOHON DOA RESTU ANDA.",
                'for_template' => false,
            ]);
            $this->seedUndanganDetails($undangan);
        }
    }

    private function seedUndanganDetails($undangan)
    {
        // Template Pernikahan
        TemplateUndanganPernikahan::create([
            'undangan_id' => $undangan->id,
            'tanggal_mulai_akad' => now()->addDays(30)->format('Y-m-d'),
            'waktu_mulai_akad' => '09:00:00',
            'detail_lokasi_akad_nikah' => 'Masjid Al-Ikhlas, Jakarta',
            'doa_pengantinn_pria' => 'Barakallahu laka...',
            'doa_pengantin_wanita' => 'Barakallahu laka...',
        ]);

        // Data Mempelai
        DataMempelai::create([
            'undangan_id' => $undangan->id,
            'nama_panggilan_pria' => 'Mempelai',
            'nama_lengkap_pria' => 'Mempelai Pria Lengkap',
            'nama_panggilan_wanita' => 'Mempelai',
            'nama_lengkap_wanita' => 'Mempelai Wanita Lengkap',
        ]);

        // Acara
        Acara::create([
            'undangan_id' => $undangan->id,
            'nama_acara' => 'Akad Nikah',
            'waktu_acara' => now()->addDays(30)->format('H:i:s'),
            'detail_lokasi_acara' => 'Alamat Lengkap Akad',
        ]);

        Acara::create([
            'undangan_id' => $undangan->id,
            'nama_acara' => 'Resepsi',
            'waktu_acara' => now()->addDays(30)->addHours(2)->format('H:i:s'),
            'detail_lokasi_acara' => 'Alamat Lengkap Resepsi',
        ]);

        // Gallery
        for ($i = 1; $i <= 2; $i++) {
            GalleryUndangan::create([
                'undangan_id' => $undangan->id,
                'image_path' => "galleries/photo-$i.jpg",
            ]);
        }

        // Kisah Cinta
        KisahCinta::create([
            'undangan_id' => $undangan->id,
            'tanggal' => now()->subYears(2)->format('Y-m-d'),
            'peristiwa' => 'Awal Bertemu',
        ]);

        // Pengaturan (Boolean values)
        PengaturanUndangan::create([
            'undangan_id' => $undangan->id,
            'reservation_form' => rand(0, 1),
            'komentar_undangan' => rand(0, 1),
            'jumlah_kehadiran' => rand(0, 1),
        ]);

        // Komentar
        KomentarUndangan::create([
            'undangan_id' => $undangan->id,
            'nama' => 'Budi',
            'pesan' => 'Selamat ya!',
        ]);

        // Reservasi (Enum values)
        ReservasiUndangan::create([
            'undangan_id' => $undangan->id,
            'nama' => 'Ani',
            'jumlah_hadir' => 2,
            'status_kehadiran' => 'Hadir',
        ]);

        ReservasiUndangan::create([
            'undangan_id' => $undangan->id,
            'nama' => 'Iwan',
            'jumlah_hadir' => 0,
            'status_kehadiran' => 'Tidak',
        ]);

        // Pengunjung
        PengunjungUndangan::create([
            'undangan_id' => $undangan->id,
            'jumlah_pengunjung' => rand(50, 500),
        ]);
    }
}
