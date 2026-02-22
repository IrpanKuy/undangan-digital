<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\KategoriUndangan;
use App\Models\User\Undangan\Undangan;

class TemplateUndangan extends Model
{
    protected $fillable = [
        'undangan_id',
        'thumbnail_template',
        'judul_undangan',
        'template_premium',
        'kategori_undangan_id',
        'file_name',
        'is_active',
        'music_url',
        'usage',
    ];

    protected $casts = [
        'template_premium' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function kategoriUndangan()
    {
        return $this->belongsTo(KategoriUndangan::class, 'kategori_undangan_id');
    }

    public function undangans()
    {
        return $this->hasMany(Undangan::class, 'undangan_id');
    }
}