<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User\Undangan\Undangan;
use App\Models\User\Undangan\DataMempelai;
use App\Models\User\Undangan\TemplateUndanganPernikahan;
use App\Models\User\Undangan\Acara;
use App\Models\User\Undangan\GalleryUndangan;
use App\Models\User\Undangan\KisahCinta;
use App\Models\User\Undangan\PengaturanUndangan;
use App\Http\Requests\Admin\TemplateContentUndangan\StoreRequest;
use App\Http\Requests\Admin\TemplateContentUndangan\UpdateRequest;
use App\Rules\templateContentUndanganValidate;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TemplateContentUndanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $templates = Undangan::with('templateUndanganPernikahan')
            ->where('for_template', true)
            ->latest()
            ->get();

        return Inertia::render('admin/templateContentUndangan/index', [
            'templates' => $templates,
        ]);
    }

    /**
     * Show the form for creating a new resource content.
     */
    public function createContent()
    {
        return Inertia::render('admin/templateContentUndangan/content');
    }

    /**
     * Show the form for creating a new resource setting.
     */
    public function createSetting()
    {
        return Inertia::render('admin/templateContentUndangan/setting');
    }

    /**
     * Check if URL is available.
     */
    public function checkUrl(Request $request)
    {
        $request->validate(['url' => 'required|string']);
        
        $exists = Undangan::where('url', $request->url)->exists();
        
        return response()->json([
            'available' => !$exists
        ]);
    }

    /**
     * Store or Update resource in storage (Upsert).
     */
    public function store(Request $request)
    {
        $request->validate(
            templateContentUndanganValidate::rules($request->id),
            templateContentUndanganValidate::messages(),
            templateContentUndanganValidate::attributes()
        );

        DB::beginTransaction();
        try {
            // 1. Upsert Undangan
            $undangan = null;
            if ($request->id) {
                $undangan = Undangan::findOrFail($request->id);
            } else {
                $undangan = new Undangan();
                $undangan->user_id = Auth::id() ?? 1;
                $undangan->for_template = true;
            }

            $undangan->judul = $request->judul;
            $undangan->url = $request->url;
            $undangan->salam_pembuka = $request->salam_pembuka;
            $undangan->text_pembuka = $request->text_pembuka;
            $undangan->video_youtube_url = $request->video_youtube_url;
            
            if ($request->hasFile('thumbnail')) {
                if ($undangan->thumbnail_path) Storage::disk('public')->delete($undangan->thumbnail_path);
                $undangan->thumbnail_path = $request->file('thumbnail')->store('thumbnails', 'public');
            }
            $undangan->save();

            // 2. Upsert Data Mempelai
            $mempelai = DataMempelai::updateOrCreate(
                ['undangan_id' => $undangan->id],
                [
                    'nama_panggilan_pria' => $request->nama_panggilan_pria,
                    'nama_lengkap_pria' => $request->nama_lengkap_pria,
                    'keterangan_keluarga_pria' => $request->keterangan_keluarga_pria,
                    'nama_panggilan_wanita' => $request->nama_panggilan_wanita,
                    'nama_lengkap_wanita' => $request->nama_lengkap_wanita,
                    'keterangan_keluarga_wanita' => $request->keterangan_keluarga_wanita,
                    'text_penutup' => $request->text_penutup,
                ]
            );

            if ($request->hasFile('foto_pria')) {
                if ($mempelai->foto_pria_path) Storage::disk('public')->delete($mempelai->foto_pria_path);
                $mempelai->foto_pria_path = $request->file('foto_pria')->store('mempelai', 'public');
            }
            if ($request->hasFile('foto_wanita')) {
                if ($mempelai->foto_wanita_path) Storage::disk('public')->delete($mempelai->foto_wanita_path);
                $mempelai->foto_wanita_path = $request->file('foto_wanita')->store('mempelai', 'public');
            }
            $mempelai->save();

            // 3. Upsert Template Undangan Pernikahan
            $pernikahan = TemplateUndanganPernikahan::updateOrCreate(
                ['undangan_id' => $undangan->id],
                [
                    'tanggal_mulai_akad' => $request->tanggal_mulai_akad,
                    'waktu_mulai_akad' => $request->waktu_mulai_akad,
                    'waktu_selesai_akad' => $request->waktu_selesai_akad,
                    'detail_lokasi_akad_nikah' => $request->detail_lokasi_akad_nikah,
                    'doa_pengantinn_pria' => $request->doa_pengantinn_pria,
                    'doa_pengantin_wanita' => $request->doa_pengantin_wanita,
                    'no_rek_amplop' => $request->no_rek_amplop,
                    'lokasi_pengiriman_kado' => $request->lokasi_pengiriman_kado,
                ]
            );
            
            if (isset($request->lokasi_akad_nikah['lat']) && isset($request->lokasi_akad_nikah['lng'])) {
                $lat = $request->lokasi_akad_nikah['lat'];
                $lng = $request->lokasi_akad_nikah['lng'];
                $pernikahan->lokasi_akad_nikah = DB::raw("ST_GeomFromText('POINT($lng $lat)', 4326)");
                $pernikahan->save();
            }

            // 4. Sync Acaras (Delete missing, Update/Create existing)
            $existingAcaraIds = collect($request->acaras)->pluck('id')->filter()->toArray();
            $undangan->acaras()->whereNotIn('id', $existingAcaraIds)->delete();

            foreach ($request->acaras as $acaraData) {
                $acara = isset($acaraData['id']) ? Acara::find($acaraData['id']) : new Acara();
                if (!$acara) $acara = new Acara();

                $acara->undangan_id = $undangan->id;
                $acara->nama_acara = $acaraData['nama_acara'];
                $acara->waktu_acara = $acaraData['waktu_acara'];
                $acara->detail_lokasi_acara = $acaraData['detail_lokasi_acara'];
                
                if (isset($acaraData['lokasi_acara']['lat']) && isset($acaraData['lokasi_acara']['lng'])) {
                    $alat = $acaraData['lokasi_acara']['lat'];
                    $alng = $acaraData['lokasi_acara']['lng'];
                    $acara->lokasi_acara = DB::raw("ST_GeomFromText('POINT($alng $alat)', 4326)");
                }
                $acara->save();
            }

            // 5. Upsert Galleries (Add new, Remove specified)
            if ($request->remove_galleries) {
                $toRemove = GalleryUndangan::whereIn('id', $request->remove_galleries)->get();
                /** @var GalleryUndangan $item */
                foreach ($toRemove as $item) {
                    if ($item->image_path) Storage::disk('public')->delete($item->image_path);
                    $item->delete();
                }
            }
            if ($request->galleries) {
                foreach ($request->galleries as $index => $galleryData) {
                    if ($request->hasFile("galleries.$index.file")) {
                        $gallery = new GalleryUndangan();
                        $gallery->undangan_id = $undangan->id;
                        $gallery->image_path = $request->file("galleries.$index.file")->store('galleries', 'public');
                        $gallery->save();
                    }
                }
            }

            // 6. Sync Kisah Cintas
            $keepKisahIds = collect($request->kisah_cintas)->pluck('id')->filter()->toArray();
            $undangan->kisahCintas()->whereNotIn('id', $keepKisahIds)->delete();

            foreach ($request->kisah_cintas as $index => $kisahData) {
                $kisah = isset($kisahData['id']) ? KisahCinta::find($kisahData['id']) : new KisahCinta();
                if (!$kisah) $kisah = new KisahCinta();

                $kisah->undangan_id = $undangan->id;
                $kisah->tanggal = $kisahData['tanggal'];
                $kisah->peristiwa = $kisahData['peristiwa'];
                
                if ($request->hasFile("kisah_cintas.$index.foto")) {
                    if ($kisah->foto_kisah_cinta_path) Storage::disk('public')->delete($kisah->foto_kisah_cinta_path);
                    $kisah->foto_kisah_cinta_path = $request->file("kisah_cintas.$index.foto")->store('kisah_cinta', 'public');
                }
                $kisah->save();
            }

            // 7. Upsert Pengaturan Undangan (Defaults if new)
            if (!$request->id) {
                PengaturanUndangan::updateOrCreate(
                    ['undangan_id' => $undangan->id],
                    [
                        'reservation_form' => true,
                        'komentar_undangan' => true,
                        'jumlah_kehadiran' => true,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.template-content-undangan.index')
                ->with('success', $request->id ? 'Template berhasil diperbarui.' : 'Konten template undangan berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource content.
     */
    public function editContent(string $id)
    {
        $template = Undangan::with([
            'templateUndanganPernikahan',
            'dataMempelai',
            'acaras',
            'galleryUndangans',
            'kisahCintas'
        ])->findOrFail($id);

        return Inertia::render('admin/templateContentUndangan/content', [
            'template' => $template,
        ]);
    }

    /**
     * Show the form for editing the specified resource setting.
     */
    public function editSetting(string $id)
    {
        $template = Undangan::with('pengaturanUndangan')->findOrFail($id);

        return Inertia::render('admin/templateContentUndangan/edit/setting', [
            'template' => $template,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $request->merge(['id' => $id]);
        return $this->store($request);
    }

    /**
     * Update specified resource settings.
     */
    public function updateSetting(Request $request, string $id)
    {
        $request->validate([
            'reservation_form' => 'required|boolean',
            'komentar_undangan' => 'required|boolean',
            'jumlah_kehadiran' => 'required|boolean',
            'music_url' => 'nullable|string',
        ]);

        $pengaturan = PengaturanUndangan::updateOrCreate(
            ['undangan_id' => $id],
            $request->only(['reservation_form', 'komentar_undangan', 'jumlah_kehadiran', 'music_url'])
        );

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    /**
     * Toggle status undangan.
     */
    public function toggleStatus(string $id)
    {
        $undangan = Undangan::findOrFail($id);
        $undangan->status = $undangan->status === 'aktif' ? 'non-aktif' : 'aktif';
        $undangan->save();

        return back()->with('success', 'Status berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $undangan = Undangan::findOrFail($id);
        
        // Clean up files
        if ($undangan->thumbnail_path) Storage::disk('public')->delete($undangan->thumbnail_path);
        // ... clean up other related files
        
        $undangan->delete();

        return back()->with('success', 'Template berhasil dihapus.');
    }
}
