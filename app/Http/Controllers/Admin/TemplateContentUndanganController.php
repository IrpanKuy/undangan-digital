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
        return Inertia::render('admin/templateContentUndangan/create/content');
    }

    /**
     * Show the form for creating a new resource setting.
     */
    public function createSetting()
    {
        return Inertia::render('admin/templateContentUndangan/create/setting');
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Undangan
            'judul' => 'required|string|max:255',
            'url' => 'required|string|unique:undangans,url|max:255',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'salam_pembuka' => 'required|string',
            'text_pembuka' => 'required|string',
            'video_youtube_url' => 'required|url',

            // Data Mempelai
            'nama_panggilan_pria' => 'required|string|max:255',
            'nama_lengkap_pria' => 'required|string|max:255',
            'keterangan_keluarga_pria' => 'required|string',
            'foto_pria' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nama_panggilan_wanita' => 'required|string|max:255',
            'nama_lengkap_wanita' => 'required|string|max:255',
            'keterangan_keluarga_wanita' => 'required|string',
            'foto_wanita' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'text_penutup' => 'required|string',

            // Template Undangan Pernikahan
            'tanggal_mulai_akad' => 'required|date',
            'waktu_mulai_akad' => 'required',
            'waktu_selesai_akad' => 'required',
            'detail_lokasi_akad_nikah' => 'required|string',
            'lokasi_akad_nikah' => 'required|array',
            'doa_pengantinn_pria' => 'required|string',
            'doa_pengantin_wanita' => 'required|string',
            'no_rek_amplop' => 'required|string',
            'lokasi_pengiriman_kado' => 'required|string',

            // Acara
            'acaras' => 'required|array|min:1',
            'acaras.*.nama_acara' => 'required|string',
            'acaras.*.waktu_acara' => 'required',
            'acaras.*.detail_lokasi_acara' => 'required|string',
            'acaras.*.lokasi_acara' => 'required|array',

            // Gallery
            'galleries' => 'required|array|min:1',
            'galleries.*' => 'image|mimes:jpeg,png,jpg|max:2048',

            // Kisah Cinta
            'kisah_cintas' => 'required|array|min:1',
            'kisah_cintas.*.tanggal' => 'required|date',
            'kisah_cintas.*.peristiwa' => 'required|string',
            'kisah_cintas.*.foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah digunakan.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berformat jpeg, png, atau jpg.',
            'max' => ':attribute maksimal :max KB.',
            'url' => ':attribute harus berupa URL yang valid.',
            'date' => ':attribute harus berupa tanggal yang valid.',
        ]);

        DB::beginTransaction();
        try {
            // 1. Create Undangan
            $undangan = new Undangan();
            $undangan->user_id = Auth::id() ?? 1; // Fallback to 1 for admin or testing
            $undangan->judul = $request->judul;
            $undangan->url = $request->url;
            $undangan->salam_pembuka = $request->salam_pembuka;
            $undangan->text_pembuka = $request->text_pembuka;
            $undangan->video_youtube_url = $request->video_youtube_url;
            $undangan->for_template = true;
            
            if ($request->hasFile('thumbnail')) {
                $undangan->thumbnail_path = $request->file('thumbnail')->store('thumbnails', 'public');
            }
            $undangan->save();

            // 2. Create Data Mempelai
            $mempelai = new DataMempelai();
            $mempelai->undangan_id = $undangan->id;
            $mempelai->nama_panggilan_pria = $request->nama_panggilan_pria;
            $mempelai->nama_lengkap_pria = $request->nama_lengkap_pria;
            $mempelai->keterangan_keluarga_pria = $request->keterangan_keluarga_pria;
            $mempelai->nama_panggilan_wanita = $request->nama_panggilan_wanita;
            $mempelai->nama_lengkap_wanita = $request->nama_lengkap_wanita;
            $mempelai->keterangan_keluarga_wanita = $request->keterangan_keluarga_wanita;
            $mempelai->text_penutup = $request->text_penutup;

            if ($request->hasFile('foto_pria')) {
                $mempelai->foto_pria_path = $request->file('foto_pria')->store('mempelai', 'public');
            }
            if ($request->hasFile('foto_wanita')) {
                $mempelai->foto_wanita_path = $request->file('foto_wanita')->store('mempelai', 'public');
            }
            $mempelai->save();

            // 3. Create Template Undangan Pernikahan
            $pernikahan = new TemplateUndanganPernikahan();
            $pernikahan->undangan_id = $undangan->id;
            $pernikahan->tanggal_mulai_akad = $request->tanggal_mulai_akad;
            $pernikahan->waktu_mulai_akad = $request->waktu_mulai_akad;
            $pernikahan->waktu_selesai_akad = $request->waktu_selesai_akad;
            $pernikahan->detail_lokasi_akad_nikah = $request->detail_lokasi_akad_nikah;
            
            // Convert LatLng to Point Geography
            $lat = $request->lokasi_akad_nikah['lat'];
            $lng = $request->lokasi_akad_nikah['lng'];
            $pernikahan->lokasi_akad_nikah = DB::raw("ST_GeomFromText('POINT($lng $lat)', 4326)");
            
            $pernikahan->doa_pengantinn_pria = $request->doa_pengantinn_pria;
            $pernikahan->doa_pengantin_wanita = $request->doa_pengantin_wanita;
            $pernikahan->no_rek_amplop = $request->no_rek_amplop;
            $pernikahan->lokasi_pengiriman_kado = $request->lokasi_pengiriman_kado;
            $pernikahan->save();

            // 4. Create Acaras
            foreach ($request->acaras as $acaraData) {
                $acara = new Acara();
                $acara->undangan_id = $undangan->id;
                $acara->nama_acara = $acaraData['nama_acara'];
                $acara->waktu_acara = $acaraData['waktu_acara'];
                $acara->detail_lokasi_acara = $acaraData['detail_lokasi_acara'];
                
                $alat = $acaraData['lokasi_acara']['lat'];
                $alng = $acaraData['lokasi_acara']['lng'];
                $acara->lokasi_acara = DB::raw("ST_GeomFromText('POINT($alng $alat)', 4326)");
                $acara->save();
            }

            // 5. Create Galleries
            if ($request->hasFile('galleries')) {
                foreach ($request->file('galleries') as $file) {
                    $gallery = new GalleryUndangan();
                    $gallery->undangan_id = $undangan->id;
                    $gallery->image_path = $file->store('galleries', 'public');
                    $gallery->save();
                }
            }

            // 6. Create Kisah Cintas
            foreach ($request->kisah_cintas as $index => $kisahData) {
                $kisah = new KisahCinta();
                $kisah->undangan_id = $undangan->id;
                $kisah->tanggal = $kisahData['tanggal'];
                $kisah->peristiwa = $kisahData['peristiwa'];
                
                if ($request->hasFile("kisah_cintas.$index.foto")) {
                    $kisah->foto_kisah_cinta_path = $request->file("kisah_cintas.$index.foto")->store('kisah_cinta', 'public');
                }
                $kisah->save();
            }

            // 7. Create Pengaturan Undangan (Defaults)
            $pengaturan = new PengaturanUndangan();
            $pengaturan->undangan_id = $undangan->id;
            $pengaturan->reservation_form = true;
            $pengaturan->komentar_undangan = true;
            $pengaturan->jumlah_kehadiran = true;
            $pengaturan->save();

            DB::commit();

            return redirect()->route('admin.template-content-undangan.index')
                ->with('success', 'Konten template undangan berhasil dibuat.');

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

        return Inertia::render('admin/templateContentUndangan/edit/content', [
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
    public function update(Request $request, string $id)
    {
        $undangan = Undangan::findOrFail($id);
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'url' => 'required|string|max:255|unique:undangans,url,' . $id,
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'salam_pembuka' => 'required|string',
            'text_pembuka' => 'required|string',
            'video_youtube_url' => 'required|url',

            // Data Mempelai
            'nama_panggilan_pria' => 'required|string|max:255',
            'nama_lengkap_pria' => 'required|string|max:255',
            'keterangan_keluarga_pria' => 'required|string',
            'foto_pria' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_panggilan_wanita' => 'required|string|max:255',
            'nama_lengkap_wanita' => 'required|string|max:255',
            'keterangan_keluarga_wanita' => 'required|string',
            'foto_wanita' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'text_penutup' => 'required|string',

            // Template Undangan Pernikahan
            'tanggal_mulai_akad' => 'required|date',
            'waktu_mulai_akad' => 'required',
            'waktu_selesai_akad' => 'required',
            'detail_lokasi_akad_nikah' => 'required|string',
            'lokasi_akad_nikah' => 'required|array',
            
            // Acara
            'acaras' => 'required|array|min:1',
            'acaras.*.nama_acara' => 'required|string',
            'acaras.*.waktu_acara' => 'required',
            'acaras.*.detail_lokasi_acara' => 'required|string',
            'acaras.*.lokasi_acara' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update Undangan
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

            // 2. Update/Create Data Mempelai
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

            // 3. Update Template Undangan Pernikahan
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
            $lat = $request->lokasi_akad_nikah['lat'];
            $lng = $request->lokasi_akad_nikah['lng'];
            $pernikahan->lokasi_akad_nikah = DB::raw("ST_GeomFromText('POINT($lng $lat)', 4326)");
            $pernikahan->save();

            // 4. Sync Acaras
            $undangan->acaras()->delete();
            foreach ($request->acaras as $acaraData) {
                $acara = new Acara();
                $acara->undangan_id = $undangan->id;
                $acara->nama_acara = $acaraData['nama_acara'];
                $acara->waktu_acara = $acaraData['waktu_acara'];
                $acara->detail_lokasi_acara = $acaraData['detail_lokasi_acara'];
                $alat = $acaraData['lokasi_acara']['lat'];
                $alng = $acaraData['lokasi_acara']['lng'];
                $acara->lokasi_acara = DB::raw("ST_GeomFromText('POINT($alng $alat)', 4326)");
                $acara->save();
            }

            // 5. Handling Gallery (Add new, remove selected)
            if ($request->remove_galleries) {
                $toRemove = GalleryUndangan::whereIn('id', $request->remove_galleries)->get();
                foreach ($toRemove as $item) {
                    Storage::disk('public')->delete($item->image_path);
                    $item->delete();
                }
            }
            if ($request->hasFile('galleries')) {
                foreach ($request->file('galleries') as $file) {
                    $gallery = new GalleryUndangan();
                    $gallery->undangan_id = $undangan->id;
                    $gallery->image_path = $file->store('galleries', 'public');
                    $gallery->save();
                }
            }

            // 6. Sync Kisah Cintas
            // (Simpler to delete all and recreate for sync if we don't need to preserve specific metadata)
            $existingKisah = $undangan->kisahCintas;
            foreach ($request->kisah_cintas as $index => $kisahData) {
                $kisah = null;
                if (isset($kisahData['id'])) {
                    $kisah = KisahCinta::find($kisahData['id']);
                }
                
                if (!$kisah) {
                    $kisah = new KisahCinta();
                    $kisah->undangan_id = $undangan->id;
                }
                
                $kisah->tanggal = $kisahData['tanggal'];
                $kisah->peristiwa = $kisahData['peristiwa'];
                
                if ($request->hasFile("kisah_cintas.$index.foto")) {
                    if ($kisah->foto_kisah_cinta_path) Storage::disk('public')->delete($kisah->foto_kisah_cinta_path);
                    $kisah->foto_kisah_cinta_path = $request->file("kisah_cintas.$index.foto")->store('kisah_cinta', 'public');
                }
                $kisah->save();
            }
            // Remove missing items 
            $keepIds = collect($request->kisah_cintas)->pluck('id')->filter()->toArray();
            $undangan->kisahCintas()->whereNotIn('id', $keepIds)->delete();

            DB::commit();
            return back()->with('success', 'Template berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()]);
        }
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
