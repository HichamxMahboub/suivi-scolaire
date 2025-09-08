<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcoursScolaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'annee_scolaire',
        'etablissement',
        'niveau',
        'resultat',
        'moyenne',
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }
}
