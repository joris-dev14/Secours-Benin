<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    protected $fillable = [
        'nom', 'departement', 'centre_samu', 'numero_vert',
        'latitude', 'longitude', 'rayon_couverture',
        'redirection_auto', 'statut'
    ];

    protected $casts = [
        'redirection_auto' => 'boolean',
    ];

    public function hopitaux()
    {
        return $this->hasMany(Hopital::class);
    }
}