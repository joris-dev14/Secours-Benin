<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerte extends Model
{
    protected $fillable = [
        'citoyen_id', 'commune', 'latitude',
        'longitude', 'photo', 'description', 'statut'
    ];

    public function citoyen()
    {
        return $this->belongsTo(Citoyen::class);
    }

    public function mission()
    {
        return $this->hasOne(Mission::class);
    }

    public function signalements()
    {
        return $this->hasMany(Signalement::class);
    }
}