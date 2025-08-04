<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SyncPhotos extends Command
{
    protected $signature = 'photos:sync';
    protected $description = 'Synchronise les photos entre storage/app/public et public/storage';

    public function handle()
    {
        $sourceDir = storage_path('app/public');
        $targetDir = public_path('storage');

        if (!File::exists($sourceDir)) {
            $this->error("Le dossier source n'existe pas: {$sourceDir}");
            return 1;
        }

        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
            $this->info("Dossier de destination créé: {$targetDir}");
        }

        // Copier récursivement tous les fichiers
        File::copyDirectory($sourceDir, $targetDir);
        
        $this->info('Synchronisation des photos terminée avec succès!');
        return 0;
    }
}
