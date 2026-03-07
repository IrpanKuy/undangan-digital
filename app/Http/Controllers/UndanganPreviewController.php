<?php

namespace App\Http\Controllers;

use App\Models\Admin\TemplateUndangan;
use App\Models\User\Undangan\Undangan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UndanganPreviewController extends Controller
{
    public function preview($judul_undangan, $undangan_id){

        if($undangan_id){
            $undangan = Undangan::with([
                'acaras',
                'dataMempelai',
                'templateUndanganPernikahan',
                'kisahCintas',
                'galleryUndangans',
                'komentarUndangans',
            ])
            ->where('id', $undangan_id)
            ->first();
            $namaFile = $undangan->templateUndanganUser->file_name;
            $kategori = $undangan->templateUndanganUser->kategoriUndangan->name;
        }else{
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
        }
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
    //
}
