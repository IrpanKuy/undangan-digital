<?php

namespace App\Models\User\Undangan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\Undangan\Undangan;
use App\Models\User\Undangan\DataMempelai;

class TemplateUndanganPernikahan extends Model
{
    protected $fillable = [
        'undangan_id',
        'prosesi',
        'tanggal_mulai_akad',
        'waktu_mulai_akad',
        'waktu_selesai_akad',
        'detail_lokasi_akad_nikah',
        'latitude_akad_nikah',
        'longitude_akad_nikah',
        'doa_pengantinn_pria',
        'doa_pengantin_wanita',
        'kirim_angpao',
        'kirim_kado',
    ];

    public function undangan()
    {
        return $this->belongsTo(Undangan::class, 'undangan_id');
    }

    public function dataMempelai()
    {
        return $this->hasOne(DataMempelai::class, 'template_undangan_pernikahan_id');
    }
}