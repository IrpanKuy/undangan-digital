<?php

namespace App\Models\User\Undangan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\Undangan\Undangan;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Acara extends Model
{
    protected $fillable = [
        'undangan_id',
        'nama_acara',
        'tanggal_acara',
        'waktu_mulai_acara',
        'waktu_selesai_acara',
        'detail_lokasi_acara',
        'show_map',
        'latitude_acara',
        'longitude_acara',
        'zoom',
    ];
    
    protected $casts = [
        'tanggal_acara' => 'date:Y-m-d',
        'waktu_mulai_acara' => 'datetime:H:i',
        'waktu_selesai_acara' => 'datetime:H:i',
    ];

    protected function waktuMulaiAcara(): Attribute
    {
        return Attribute::make(
            set: fn($value) => $value ? Carbon::parse($value)->format('H:i') : null,
        );
    }

    protected function waktuSelesaiAcara(): Attribute
    {
        return Attribute::make(
            set: fn($value) => $value ? Carbon::parse($value)->format('H:i') : null,
        );
    }

    protected function tanggalAcaraIndo(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal_acara 
                ? Carbon::parse($this->tanggal_acara)->translatedFormat('d F, Y') 
                : null,
        );
    }

    protected $appends = ['tanggal_acara_indo'];



    public function undangan()
    {
        return $this->belongsTo(Undangan::class, 'undangan_id');
    }
}