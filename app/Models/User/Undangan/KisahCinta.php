<?php

namespace App\Models\User\Undangan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\Undangan\Undangan;

class KisahCinta extends Model
{
    protected $fillable = [
        'undangan_id',
        'tanggal',
        'peristiwa',
        'foto_kisah_cinta_path',
    ];

    public function undangan()
    {
        return $this->belongsTo(Undangan::class, 'undangan_id');
    }
}