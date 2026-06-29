<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ambulancier extends Model
{
    protected $fillable = [
        'nom', 'prenom', 'matricule',
        'mot_de_passe', 'ambulance_id', 'centre', 'statut'
    ];

    protected $hidden = ['mot_de_passe'];

    public function ambulance()
    {
        return $this->belongsTo(Ambulance::class);
    }
    public function missions()
    {
        return $this->hasManyThrough(Mission::class, Ambulance::class);
    }
}