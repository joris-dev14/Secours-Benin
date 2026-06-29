<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    protected $fillable = [
        'admin_id', 'titre', 'type', 'date_debut', 'date_fin',
        'centre', 'commune', 'format', 'fichier', 'taille'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin'   => 'date',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}