<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Device extends Model {
    protected $fillable = ['name', 'device_token', 'status', 'qr_data'];
    protected $casts = ['qr_data' => 'array'];
}
