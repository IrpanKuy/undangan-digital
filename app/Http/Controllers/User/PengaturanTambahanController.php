<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Undangan\Undangan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PengaturanTambahanController extends Controller
{
    public function index($activeMenu, $undanganId)
    {
        $undangan = Undangan::find($undanganId);
        return Inertia::render('user/undangan/pengaturanTambahan', [
            'activeMenu' => $activeMenu,
            'undangan' => $undangan
        ]);
    }
}
