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
            'undangans',
            'undangans.acaras',
            'undangans.dataMempelai',
            'undangans.templateUndanganPernikahan',
            'undangans.kisahCintas',
            'undangans.galleryUndangans',
        ])
        ->where('judul_undangan', $judul_undangan)
        ->first();

        $undangan = $templateUndangan->undangans;
        $kategori = $templateUndangan->kategoriUndangan->name;
        return Inertia::render("templateUndangan/{$kategori}/{$templateUndangan->file_name}", [
                'undangan' => $undangan,
                'acara' => $undangan->acaras,
                'dataMempelai' => $undangan->dataMempelai,
                'templateUndanganPernikahan' => $undangan->templateUndanganPernikahan,
                'kisahCinta' => $undangan->kisahCintas,
                'galleryUndangan' => $undangan->galleryUndangans,

        ]);
    }
    //
}
