<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
     protected $fillable = ['telephone', 'code', 'utilise', 'expire_a'];

    protected $casts = [
        'expire_a' => 'datetime',
        'utilise' => 'boolean',
    ];
}
