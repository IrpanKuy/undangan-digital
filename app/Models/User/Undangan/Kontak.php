<?php

namespace App\Models\User\Undangan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\Undangan\Undangan;

class Kontak extends Model
{
    protected $fillable = [
        'undangan_id',
        'nama',
        'no_hp',
        'pesan',
    ];

    public function undangan()
    {
        return $this->belongsTo(Undangan::class, 'undangan_id');
    }
}