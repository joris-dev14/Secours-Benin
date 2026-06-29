<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regulateur extends Model
{
    protected $fillable = [
        'nom', 'prenom', 'matricule',
        'mot_de_passe', 'centre', 'commune', 'statut'
    ];

    protected $hidden = ['mot_de_passe'];

    public function signalements()
    {
        return $this->hasMany(Signalement::class);
    }
}