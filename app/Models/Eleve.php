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
        'encadrant_id',
        'date_naissance',
        'lieu_naissance',
        'annee_entree',
        'educateur_responsable',
        'sexe',
        'email',
        'telephone',
        'telephone_parent',
        'adresse',
        'contact_urgence',
        'nom_tuteur',
        'groupe_sanguin',
        'allergies',
        'remarques_medicales',
        'medecin_traitant',
        'numero_assurance',
        'photo',
        'remarques',
        'redoublant',
        'niveau_redouble',
        'type_etablissement',
        'annee_sortie',
        'cause_sortie',
        // Nouveaux champs pour les informations détaillées
        'profession_pere',
        'profession_mere',
        'nationalite',
        'situation_familiale',
        'nombre_freres_soeurs',
        'transport_scolaire',
        'bourse_etudes',
        'date_inscription',
    ];

    public function etatSante()
    {
        return $this->hasOne(EtatSante::class);
    }

    public function classeInfo()
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    // Alias pour compatibilité (à utiliser dans les vues)
    public function classe()
    {
        return $this->classeInfo();
    }

    public function encadrant()
    {
        return $this->belongsTo(Encadrant::class, 'encadrant_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function parcoursScolaires()
    {
        return $this->hasMany(ParcoursScolaire::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Calculer la moyenne générale de l'élève pour un semestre
     */
    public function getMoyenneSemestre($semestre = null)
    {
        $query = $this->notes();

        if ($semestre) {
            $query->where('semestre', $semestre);
        }

        $notes = $query->get();

        if ($notes->count() === 0) {
            return 0;
        }

        $totalPoints = $notes->sum('note_vingt');
        return round($totalPoints / $notes->count(), 2);
    }

    /**
     * Obtenir les matières étudiées par l'élève
     */
    public function getMatieres()
    {
        return $this->notes()->distinct('matiere')->pluck('matiere');
    }

    /**
     * Constantes pour les types d'établissement
     */
    public const TYPE_PRIMAIRE = 'primaire';
    public const TYPE_COLLEGE = 'college';
    public const TYPE_LYCEE = 'lycee';

    /**
     * Obtenir tous les types d'établissement disponibles
     */
    public static function getTypesEtablissement()
    {
        return [
            self::TYPE_PRIMAIRE => 'Primaire',
            self::TYPE_COLLEGE => 'Collège',
            self::TYPE_LYCEE => 'Lycée'
        ];
    }

    /**
     * Obtenir le libellé du type d'établissement
     */
    public function getTypeEtablissementLibelleAttribute()
    {
        $types = self::getTypesEtablissement();
        return $types[$this->type_etablissement] ?? 'Non défini';
    }

    /**
     * Scope pour filtrer par type d'établissement
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type_etablissement', $type);
    }

    /**
     * Déterminer automatiquement le type d'établissement selon la classe ou le niveau
     */
    public function determineTypeEtablissement()
    {
        // Si une classe est assignée, utiliser le niveau de la classe
        if ($this->classeInfo && $this->classeInfo->niveau) {
            $niveau = strtolower($this->classeInfo->niveau);

            // Primaire : CP, CE1, CE2, CM1, CM2
            if (preg_match('/^(cp|ce[12]|cm[12])/', $niveau)) {
                return self::TYPE_PRIMAIRE;
            }

            // Collège : 6ème à 3ème
            if (preg_match('/^([6543]).*|sixieme|cinquieme|quatrieme|troisieme/', $niveau)) {
                return self::TYPE_COLLEGE;
            }

            // Lycée : 2nde, 1ère, Terminale
            if (preg_match('/^(2nd|1er|ter|seconde|premiere|terminale)/', $niveau)) {
                return self::TYPE_LYCEE;
            }
        }

        // Fallback sur le niveau scolaire de l'élève
        if ($this->niveau_scolaire) {
            $niveau = strtolower($this->niveau_scolaire);

            if (preg_match('/^(cp|ce[12]|cm[12])/', $niveau)) {
                return self::TYPE_PRIMAIRE;
            }

            if (preg_match('/^([6543]).*|sixieme|cinquieme|quatrieme|troisieme/', $niveau)) {
                return self::TYPE_COLLEGE;
            }

            if (preg_match('/^(2nd|1er|ter|seconde|premiere|terminale)/', $niveau)) {
                return self::TYPE_LYCEE;
            }
        }

        // Par défaut, retourner collège
        return self::TYPE_COLLEGE;
    }

    /**
     * Assigner automatiquement le type d'établissement si non défini
     */
    public function assignTypeEtablissement()
    {
        if (!$this->type_etablissement) {
            $this->type_etablissement = $this->determineTypeEtablissement();
            $this->save();
        }
    }
}
