<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citoyen extends Model
{
    protected $fillable = ['telephone', 'consentement'];

    public function alertes()
    {
        return $this->hasMany(Alerte::class);
    }
}
