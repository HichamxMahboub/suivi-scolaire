<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre à jour les niveaux dans la table classes
        DB::table('classes')->where('niveau', 'CP')->update(['niveau' => 'CP1']);
        
        // Mettre à jour les niveaux dans la table eleves
        DB::table('eleves')->where('niveau_scolaire', 'CP')->update(['niveau_scolaire' => 'CP1']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir aux anciens niveaux
        DB::table('classes')->where('niveau', 'CP1')->update(['niveau' => 'CP']);
        DB::table('eleves')->where('niveau_scolaire', 'CP1')->update(['niveau_scolaire' => 'CP']);
    }
};
