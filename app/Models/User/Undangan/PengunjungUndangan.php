<?php

namespace App\Models\User\Undangan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\Undangan\Undangan;

class PengunjungUndangan extends Model
{
    protected $fillable = [
        'undangan_id',
        'jumlah_pengunjung',
    ];

    public function undangan()
    {
        return $this->belongsTo(Undangan::class, 'undangan_id');
    }
}