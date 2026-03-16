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
use App\Models\User\Undangan\Kontak;
use App\Models\User;

class UndanganSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $users = User::where('role', 'user')->get();

        if (!$admin) {
            return;
        }

        // 1. Buat Template (for_template = true)
        for ($i = 1; $i <= 3; $i++) {
            $template = Undangan::create([
                'user_id' => $admin->id,
                'judul' => "Master Template #$i",
                'url' => "master-template-$i",
                'thumbnail_path' => "template_thumnails/idX7viV9AYGtZVr3dwp5XYCMfUCcMEm5tHW1hdTY.png",
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
                'template_id' => $index + 1,
                'judul' => "Undangan Pernikahan $user->name",
                'url' => "wedding-" . str_replace(' ', '-', strtolower($user->name)) . "-" . uniqid(),
                'thumbnail_path' => "assets/bingkai/bingkai2.png",
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
            'nama_prosesi' => 'Akad Nikah',
            'tanggal_mulai' => now()->addDays(30)->format('Y-m-d'),
            'waktu_mulai' => '09:00:00',
            'waktu_selesai' => '11:00:00',
            'detail_lokasi_nikah' => 'Masjid Al-Ikhlas, Jakarta',
            'show_map' => true,
            'latitude' => '-6.2088',
            'longitude' => '106.8456',
            'zoom' => 15,
            'doa_pengantin_pria' => 'Semoga menjadi keluarga yang sakinah, mawaddah, warahmah.',
            'doa_pengantin_wanita' => 'Aamiin ya Rabbal Alamin.',
            'no_rek_amplop' => 'BCA 1234567890 a/n Mempelai',
            'lokasi_pengiriman_kado' => 'Jl. Mawar No. 123, Jakarta',
        ]);

        // Data Mempelai
        DataMempelai::create([
            'undangan_id' => $undangan->id,
            'foto_pria_path' => 'assets/logo_up.png',
            'nama_panggilan_pria' => 'Putra',
            'nama_lengkap_pria' => 'Putra Pratama',
            'instagram_url_pria' => 'https://instagram.com/putra',
            'tiktok_url_pria' => 'https://tiktok.com/@putra',
            'x_url_pria' => 'https://x.com/putra',
            'keterangan_keluarga_pria' => 'Putra dari Bpk. Ahmad & Ibu Siti',
            'foto_wanita_path' => 'assets/logo_up.png',
            'nama_panggilan_wanita' => 'Putri',
            'nama_lengkap_wanita' => 'Putri Lestari',
            'instagram_url_wanita' => 'https://instagram.com/putri',
            'tiktok_url_wanita' => 'https://tiktok.com/@putri',
            'x_url_wanita' => 'https://x.com/putri',
            'keterangan_keluarga_wanita' => 'Putri dari Bpk. Bambang & Ibu Ani',
            'text_penutup' => 'Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir.',
        ]);

        // Acara (Multiple Acara)
        Acara::create([
            'undangan_id' => $undangan->id,
            'nama_acara' => 'Resepsi Pernikahan',
            'tanggal_acara' => now()->addDays(30)->format('Y-m-d'),
            'waktu_mulai_acara' => '12:00:00',
            'waktu_selesai_acara' => '15:00:00',
            'detail_lokasi_acara' => 'Gedung Serbaguna Jakarta',
            'show_map' => true,
            'zoom' => 15,
            'latitude_acara' => '-6.2088',
            'longitude_acara' => '106.8456',
        ]);

        Acara::create([
            'undangan_id' => $undangan->id,
            'nama_acara' => 'Ngunduh Mantu',
            'tanggal_acara' => now()->addDays(45)->format('Y-m-d'),
            'waktu_mulai_acara' => '10:00:00',
            'waktu_selesai_acara' => '13:00:00',
            'detail_lokasi_acara' => 'Kediaman Mempelai Pria, Bandung',
            'show_map' => false,
            'zoom' => 13,
        ]);

        // Gallery
        for ($i = 1; $i <= 4; $i++) {
            GalleryUndangan::create([
                'undangan_id' => $undangan->id,
                'image_path' => "assets/bingkai/bingkai-gallery.png",
            ]);
        }

        // Kisah Cinta
        KisahCinta::create([
            'undangan_id' => $undangan->id,
            'tanggal' => '14 Februari 2020',
            'judul' => 'Pertemuan Pertama',
            'peristiwa' => 'Kami pertama kali bertemu di sebuah cafe saat hujan turun.',
            'foto_kisah_cinta_path' => 'template_thumnails/idX7viV9AYGtZVr3dwp5XYCMfUCcMEm5tHW1hdTY.png',
        ]);

        KisahCinta::create([
            'undangan_id' => $undangan->id,
            'tanggal' => '01 Januari 2024',
            'judul' => 'Lamaran',
            'peristiwa' => 'Momen bahagia saat kami memutuskan untuk melangkah ke jenjang yang lebih serius.',
            'foto_kisah_cinta_path' => 'template_thumnails/idX7viV9AYGtZVr3dwp5XYCMfUCcMEm5tHW1hdTY.png',
        ]);

        // Kontak
        Kontak::create([
            'undangan_id' => $undangan->id,
            'nama' => 'Bpk. Ahmad (Keluarga Pria)',
            'pesan' => 'Bpk. Ahmad (Keluarga Pria)',
            'no_hp' => '081234567890',
        ]);

        Kontak::create([
            'undangan_id' => $undangan->id,
            'nama' => 'Bpk. Bambang (Keluarga Wanita)',
            'pesan' => 'Bpk. Bambang (Keluarga Wanita)',
            'no_hp' => '089876543210',
        ]);

        // Pengaturan
        PengaturanUndangan::create([
            'undangan_id' => $undangan->id,
            'reservation_form' => true,
            'komentar_undangan' => true,
            'jumlah_kehadiran' => true,
            'music_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3',
        ]);

        // Komentar
        KomentarUndangan::create([
            'undangan_id' => $undangan->id,
            'nama' => 'Budi Santoso',
            'pesan' => 'Selamat menempuh hidup baru ya Putra & Putri! Semoga bahagia selalu.',
            'balasan_pengantin' => 'Terima kasih atas doanya',
        ]);

        // Reservasi
        ReservasiUndangan::create([
            'undangan_id' => $undangan->id,
            'nama' => 'Anisa Rahma',
            'jumlah_hadir' => 2,
            'status_kehadiran' => 'hadir',
        ]);

        // Pengunjung
        PengunjungUndangan::create([
            'undangan_id' => $undangan->id,
            'jumlah_pengunjung' => rand(100, 1000),
        ]);
    }
}
