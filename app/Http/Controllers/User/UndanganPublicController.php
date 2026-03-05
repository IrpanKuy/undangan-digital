<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Undangan\KomentarUndangan;
use App\Models\User\Undangan\ReservasiUndangan;
use Illuminate\Http\Request;

class UndanganPublicController extends Controller
{
    public function storeKomentar(Request $request, $undangan_id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'pesan' => 'required|string',
        ]);

        KomentarUndangan::create([
            'undangan_id' => $undangan_id,
            'nama' => $request->nama,
            'pesan' => $request->pesan,
        ]);

        return redirect()->back();
    }

    public function storeReservasi(Request $request, $undangan_id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah_hadir' => 'required|integer|min:1',
            'status_kehadiran' => 'required|in:Hadir,Tidak',
        ]);

        ReservasiUndangan::create([
            'undangan_id' => $undangan_id,
            'nama' => $request->nama,
            'jumlah_hadir' => $request->jumlah_hadir,
            'status_kehadiran' => $request->status_kehadiran,
        ]);

        return redirect()->back();
    }
}
