<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class OptimizeSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:optimize {--force : Force optimization}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize system performance by clearing caches and optimizing database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Optimisation du système en cours...');

        // Nettoyer tous les caches
        $this->info('🧹 Nettoyage des caches...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        // Optimiser l'application
        $this->info('⚡ Optimisation de l\'application...');
        Artisan::call('optimize');

        // Nettoyer le cache de la base de données
        $this->info('🗄️ Optimisation de la base de données...');
        Cache::flush();

        // Vérifier les performances
        $this->info('📊 Vérification des performances...');
        $this->checkPerformance();

        $this->info('✅ Optimisation terminée avec succès !');
        $this->info('🎯 Le système est maintenant optimisé pour de meilleures performances.');
    }

    private function checkPerformance()
    {
        // Vérifier le temps de réponse des requêtes principales
        $start = microtime(true);

        // Test dashboard
        DB::table('eleves')->count();
        DB::table('classes')->count();
        DB::table('messages')->count();
        DB::table('notes')->count();

        $end = microtime(true);
        $time = round(($end - $start) * 1000, 2);

        if ($time < 100) {
            $this->info("✅ Temps de réponse excellent: {$time}ms");
        } elseif ($time < 500) {
            $this->info("⚠️ Temps de réponse acceptable: {$time}ms");
        } else {
            $this->warn("❌ Temps de réponse lent: {$time}ms");
        }
    }
}
