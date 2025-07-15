<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Eleve extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'prenom',
        'numero_matricule',
        'code_massar',
        'niveau_scolaire',
        'etablissement_actuel',
        'classe_id',
        'date_naissance',
        'annee_entree',
        'educateur_responsable',
        'sexe',
        'email',
        'telephone_parent',
        'adresse',
        'photo',
        'remarques',
        'redoublant',
        'niveau_redouble',
        'annee_sortie',
        'cause_sortie',
    ];

    public function etatSante()
    {
        return $this->hasOne(EtatSante::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function parcoursScolaires()
    {
        return $this->hasMany(ParcoursScolaire::class);
    }
}
