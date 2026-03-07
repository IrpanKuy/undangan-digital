<?php

namespace App\Http\Controllers;

use App\Models\User\Undangan\Undangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RoutingUndanganController extends Controller
{
   public function indexAdmin(Request $request)
    {
        $templates = Undangan::with('templateUndanganPernikahan')
            ->where('for_template', true)
            ->latest()
            ->get();

        return Inertia::render('admin/templateContentUndangan/index', [
            'templates' => $templates,
        ]);
    }
    public function indexUser()
    {
        $undangans = Undangan::with('templateUndangan')
            ->where('user_id', Auth::id())
            ->latest() // Urutkan dari yang terbaru
            ->get();

        return Inertia::render('user/undangan/undangan', [
            'undangans' => $undangans
        ]);
    }

    /**
     * Show the form for creating a new resource content.
     */
    public function createContentAdmin()
    {
        return Inertia::render('admin/templateContentUndangan/content');
    }

    /**
     * Show the  for creating a new resource setting.
     */
    public function createSettingAdmin()
    {
        return Inertia::render('admin/templateContentUndangan/setting');
    }
    
    public function createContentUser()
    {
        return Inertia::render('user/undangan/undangan/undanganForm/content');
    }

    /**
     * Show the form for creating a new resource setting.
     */
    public function createSettingUser()
    {
        return Inertia::render('user/undangan/undangan/undanganForm/setting');
    }

        public function editContent(string $id)
    {
        $template = Undangan::with([
            'templateUndanganPernikahan',
            'dataMempelai',
            'acaras',
            'galleryUndangans',
            'kisahCintas'
        ])->findOrFail($id);

        // Transform data for frontend
        if ($template->templateUndanganPernikahan) {
            $template->templateUndanganPernikahan->lokasi_akad_nikah = [
                'lat' => $template->templateUndanganPernikahan->latitude_akad,
                'lng' => $template->templateUndanganPernikahan->longitude_akad,
            ];
        }

        $template->acaras->transform(function ($acara) {
            $acara->lokasi_acara = [
                'lat' => $acara->latitude_acara,
                'lng' => $acara->longitude_acara,
            ];
            return $acara;
        });

        $roleUser = Auth::user()->role;

        if ($roleUser === 'admin') {
            return Inertia::render('admin/templateContentUndangan/content', [
                'template' => $template,
            ]);
        } 

        return Inertia::render('user/undangan/formUndangan/content', [
            'template' => $template,
        ]);
    }

    /**
     * Show the form for editing the specified resource setting.
     */
    public function editSetting(string $id)
    {
        $roleUser = Auth::user()->role;

        $template = Undangan::with('pengaturanUndangan')->findOrFail($id);

        if ($roleUser === 'admin') {
            return Inertia::render('admin/templateContentUndangan/setting', [
                'template' => $template,
            ]);
        } 

        return Inertia::render('user/undangan/formUndangan/setting', [
            'template' => $template,
        ]);
    }
}
