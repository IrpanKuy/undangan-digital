<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Undangan\Undangan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User\Undangan\ReservasiUndangan;
use App\Models\User\Undangan\KomentarUndangan;
use App\Models\User\Undangan\PengunjungUndangan;
use App\Models\User\Undangan\Kontak;

class PengaturanTambahanController extends Controller
{
    public function index($activeMenu, $undanganId)
    {
        // $undangan = Undangan::find($undanganId)->with(['kontaks', 'reservasiUndangans', 'pengunjungUndangan', 'komentarUndangans'])->first();
        // dd($undangan);
        // return Inertia::render('user/undangan/pengaturanTambahan', [
        //     'activeMenu' => $activeMenu,
        //     'undangan' => $undangan
        // ]);
        // Pastikan undangan ada (dan bisa ditambahkan validasi kepemilikan undangan di sini)
        $undangan = Undangan::findOrFail($undanganId);

        // 1. Data untuk Chart (Ringkasan)
        $chartData = [
            'rsvp' => ReservasiUndangan::where('undangan_id', $undanganId)->count(),
            'komentar' => KomentarUndangan::where('undangan_id', $undanganId)->count(),
            'pengunjung' => PengunjungUndangan::where('undangan_id', $undanganId)->count(),
            'kontak' => Kontak::where('undangan_id', $undanganId)->count(),
        ];

        // 2. Data RSVP
        $rsvpData = ReservasiUndangan::where('undangan_id', $undanganId)
            ->orderBy('created_at', 'desc')
            ->get();
        // Menghitung total orang yang hadir saja
        $totalHadir = ReservasiUndangan::where('undangan_id', $undanganId)
            ->where('status_kehadiran', 'hadir')
            ->sum('jumlah_hadir');

        // 3. Data Komentar
        $komentarData = KomentarUndangan::where('undangan_id', $undanganId)
            ->orderBy('created_at', 'desc')
            ->get();

        // 4. Data Kontak WA
        $kontakData = Kontak::where('undangan_id', $undanganId)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // dd('data kontak', $kontakData, 'total hadir', $totalHadir, 'data rsvp', $rsvpData, 'data komentar', $komentarData, 'data chart', $chartData);
        return Inertia::render('user/undangan/pengaturanTambahan', [
            'undanganId' => $undanganId,
            'chartData' => $chartData,
            'rsvpData' => $rsvpData,
            'totalHadir' => $totalHadir,
            'komentarData' => $komentarData,
            'kontakData' => $kontakData,
        ]);
    }

    public function replyKomentar(Request $request, $undanganId, $komentarId)
    {
        $request->validate([
            'balasan_pengantin' => 'required|string'
        ]);

        $komentar = KomentarUndangan::where('undangan_id', $undanganId)->findOrFail($komentarId);
        $komentar->balasan_pengantin = $request->balasan_pengantin;
        $komentar->save();

        return redirect()->back();
    }

    public function toggleLike($undanganId, $komentarId)
    {
        $komentar = KomentarUndangan::where('undangan_id', $undanganId)->findOrFail($komentarId);
        $komentar->is_liked = !$komentar->is_liked;
        $komentar->save();

        return redirect()->back();
    }

    public function storeKontak(Request $request, $undanganId)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'pesan' => 'nullable|string'
        ]);

        Kontak::create([
            'undangan_id' => $undanganId,
            'nama' => $validated['nama'],
            'no_hp' => $validated['no_hp'],
            'pesan' => $validated['pesan'] ?? '',
        ]);

        return redirect()->back();
    }

    public function updateKontak(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'pesan' => 'nullable|string'
        ]);

        $kontak = Kontak::findOrFail($id);
        $kontak->update([
            'nama' => $validated['nama'],
            'no_hp' => $validated['no_hp'],
            'pesan' => $validated['pesan'] ?? '',
        ]);

        return redirect()->back();
    }

    public function destroyKontak($id)
    {
        $kontak = Kontak::findOrFail($id);
        $kontak->delete();

        return redirect()->back();
    }

    public function markSentKontak(Request $request, $undanganId)
    {
        $request->validate([
            'kontak_ids' => 'required|array',
            'kontak_ids.*' => 'exists:kontaks,id'
        ]);

        Kontak::whereIn('id', $request->kontak_ids)
            ->where('undangan_id', $undanganId)
            ->update(['status' => true]);

        return redirect()->back();
    }
}
