<?php

namespace App\Models\User\Undangan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\Undangan\TemplateUndanganPernikahan;

class DataMempelai extends Model
{
    protected $fillable = [
        'template_undangan_pernikahan_id',
        'foto_pria_path',
        'nama_panggilan_pria',
        'nama_lengkap_pria',
        'keterangan_keluarga_pria',
        'foto_wanita_path',
        'nama_panggilan_wanita',
        'nama_lengkap_wanita',
        'keterangan_keluarga_wanita',
        'text_penutup',
    ];

    public function templateUndanganPernikahan()
    {
        return $this->belongsTo(TemplateUndanganPernikahan::class, 'template_undangan_pernikahan_id');
    }
}