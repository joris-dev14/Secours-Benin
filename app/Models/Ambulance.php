<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ambulance extends Model
{
    protected $fillable = [
    'matricule', 'modele', 'centre', 'commune',
    'statut', 'latitude', 'longitude', 'ambulancier_id'
];

    public function ambulancier()
{
    return $this->belongsTo(Ambulancier::class);
}

    public function missions()
    {
        return $this->hasMany(Mission::class);
    }
}
