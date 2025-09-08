<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->string('matiere');
            $table->string('type_evaluation'); // Contrôle, Devoir, Examen, etc.
            $table->decimal('note', 5, 2); // Note sur 20
            $table->decimal('note_sur', 5, 2)->default(20); // Barème (par défaut sur 20)
            $table->date('date_evaluation');
            $table->string('semestre')->nullable(); // S1, S2
            $table->text('commentaire')->nullable();
            $table->foreignId('enseignant_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Index pour optimiser les requêtes
            $table->index(['eleve_id', 'matiere']);
            $table->index(['date_evaluation']);
            $table->index(['semestre']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
