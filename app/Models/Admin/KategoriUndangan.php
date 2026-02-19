<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\TemplateUndangan;

class KategoriUndangan extends Model
{
    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function templateUndangans()
    {
        return $this->hasMany(TemplateUndangan::class, 'kategori_undangan_id');
    }
}