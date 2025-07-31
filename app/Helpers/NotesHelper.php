<?php

namespace App\Helpers;

class NotesHelper
{
    /**
     * Configuration des matières par type d'établissement
     */
    public static function getMatieresByType($typeEtablissement)
    {
        $configurations = [
            'primaire' => [
                'Arabe' => [
                    'sous_types' => ['S1', 'S2'],
                    'note_sur' => 10.0,
                    'couleur' => '#FFA726',
                ],
                'Français' => [
                    'sous_types' => ['S1', 'S2'],
                    'note_sur' => 10.0,
                    'couleur' => '#42A5F5',
                ],
                'Maths' => [
                    'sous_types' => ['S1', 'S2'],
                    'note_sur' => 10.0,
                    'couleur' => '#66BB6A',
                ],
                'EDUC ISL' => [
                    'sous_types' => ['S1', 'S2'],
                    'note_sur' => 10.0,
                    'couleur' => '#26C6DA',
                ],
                'Sportive' => [
                    'sous_types' => ['S1', 'S2'],
                    'note_sur' => 10.0,
                    'couleur' => '#EF5350',
                ],
                'SVT' => [
                    'sous_types' => ['S1', 'S2'],
                    'note_sur' => 10.0,
                    'couleur' => '#AB47BC',
                ],
                'Artistique' => [
                    'sous_types' => ['S1', 'S2'],
                    'note_sur' => 10.0,
                    'couleur' => '#29B6F6',
                ],
            ],
            'college' => [
                'Mathématiques' => [
                    'sous_types' => ['Contrôle', 'Examen'],
                    'note_sur' => 20.0,
                    'couleur' => '#66BB6A',
                ],
                'Français' => [
                    'sous_types' => ['Contrôle', 'Examen'],
                    'note_sur' => 20.0,
                    'couleur' => '#42A5F5',
                ],
                'Arabe' => [
                    'sous_types' => ['Contrôle', 'Examen'],
                    'note_sur' => 20.0,
                    'couleur' => '#FFA726',
                ],
                'Sciences' => [
                    'sous_types' => ['Contrôle', 'Examen'],
                    'note_sur' => 20.0,
                    'couleur' => '#AB47BC',
                ],
                'Histoire-Géographie' => [
                    'sous_types' => ['Contrôle', 'Examen'],
                    'note_sur' => 20.0,
                    'couleur' => '#FF7043',
                ],
                'Anglais' => [
                    'sous_types' => ['Contrôle', 'Examen'],
                    'note_sur' => 20.0,
                    'couleur' => '#26C6DA',
                ],
                'EPS' => [
                    'sous_types' => ['Pratique'],
                    'note_sur' => 20.0,
                    'couleur' => '#EF5350',
                ],
            ],
            'lycee' => [
                'Mathématiques' => [
                    'sous_types' => ['Contrôle', 'Devoir Surveillé', 'Baccalauréat Blanc'],
                    'note_sur' => 20.0,
                    'couleur' => '#66BB6A',
                ],
                'Français' => [
                    'sous_types' => ['Contrôle', 'Dissertation', 'Baccalauréat Blanc'],
                    'note_sur' => 20.0,
                    'couleur' => '#42A5F5',
                ],
                'Philosophie' => [
                    'sous_types' => ['Contrôle', 'Dissertation', 'Baccalauréat Blanc'],
                    'note_sur' => 20.0,
                    'couleur' => '#9C27B0',
                ],
                'Sciences Physiques' => [
                    'sous_types' => ['Contrôle', 'TP', 'Baccalauréat Blanc'],
                    'note_sur' => 20.0,
                    'couleur' => '#FF9800',
                ],
                'Sciences de la Vie et de la Terre' => [
                    'sous_types' => ['Contrôle', 'TP', 'Baccalauréat Blanc'],
                    'note_sur' => 20.0,
                    'couleur' => '#4CAF50',
                ],
                'Histoire-Géographie' => [
                    'sous_types' => ['Contrôle', 'Composition', 'Baccalauréat Blanc'],
                    'note_sur' => 20.0,
                    'couleur' => '#FF7043',
                ],
                'Anglais' => [
                    'sous_types' => ['Contrôle', 'Expression Orale', 'Baccalauréat Blanc'],
                    'note_sur' => 20.0,
                    'couleur' => '#26C6DA',
                ],
                'Espagnol' => [
                    'sous_types' => ['Contrôle', 'Expression Orale'],
                    'note_sur' => 20.0,
                    'couleur' => '#FFC107',
                ],
            ],
        ];

        return $configurations[$typeEtablissement] ?? [];
    }

    /**
     * Obtenir tous les semestres selon le type d'établissement
     */
    public static function getSemestresByType($typeEtablissement)
    {
        switch($typeEtablissement) {
            case 'primaire':
                return ['S1', 'S2'];
            case 'college':
            case 'lycee':
                return ['1er Semestre', '2ème Semestre'];
            default:
                return ['S1', 'S2'];
        }
    }

    /**
     * Calculer les moyennes pour un élève
     */
    public static function calculerMoyennes($eleve, $typeEtablissement, $semestre = null)
    {
        $matieres = self::getMatieresByType($typeEtablissement);
        $moyennes = [];
        
        foreach ($matieres as $matiere => $config) {
            $notes = $eleve->notes()
                          ->where('matiere', $matiere)
                          ->when($semestre, function($query) use ($semestre) {
                              return $query->where('semestre', $semestre);
                          })
                          ->get();
            
            if ($notes->count() > 0) {
                $moyenne = $notes->avg('note');
                $moyennes[$matiere] = round($moyenne, 2);
            }
        }
        
        return $moyennes;
    }

    /**
     * Obtenir l'emoji pour le type d'établissement
     */
    public static function getEmojiForType($type)
    {
        return match($type) {
            'primaire' => '🏫',
            'college' => '🏛️',
            'lycee' => '🎓',
            default => '📚'
        };
    }

    /**
     * Valider une note selon le type d'établissement
     */
    public static function validerNote($note, $matiere, $typeEtablissement)
    {
        $matieres = self::getMatieresByType($typeEtablissement);
        $config = $matieres[$matiere] ?? null;
        
        if (!$config) {
            return false;
        }
        
        return $note >= 0 && $note <= $config['note_sur'];
    }

    /**
     * Obtenir la mention selon la note
     */
    public static function getMention($moyenne)
    {
        if ($moyenne >= 16) return ['mention' => 'Très Bien', 'couleur' => 'text-green-600'];
        if ($moyenne >= 14) return ['mention' => 'Bien', 'couleur' => 'text-blue-600'];
        if ($moyenne >= 12) return ['mention' => 'Assez Bien', 'couleur' => 'text-yellow-600'];
        if ($moyenne >= 10) return ['mention' => 'Passable', 'couleur' => 'text-orange-600'];
        return ['mention' => 'Insuffisant', 'couleur' => 'text-red-600'];
    }
}
