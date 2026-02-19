<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Import model relasi
use App\Models\Admin\Langganan;
use App\Models\User\Transaction;
use App\Models\User\Undangan\Undangan;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi massal.
     * Berdasarkan ERD revisi.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'subscription',
        'no_hp',
        'langganan_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi ke paket langganan (Admin).
     */
    public function langganan()
    {
        return $this->belongsTo(Langganan::class, 'langganan_id');
    }

    /**
     * Relasi ke transaksi (User).
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    /**
     * Relasi ke daftar undangan yang dibuat user.
     */
    public function undangans()
    {
        return $this->hasMany(Undangan::class, 'user_id');
    }
}