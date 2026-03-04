<?php

namespace App\Models\User\Undangan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\Undangan\Undangan;
use App\Models\User\Undangan\DataMempelai;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class TemplateUndanganPernikahan extends Model
{
    protected $casts = [
        'tanggal_mulai' => 'date',
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
    ];

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

    public function namaProsesi(): Attribute
    {
        return Attribute::make(
            set: fn($value) => $value ?: 'Akad Nikah',
        );
    }

    protected function waktuMulai(): Attribute
    {
        return Attribute::make(
            set: fn($value) => $value ? Carbon::parse($value)->format('H:i') : null,
        );
    }

    protected function waktuSelesai(): Attribute
    {
        return Attribute::make(
            set: fn($value) => $value ? Carbon::parse($value)->format('H:i') : null,
        );
    }

    public function undangan()
    {
        return $this->belongsTo(Undangan::class, 'undangan_id');
    }
}