<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class FeedbackUser extends Model
{
    protected $fillable = [
        'nama',
        'email',
        'pesan',
    ];
}