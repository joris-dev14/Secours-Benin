<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    protected $fillable = [
        'alerte_id', 'ambulance_id', 'depart_a',
        'arrive_a', 'termine_a', 'statut'
    ];

    protected $casts = [
        'depart_a' => 'datetime',
        'arrive_a' => 'datetime',
        'termine_a' => 'datetime',
    ];

    public function alerte()
    {
        return $this->belongsTo(Alerte::class);
    }

    public function ambulance()
    {
        return $this->belongsTo(Ambulance::class);
    }
}