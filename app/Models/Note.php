<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'matiere',
        'type_evaluation',
        'note',
        'note_sur',
        'date_evaluation',
        'semestre',
        'commentaire',
        'enseignant_id',
    ];

    protected $casts = [
        'date_evaluation' => 'date',
        'note' => 'decimal:2',
        'note_sur' => 'decimal:2',
    ];

    /**
     * Relation avec l'élève
     */
    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class);
    }

    /**
     * Relation avec l'enseignant
     */
    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'enseignant_id');
    }

    /**
     * Calculer la note sur 20
     */
    public function getNoteVingtAttribute(): float
    {
        if ($this->note_sur == 0) {
            return 0;
        }
        return round(($this->note * 20) / $this->note_sur, 2);
    }

    /**
     * Obtenir la mention selon la note
     */
    public function getMentionAttribute(): string
    {
        $noteVingt = $this->getNoteVingtAttribute();

        if ($noteVingt >= 16) {
            return 'Très Bien';
        }
        if ($noteVingt >= 14) {
            return 'Bien';
        }
        if ($noteVingt >= 12) {
            return 'Assez Bien';
        }
        if ($noteVingt >= 10) {
            return 'Passable';
        }
        return 'Insuffisant';
    }

    /**
     * Obtenir la couleur selon la note
     */
    public function getCouleurAttribute(): string
    {
        $noteVingt = $this->getNoteVingtAttribute();

        if ($noteVingt >= 16) {
            return 'text-green-600';
        }
        if ($noteVingt >= 14) {
            return 'text-blue-600';
        }
        if ($noteVingt >= 12) {
            return 'text-yellow-600';
        }
        if ($noteVingt >= 10) {
            return 'text-orange-600';
        }
        return 'text-red-600';
    }

    /**
     * Scope pour filtrer par semestre
     */
    public function scopeSemestre($query, $semestre)
    {
        return $query->where('semestre', $semestre);
    }

    /**
     * Scope pour filtrer par matière
     */
    public function scopeMatiere($query, $matiere)
    {
        return $query->where('matiere', $matiere);
    }

    /**
     * Scope pour filtrer par élève
     */
    public function scopeEleve($query, $eleveId)
    {
        return $query->where('eleve_id', $eleveId);
    }
}
