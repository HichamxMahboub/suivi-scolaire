<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Classe;

class ListClasses extends Command
{
    protected $signature = 'classes:list';
    protected $description = 'Lister toutes les classes existantes';

    public function handle()
    {
        $this->info('Classes existantes dans la base de données:');
        $this->info('==========================================');

        $classes = Classe::orderBy('nom')->get();

        if ($classes->isEmpty()) {
            $this->warn('Aucune classe trouvée dans la base de données.');
            return 0;
        }

        foreach ($classes as $classe) {
            $this->line("- {$classe->nom} (ID: {$classe->id})");
        }

        $this->info("\nTotal: " . $classes->count() . " classe(s)");

        return 0;
    }
}
