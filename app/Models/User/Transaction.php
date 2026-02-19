<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_pembayaran',
        'tipe_pembayaran',
        'expired_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}