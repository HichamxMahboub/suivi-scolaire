<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PerformanceHelper
{
    /**
     * Optimiser les requêtes avec eager loading
     */
    public static function optimizeQueries($query, $relations = [])
    {
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query;
    }

    /**
     * Cache les données fréquemment utilisées
     */
    public static function cacheData($key, $callback, $minutes = 60)
    {
        return Cache::remember($key, $minutes * 60, $callback);
    }

    /**
     * Nettoyer le cache spécifique
     */
    public static function clearCache($pattern = null)
    {
        if ($pattern) {
            Cache::flush();
        } else {
            Cache::flush();
        }
    }

    /**
     * Optimiser les statistiques du dashboard
     */
    public static function getDashboardStats()
    {
        return Cache::remember('dashboard_stats', 300, function () {
            return [
                'eleves' => DB::table('eleves')->count(),
                'classes' => DB::table('classes')->count(),
                'messages' => DB::table('messages')->count(),
            ];
        });
    }
} 