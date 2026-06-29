<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avertissement extends Model
{
    protected $fillable = [
        'citoyen_id',
        'admin_id',
        'message',
        'statut',
    ];

    public function citoyen()
    {
        return $this->belongsTo(Citoyen::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}