<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class bulkShareWaController extends Controller
{
    public function bulkShareWa(Request $request)
    {
        $kontaks = $request->input('kontaks');

        if (!$kontaks || count($kontaks) === 0) {
            return back()->with('error', 'Pilih minimal satu kontak.');
        }

        // Ambil token dari .env agar aman
        $token = env('FONNTE_TOKEN');

        foreach ($kontaks as $kontak) {
            // Logika pengiriman ke Fonnte
            // Kita gunakan delay 1 detik antar request agar lebih aman
            try {
                $response = Http::withHeaders([
                    'Authorization' => $token
                ])->post('https://api.fonnte.com/send', [
                    'target' => $kontak['no_hp'],
                    'message' => $kontak['pesan'],
                'delay' => '2', 
                'countryCode' => '62',
                ]);

                dd($response);
            } catch (\Exception $e) {
                dd($e);
            }
        }

        return back()->with('success', count($kontaks) . ' Undangan sedang diproses.');
    }
}
