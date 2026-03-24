<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Api\Device;
use App\Models\User\Undangan\Undangan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User\Undangan\ReservasiUndangan;
use App\Models\User\Undangan\KomentarUndangan;
use App\Models\User\Undangan\PengunjungUndangan;
use App\Models\User\Undangan\Kontak;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PengaturanTambahanController extends Controller
{
    public function index($activeMenu, $undanganId)
    {

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

        $userId = Auth::user()->id;
        // 5. data device
        $device = Device::where('user_id', $userId)->get();
        
        // dd('data kontak', $kontakData, 'total hadir', $totalHadir, 'data rsvp', $rsvpData, 'data komentar', $komentarData, 'data chart', $chartData);
        return Inertia::render('user/undangan/pengaturanTambahan/index', [
            'undanganId' => $undanganId,
            'chartData' => $chartData,
            'rsvpData' => $rsvpData,
            'totalHadir' => $totalHadir,
            'komentarData' => $komentarData,
            'kontakData' => $kontakData,
            'device' => $device

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

    public function storeDevice(Request $request)
    {
        $data = $request->validate([
            'no_hp' => 'required|string|max:20',
        ]);

        $name = Auth::user()->name;
        $userId = Auth::user()->id;

        $accountToken = env('FONNTE_ACCOUNT_TOKEN');

        $response = Http::withHeaders([
                'Authorization' => $accountToken
            ])->post('https://api.fonnte.com/add-device', [
                'name' => $name,
                'device' => $data['no_hp'],
            ]);

        $token = $response->json('token');
        Device::create([
            'user_id' => $userId,
            'no_hp' => $data['no_hp'],
            'device_token' => $token,
        ]);

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
