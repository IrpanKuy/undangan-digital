<?php

namespace App\Models\User\Undangan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\Undangan\Undangan;
use App\Models\User\Undangan\DataMempelai;

class TemplateUndanganPernikahan extends Model
{
    protected $fillable = [
        'undangan_id',
        'tanggal_mulai_akad',
        'waktu_mulai_akad',
        'waktu_selesai_akad',
        'detail_lokasi_akad_nikah',
        'lokasi_akad_nikah',
        'doa_pengantinn_pria',
        'doa_pengantin_wanita',
        'no_rek_amplop',
        'lokasi_pengiriman_kado',
    ];

    public function undangan()
    {
        return $this->belongsTo(Undangan::class, 'undangan_id');
    }
}