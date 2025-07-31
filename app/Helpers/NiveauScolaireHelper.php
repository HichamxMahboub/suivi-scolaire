<?php

namespace App\Helpers;

class NiveauScolaireHelper
{
    /**
     * Obtenir tous les niveaux scolaires
     */
    public static function getNiveaux(): array
    {
        return config('niveaux_scolaires.niveaux', []);
    }

    /**
     * Obtenir le nom d'affichage d'un niveau
     */
    public static function getNomNiveau(string $code): string
    {
        $niveaux = self::getNiveaux();
        return $niveaux[$code] ?? $code;
    }

    /**
     * Obtenir les niveaux dans l'ordre d'affichage
     */
    public static function getNiveauxOrdonnes(): array
    {
        $ordre = config('niveaux_scolaires.ordre', []);
        $niveaux = self::getNiveaux();
        
        $niveauxOrdonnes = [];
        foreach ($ordre as $code) {
            if (isset($niveaux[$code])) {
                $niveauxOrdonnes[$code] = $niveaux[$code];
            }
        }
        
        return $niveauxOrdonnes;
    }

    /**
     * Obtenir les niveaux par groupe
     */
    public static function getNiveauxParGroupe(): array
    {
        $groupes = config('niveaux_scolaires.groupes', []);
        $niveaux = self::getNiveaux();
        
        $resultat = [];
        foreach ($groupes as $nomGroupe => $codes) {
            $resultat[$nomGroupe] = [];
            foreach ($codes as $code) {
                if (isset($niveaux[$code])) {
                    $resultat[$nomGroupe][$code] = $niveaux[$code];
                }
            }
        }
        
        return $resultat;
    }

    /**
     * Obtenir le groupe d'un niveau
     */
    public static function getGroupeNiveau(string $code): ?string
    {
        $groupes = config('niveaux_scolaires.groupes', []);
        
        foreach ($groupes as $nomGroupe => $codes) {
            if (in_array($code, $codes)) {
                return $nomGroupe;
            }
        }
        
        return null;
    }

    /**
     * Vérifier si un niveau existe
     */
    public static function niveauExiste(string $code): bool
    {
        $niveaux = self::getNiveaux();
        return isset($niveaux[$code]);
    }

    /**
     * Obtenir les niveaux du primaire
     */
    public static function getNiveauxPrimaire(): array
    {
        $groupes = config('niveaux_scolaires.groupes', []);
        $niveaux = self::getNiveaux();
        
        $resultat = [];
        if (isset($groupes['primaire'])) {
            foreach ($groupes['primaire'] as $code) {
                if (isset($niveaux[$code])) {
                    $resultat[$code] = $niveaux[$code];
                }
            }
        }
        
        return $resultat;
    }

    /**
     * Obtenir les niveaux du collège
     */
    public static function getNiveauxCollege(): array
    {
        $groupes = config('niveaux_scolaires.groupes', []);
        $niveaux = self::getNiveaux();
        
        $resultat = [];
        if (isset($groupes['college'])) {
            foreach ($groupes['college'] as $code) {
                if (isset($niveaux[$code])) {
                    $resultat[$code] = $niveaux[$code];
                }
            }
        }
        
        return $resultat;
    }

    /**
     * Obtenir les niveaux du lycée (tous)
     */
    public static function getNiveauxLycee(): array
    {
        $groupes = config('niveaux_scolaires.groupes', []);
        $niveaux = self::getNiveaux();
        
        $resultat = [];
        $lyceeGroupes = ['lycee_tronc_commun', 'lycee_1ere_bac', 'lycee_2eme_bac'];
        
        foreach ($lyceeGroupes as $groupe) {
            if (isset($groupes[$groupe])) {
                foreach ($groupes[$groupe] as $code) {
                    if (isset($niveaux[$code])) {
                        $resultat[$code] = $niveaux[$code];
                    }
                }
            }
        }
        
        return $resultat;
    }

    /**
     * Obtenir les filières du baccalauréat
     */
    public static function getFiliereBac(): array
    {
        $groupes = config('niveaux_scolaires.groupes', []);
        $niveaux = self::getNiveaux();
        
        $resultat = [];
        $bacGroupes = ['lycee_1ere_bac', 'lycee_2eme_bac'];
        
        foreach ($bacGroupes as $groupe) {
            if (isset($groupes[$groupe])) {
                foreach ($groupes[$groupe] as $code) {
                    if (isset($niveaux[$code])) {
                        $resultat[$code] = $niveaux[$code];
                    }
                }
            }
        }
        
        return $resultat;
    }

    /**
     * Obtenir le cycle d'un niveau
     */
    public static function getCycleNiveau(string $code): string
    {
        if (in_array($code, config('niveaux_scolaires.groupes.primaire', []))) {
            return 'Primaire';
        } elseif (in_array($code, config('niveaux_scolaires.groupes.college', []))) {
            return 'Collège';
        } elseif (in_array($code, config('niveaux_scolaires.groupes.lycee_tronc_commun', []))) {
            return 'Lycée - Tronc Commun';
        } elseif (in_array($code, config('niveaux_scolaires.groupes.lycee_1ere_bac', []))) {
            return 'Lycée - 1ère Bac';
        } elseif (in_array($code, config('niveaux_scolaires.groupes.lycee_2eme_bac', []))) {
            return 'Lycée - 2ème Bac';
        }
        
        return 'Non défini';
    }
} 