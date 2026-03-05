<?php

namespace App\Http\Controllers;

use App\Models\Admin\TemplateUndangan;
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

        $undangan = $templateUndangan->undanganPreview;
        $kategori = $templateUndangan->kategoriUndangan->name;
        return Inertia::render("templateUndangan/{$kategori}/{$templateUndangan->file_name}", [
                'undangan' => $undangan,
                'acara' => $undangan->acaras,
                'dataMempelai' => $undangan->dataMempelai,
                'templateUndanganPernikahan' => $undangan->templateUndanganPernikahan,
                'kisahCinta' => $undangan->kisahCintas,
                'galleryUndangan' => $undangan->galleryUndangans,
                'komentarUndangan' => $undangan->komentarUndangans,
        ]);
    }
    //
}
