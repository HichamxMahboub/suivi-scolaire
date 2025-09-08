<?php

use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cette migration vide permet de démarrer la production
        // Les tables seront créées avec les bonnes contraintes depuis le début
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rien à faire
    }
};
