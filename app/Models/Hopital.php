<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hopital extends Model
{
    protected $table = 'hopitaux';
    protected $fillable = ['commune_id', 'nom'];

    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }
}