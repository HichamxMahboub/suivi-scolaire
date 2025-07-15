<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classe extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'niveau',
        'annee_scolaire',
        'description',
        'professeur_principal',
        'effectif_max',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'effectif_max' => 'integer',
    ];

    public function eleves(): HasMany
    {
        return $this->hasMany(Eleve::class);
    }

    public function getEffectifActuelAttribute(): int
    {
        return $this->eleves()->count();
    }

    public function getEffectifDisponibleAttribute(): int
    {
        return $this->effectif_max - $this->effectif_actuel;
    }

    public function getEstCompleteAttribute(): bool
    {
        return $this->effectif_actuel >= $this->effectif_max;
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByNiveau($query, $niveau)
    {
        return $query->where('niveau', $niveau);
    }

    public function scopeByAnneeScolaire($query, $annee)
    {
        return $query->where('annee_scolaire', $annee);
    }
}
