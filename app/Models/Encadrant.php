<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Encadrant extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'remarque',
        'photo',
        'numero',
        'matricule',
        'adresse',
    ];

    /**
     * Relation avec les élèves
     */
    public function eleves(): HasMany
    {
        return $this->hasMany(Eleve::class, 'encadrant_id');
    }
    /**
     * Classes encadrées par cet encadrant
     */
    public function classes(): HasMany
    {
        return $this->hasMany(Classe::class);
    }
}
