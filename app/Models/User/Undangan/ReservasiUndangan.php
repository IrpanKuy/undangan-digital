<?php

namespace App\Models\User\Undangan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\Undangan\Undangan;

class ReservasiUndangan extends Model
{
    protected $fillable = [
        'undangan_id',
        'nama',
        'jumlah_hadir',
        'status_kehadiran',
    ];

    public function undangan()
    {
        return $this->belongsTo(Undangan::class, 'undangan_id');
    }
}