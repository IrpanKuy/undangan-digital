<?php

namespace App\Http\Middleware;

use App\Models\User\Undangan\IdPengunjungUnik;
use App\Models\User\Undangan\PengunjungUndangan;
use App\Models\User\Undangan\Undangan;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackInvitationVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ambil slug 'url' dari parameter route
        $url = $request->route('url');

        if ($url) {
            $undangan = Undangan::where('url', $url)->first();

            if ($undangan) {
                // --- TRACK TOTAL HITS (PENGUNJUNG BIASA) ---
                // Menggunakan increment agar aman dari bentrokan data (race condition)
                $stats = PengunjungUndangan::firstOrCreate(
                    ['undangan_id' => $undangan->id],
                    ['jumlah_pengunjung' => 0]
                );
                $stats->increment('jumlah_pengunjung');

                // --- TRACK PENGUNJUNG UNIK ---
                $cookieName = 'u_v_tkn_' . $undangan->id; // Unique Visitor Token
                
                if (!$request->hasCookie($cookieName)) {
                    $visitorToken = (string) Str::uuid();
                    // Simpan ke database pengunjung unik
                    IdPengunjungUnik::create([
                        'undangan_id' => $undangan->id,
                        'cookie_id'   => $visitorToken
                    ]);

                    // Titipkan cookie untuk dikirim ke browser (aktif selama 5 tahun)
                    Cookie::queue($cookieName, $visitorToken, 525600 * 5);
                }
            }
        }

        return $next($request);
    }
}
