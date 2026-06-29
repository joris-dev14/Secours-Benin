<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = [
        'nom', 'prenom', 'matricule',
        'mot_de_passe', 'role', 'statut'
    ];

    protected $hidden = ['mot_de_passe'];
}