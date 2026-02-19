<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Admin\PoinLangganan;

class Langganan extends Model
{
    protected $fillable = [
        'nama_paket',
        'harga',
        'diskon',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'langganan_id');
    }

    public function poinLangganans()
    {
        return $this->hasMany(PoinLangganan::class, 'langganan_id');
    }
}