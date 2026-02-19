<?php

namespace App\Models\User\Undangan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\Undangan\Undangan;

class PengaturanUndangan extends Model
{
    protected $fillable = [
        'undangan_id',
        'reservation_form',
        'komentar_undangan',
        'jumlah_kehadiran',
        'music_url',
    ];

    protected $casts = [
        'reservation_form' => 'boolean',
        'komentar_undangan' => 'boolean',
        'jumlah_kehadiran' => 'boolean',
    ];

    public function undangan()
    {
        return $this->belongsTo(Undangan::class, 'undangan_id');
    }
}