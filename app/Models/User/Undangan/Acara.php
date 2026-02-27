<?php

namespace App\Models\User\Undangan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\Undangan\Undangan;

class Acara extends Model
{
    protected $fillable = [
        'undangan_id',
        'nama_acara',
        'tanggal_acara',
        'waktu_acara',
        'detail_lokasi_acara',
        'lokasi_acara',
    ];

    public function undangan()
    {
        return $this->belongsTo(Undangan::class, 'undangan_id');
    }
}