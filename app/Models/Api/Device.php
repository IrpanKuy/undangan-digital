<?php

namespace App\Models\Api;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Device extends Model {
    protected $fillable = ['user_id', 'device_token', 'connected', 'qr_data'];
    protected $casts = ['qr_data' => 'array'];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
