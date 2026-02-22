<?php

namespace App\Models\User\Undangan;

use App\Models\Admin\TemplateUndangan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

// Import model relasi

class Undangan extends Model
{
    protected $fillable = [
        'user_id',
        'judul',
        'url',
        'thumbnail_path',
        'salam_pembuka',
        'text_pembuka',
        'video_youtube_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function templateUndanganPernikahan()
    {
        return $this->hasOne(TemplateUndanganPernikahan::class, 'undangan_id');
    }

    public function acaras()
    {
        return $this->hasMany(Acara::class, 'undangan_id');
    }

    public function galleryUndangans()
    {
        return $this->hasMany(GalleryUndangan::class, 'undangan_id');
    }

    public function kisahCintas()
    {
        return $this->hasMany(KisahCinta::class, 'undangan_id');
    }

    public function komentarUndangans()
    {
        return $this->hasMany(KomentarUndangan::class, 'undangan_id');
    }

    public function reservasiUndangans()
    {
        return $this->hasMany(ReservasiUndangan::class, 'undangan_id');
    }

    public function pengunjungUndangan()
    {
        return $this->hasOne(PengunjungUndangan::class, 'undangan_id');
    }

    public function idPengunjungUniks()
    {
        return $this->hasMany(IdPengunjungUnik::class, 'undangan_id');
    }

    public function pengaturanUndangan()
    {
        return $this->hasOne(PengaturanUndangan::class, 'undangan_id');
    }

    public function kontaks()
    {
        return $this->hasMany(Kontak::class, 'undangan_id');
    }

    public function templateUndangan()
    {
        return $this->belongsTo(TemplateUndangan::class, 'template_undangan_id');
    }

    public function dataMempelai()
    {
        return $this->hasOne(DataMempelai::class, 'undangan_id');
    }
}