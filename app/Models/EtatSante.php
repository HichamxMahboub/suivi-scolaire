<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtatSante extends Model
{
    protected $fillable = [
        'eleve_id',
        'physique',
        'psychique',
        'remarque',
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }
}
