<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('eleves', function (Blueprint $table) {
            // Champs à conserver
            $table->bigIncrements('id');
            $table->string('nom');
            $table->string('prenom');
            $table->string('numero_matricule')->unique();
            $table->string('niveau_scolaire');
            $table->date('date_naissance')->nullable();
            $table->integer('annee_entree');
            $table->char('sexe', 1)->nullable();
            $table->string('adresse')->nullable();
            $table->boolean('redoublant')->nullable();
            $table->json('niveaux_redoubles')->nullable();
            $table->integer('annee_sortie')->nullable();
            $table->string('cause_sortie')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eleves');
    }
}; 