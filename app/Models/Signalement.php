<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signalement extends Model
{
    protected $fillable = [
        'alerte_id', 'regulateur_id',
        'motif', 'statut', 'commentaire'
    ];

    public function alerte()
    {
        return $this->belongsTo(Alerte::class);
    }

    public function regulateur()
    {
        return $this->belongsTo(Regulateur::class);
    }
}