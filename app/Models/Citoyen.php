<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citoyen extends Model
{
    use HasFactory;
    protected $fillable = ['telephone', 'consentement'];

    public function alertes()
    {
        return $this->hasMany(Alerte::class);
    }
}
