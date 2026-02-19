<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class PanduanPenggunaan extends Model
{
    protected $fillable = [
        'title',
        'content',
        'is_active',
        'order_position',
        'view_count',
    ];
}