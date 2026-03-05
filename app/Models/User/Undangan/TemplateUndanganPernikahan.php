<?php

namespace App\Models\User\Undangan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\Undangan\Undangan;
use App\Models\User\Undangan\DataMempelai;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

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
    protected $casts = [
        'tanggal_mulai' => 'date:Y-m-d',
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
    ];

    protected function tanggalMulaiIndo(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal_mulai 
                ? Carbon::parse($this->tanggal_mulai)->translatedFormat('d F, Y') 
                : null,
        );
    }

    protected $appends = ['tanggal_mulai_indo'];
    


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