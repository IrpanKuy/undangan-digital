<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TemplateUndangan extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = \App\Models\Admin\TemplateUndangan::with(['kategoriUndangan', 'undangans'])->get();
        $categories = \App\Models\Admin\KategoriUndangan::where('status', true)->get();
        $availableUndangans = \App\Models\User\Undangan\Undangan::where('for_template', true)->get();

        return Inertia::render('admin/templateUndangan', [
            'templates' => $templates,
            'categories' => $categories,
            'availableUndangans' => $availableUndangans
        ]);
    }

    /**
     * Store a newly created resource in storage (Upsert).
     */
    public function store(Request $request)
    {

// ... di dalam method store atau update ...

        $request->validate([
            'id' => 'nullable|exists:template_undangans,id',
            'undangan_id' => 'required|exists:undangans,id',
            'judul_undangan' => 'required|string|max:255',
            'kategori_undangan_id' => 'required|exists:kategori_undangans,id',
            'file_name' => 'required|string|max:255',
            // Untuk update, thumbnail dibuat nullable agar tidak wajib upload ulang
            'thumbnail_template' => $request->id ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
            'template_premium' => 'required|boolean',
            'is_active' => 'required|boolean',
            'music_url' => 'nullable|url',
        ]);

        // 1. Cari data lama jika ini proses Update
        $template = \App\Models\Admin\TemplateUndangan::find($request->id);

        // 2. Siapkan data untuk disimpan
        $data = [
            'undangan_id'          => $request->undangan_id,
            'judul_undangan'       => $request->judul_undangan,
            'kategori_undangan_id' => $request->kategori_undangan_id,
            'file_name'            => $request->file_name,
            'template_premium'     => $request->template_premium,
            'is_active'            => $request->is_active,
            'music_url'            => $request->music_url,
            'usage'                => $template ? $template->usage : 0,
        ];

        // 3. Proses Upload File jika ada file yang diunggah
        if ($request->hasFile('thumbnail_template')) {
            // Hapus foto lama jika ada (saat update)
            if ($template && $template->thumbnail_template) {
                Storage::disk('public')->delete($template->thumbnail_template);
            }

            // Simpan file baru ke folder 'templates/thumbnails' di disk 'public'
            $path = $request->file('thumbnail_template')->store('templates/thumbnails', 'public');
            
            // Masukkan path file ke dalam array data (Pastikan nama kolom di DB benar: thumbnail_template)
            $data['thumbnail_template'] = $path;
        }

        // 4. Eksekusi Update atau Create
        $save = \App\Models\Admin\TemplateUndangan::updateOrCreate(
            ['id' => $request->id],
            $data
        );
        

        return redirect()->back()->with('success', $request->id ? 'Template berhasil diperbarui.' : 'Template berhasil ditambahkan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $template = \App\Models\Admin\TemplateUndangan::findOrFail($id);
        if ($template->thumbnail_template) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($template->thumbnail_template);
        }
        $template->delete();

        return redirect()->back()->with('success', 'Template berhasil dihapus.');
    }
}
