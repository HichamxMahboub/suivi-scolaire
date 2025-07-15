<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
