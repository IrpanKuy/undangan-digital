<?php

namespace App\Models\User\Undangan;
use Illuminate\Database\Eloquent\Model;
    
class DataMempelai extends Model
{
    protected $fillable = [
        'undangan_id',
        'foto_pria_path',
        'nama_panggilan_pria',
        'nama_lengkap_pria',
        'instagram_url_pria',
        'tiktok_url_pria',
        'x_url_pria',
        'keterangan_keluarga_pria',
        'foto_wanita_path',
        'nama_panggilan_wanita',
        'nama_lengkap_wanita',
        'instagram_url_wanita',
        'tiktok_url_wanita',
        'x_url_wanita',
        'keterangan_keluarga_wanita',
        'text_penutup',
    ];

    public function undangan()
    {
        return $this->belongsTo(Undangan::class, 'undangan_id');
    }
}