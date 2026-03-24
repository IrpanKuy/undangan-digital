<?php

namespace App\Http\Controllers;

use App\Models\Admin\TemplateUndangan;
use App\Models\User\Undangan\Undangan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UndanganPreviewController extends Controller
{
    public function preview($judul_undangan){

            $templateUndangan = TemplateUndangan::with([
                'kategoriUndangan', 
                'undanganPreview',
                'undanganPreview.acaras',
                'undanganPreview.dataMempelai',
                'undanganPreview.templateUndanganPernikahan',
                'undanganPreview.kisahCintas',
                'undanganPreview.galleryUndangans',
                'undanganPreview.komentarUndangans',
            ])
            ->where('judul_undangan', $judul_undangan)
            ->first();
        
        $namaFile = $templateUndangan->file_name;
        $undangan = $templateUndangan->undanganPreview;
        $kategori = $templateUndangan->kategoriUndangan->name;
        // dd('undangan =>', $undangan,
        //         'acara =>', $undangan->acaras,
        //         'dataMempelai =>', $undangan->dataMempelai,
        //         'templateUndanganPernikahan =>', $undangan->templateUndanganPernikahan,
        //         'kisahCinta =>', $undangan->kisahCintas,
        //         'galleryUndangan =>', $undangan->galleryUndangans,
        //         'komentarUndangan =>', $undangan->komentarUndangans,);
                
        return Inertia::render("templateUndangan/{$kategori}/{$namaFile}", [
                'undangan' => $undangan,
                'acara' => $undangan->acaras,
                'dataMempelai' => $undangan->dataMempelai,
                'templateUndanganPernikahan' => $undangan->templateUndanganPernikahan,
                'kisahCinta' => $undangan->kisahCintas,
                'galleryUndangan' => $undangan->galleryUndangans,
                'komentarUndangan' => $undangan->komentarUndangans,
        ]);
    }

    public function previewCreateEdit(Request $request)
    {
        $data = json_decode($request->input('preview_data'), true);

        if (!$data) {
            abort(400, 'Data preview tidak valid atau kosong.');
        }

        array_walk_recursive($data, function (&$item) {
            if ($item === "") {
                $item = null;
            }
        });
        // 1. Data Utama Undangan
        $undangan = [
            'id' => $data['id'] ?? 0,
            'template_id' => $data['template_id'] ?? null,
            'judul' => $data['judul'] ?? null,
            'url' => $data['url'] ?? null,
            'thumbnail_path' => $data['thumbnail_path'] ?? null,
            'salam_pembuka' => $data['salam_pembuka'] ?? null,
            'text_pembuka' => $data['text_pembuka'] ?? null,
            'video_youtube_url' => $data['video_youtube_url'] ?? null,
            'for_template' => false,
        ];
        
        // 2. Data Acara (Array of Acaras)
        $acara = array_map(function ($item) {
            return [
                'nama_acara' => $item['nama_acara'] ?? null,
                'tanggal_acara' => $item['tanggal_acara'] ?? null,
                'waktu_mulai_acara' => $item['waktu_mulai_acara'] ?? null,
                'waktu_selesai_acara' => $item['waktu_selesai_acara'] ?? null,
                'detail_lokasi_acara' => $item['detail_lokasi_acara'] ?? null,
                'show_map' => $item['show_map'] ?? true,
                'latitude_acara' => $item['lokasi_acara']['lat'] ?? null,
                'longitude_acara' => $item['lokasi_acara']['lng'] ?? null,
                'zoom' => $item['lokasi_acara']['zoom'] ?? 15,
            ];
        }, $data['acaras'] ?? []);

        // 3. Data Mempelai (Object)
        $dataMempelai = [
            'nama_panggilan_pria' => $data['nama_panggilan_pria'] ?? null,
            'nama_lengkap_pria' => $data['nama_lengkap_pria'] ?? null,
            'keterangan_keluarga_pria' => $data['keterangan_keluarga_pria'] ?? null,
            'foto_pria_path' => $data['foto_pria_path'] ?? null,
            'instagram_url_pria' => $data['instagram_url_pria'] ?? null,
            'tiktok_url_pria' => $data['tiktok_url_pria'] ?? null,
            'x_url_pria' => $data['x_url_pria'] ?? null,

            'nama_panggilan_wanita' => $data['nama_panggilan_wanita'] ?? null,
            'nama_lengkap_wanita' => $data['nama_lengkap_wanita'] ?? null,
            'keterangan_keluarga_wanita' => $data['keterangan_keluarga_wanita'] ?? null,
            'foto_wanita_path' => $data['foto_wanita_path'] ?? null,
            'instagram_url_wanita' => $data['instagram_url_wanita'] ?? null,
            'tiktok_url_wanita' => $data['tiktok_url_wanita'] ?? null,
            'x_url_wanita' => $data['x_url_wanita'] ?? null,

            'text_penutup' => $data['text_penutup'] ?? null,
        ];

        // 4. Data Template Pernikahan / Akad (Object)
        $templateUndanganPernikahan = [
            'nama_prosesi' => $data['nama_prosesi'] ?? null,
            'tanggal_mulai' => $data['tanggal_mulai'] ?? null,
            'waktu_mulai' => $data['waktu_mulai'] ?? null,
            'waktu_selesai' => $data['waktu_selesai'] ?? null,
            'detail_lokasi_nikah' => $data['detail_lokasi_nikah'] ?? null,
            'show_map' => $data['show_map'] ?? true,
            'latitude' => $data['lokasi_nikah']['lat'] ?? null,
            'longitude' => $data['lokasi_nikah']['lng'] ?? null,
            'zoom' => $data['lokasi_nikah']['zoom'] ?? 15,
            'doa_pengantin_pria' => $data['doa_pengantin_pria'] ?? null,
            'doa_pengantin_wanita' => $data['doa_pengantin_wanita'] ?? null,
            'no_rek_amplop' => $data['no_rek_amplop'] ?? null,
            'lokasi_pengiriman_kado' => $data['lokasi_pengiriman_kado'] ?? null,
        ];

        // 5. Data Kisah Cinta (Array of Kisah Cinta)
        $kisahCinta = array_map(function ($kisah) {
            return [
                'tanggal' => $kisah['tanggal'] ?? null,
                'judul' => $kisah['judul'] ?? null,
                'peristiwa' => $kisah['peristiwa'] ?? null,
                'foto_kisah_cinta_path' => $kisah['foto'] ?? null,
            ];
        }, $data['kisah_cintas'] ?? []);

        // 6. Data Gallery (Array of Galleries)
        $galleryUndangan = array_map(function ($gallery) {
            return [
                'image_path' => $gallery['image_path'] ?? null,
            ];
        }, $data['galleries'] ?? []);

        // 7. Data Komentar (Kosong untuk preview)
        $komentarUndangan = [];

        // Kamu bisa test dd() di sini untuk memastikan strukturnya sudah sesuai
        // dd('undangan =>', $undangan, 'acara =>', $acara, 'dataMempelai =>', $dataMempelai, 'templateUndanganPernikahan =>', $templateUndanganPernikahan, 'kisahCinta =>', $kisahCinta, 'galleryUndangan =>', $galleryUndangan, 'komentarUndangan =>', $komentarUndangan);

        $templateUndangan = TemplateUndangan::where('id', $undangan['template_id'])->first();
        $kategori = $templateUndangan->kategoriUndangan->name;
        $namaFile = $templateUndangan->file_name;
        

        // Mengirim data ke komponen Vue
        return Inertia::render("templateUndangan/{$kategori}/{$namaFile}", [
            'undangan' => $undangan,
            'acara' => $acara,
            'dataMempelai' => $dataMempelai,
            'templateUndanganPernikahan' => $templateUndanganPernikahan,
            'kisahCinta' => $kisahCinta,
            'galleryUndangan' => $galleryUndangan,
            'komentarUndangan' => $komentarUndangan
        ]);
    }
    //
    public function previewUser($url){
        $undangan = Undangan::with([
                'acaras',
                'dataMempelai',
                'templateUndanganPernikahan',
                'kisahCintas',
                'galleryUndangans',
                'komentarUndangans',
            ])
            ->where('url', $url)->first();
        $namaFile = $undangan->templateUndanganUser->file_name;
        $kategori = $undangan->templateUndanganUser->kategoriUndangan->name;
        // dd(['undangan: '=>$undangan, 'namaFile: '=>$namaFile, 'kategori: '=>$kategori]);
        return Inertia::render("templateUndangan/{$kategori}/{$namaFile}", [
                'undangan' => $undangan,
                'acara' => $undangan->acaras,
                'dataMempelai' => $undangan->dataMempelai,
                'templateUndanganPernikahan' => $undangan->templateUndanganPernikahan,
                'kisahCinta' => $undangan->kisahCintas,
                'galleryUndangan' => $undangan->galleryUndangans,
                'komentarUndangan' => $undangan->komentarUndangans,
        ]);
    }
}
