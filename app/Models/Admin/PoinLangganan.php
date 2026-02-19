<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Langganan;

class PoinLangganan extends Model
{
    protected $fillable = [
        'langganan_id',
        'judul',
        'is_checklis',
    ];

    protected $casts = [
        'is_checklis' => 'boolean',
    ];

    public function langganan()
    {
        return $this->belongsTo(Langganan::class, 'langganan_id');
    }
}