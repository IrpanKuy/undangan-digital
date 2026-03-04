<?php

namespace App\Models\User\Undangan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User\Undangan\Undangan;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Acara extends Model
{
    protected $casts = [
        'tanggal_acara' => 'date',
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

    public function undangan()
    {
        return $this->belongsTo(Undangan::class, 'undangan_id');
    }
}