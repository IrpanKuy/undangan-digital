<?php

namespace App\Models\User\Undangan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\Undangan\Undangan;
use App\Models\User\Undangan\DataMempelai;

class TemplateUndanganPernikahan extends Model
{
    protected $fillable = [
        'undangan_id',
        'nama_prosesi',
        'tanggal_mulai',
        'waktu_mulai',
        'waktu_selesai',
        'detail_lokasi_nikah',
        'show_map',
        'zoom',
        'latitude',
        'longitude',    
        'doa_pengantin_pria',
        'doa_pengantin_wanita',
        'no_rek_amplop',
        'lokasi_pengiriman_kado',
    ];

    public function undangan()
    {
        return $this->belongsTo(Undangan::class, 'undangan_id');
    }
}