<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

        return Inertia::render('admin/templateUndangan/index', [
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
        $request->validate([
            'id' => 'nullable|exists:template_undangans,id',
            'undangan_id' => 'required|exists:undangans,id',
            'judul_undangan' => 'required|string|max:255',
            'kategori_undangan_id' => 'required|exists:kategori_undangans,id',
            'file_name' => 'required|string|max:255',
            'thumbnail_template' => $request->id ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
            'template_premium' => 'required|boolean',
            'is_active' => 'required|boolean',
            'music_url' => 'nullable|url',
        ]);

        $template = \App\Models\Admin\TemplateUndangan::updateOrCreate(
            ['id' => $request->id],
            [
                'undangan_id' => $request->undangan_id,
                'judul_undangan' => $request->judul_undangan,
                'kategori_undangan_id' => $request->kategori_undangan_id,
                'file_name' => $request->file_name,
                'template_premium' => $request->template_premium,
                'is_active' => $request->is_active,
                'music_url' => $request->music_url,
                'usage' => $request->id ? \App\Models\Admin\TemplateUndangan::find($request->id)->usage : 0,
            ]
        );

        if ($request->hasFile('thumbnail_template')) {
            if ($template->thumbnail_template) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($template->thumbnail_template);
            }
            $path = $request->file('thumbnail_template')->store('template_thumbnails', 'public');
            $template->update(['thumbnail_template' => $path]);
        }

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
