<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('eleves', function (Blueprint $table) {
            // Vérifier et ajouter seulement les colonnes qui n'existent pas
            if (!Schema::hasColumn('eleves', 'lieu_naissance')) {
                $table->string('lieu_naissance')->nullable()->after('date_naissance');
            }
            if (!Schema::hasColumn('eleves', 'code_massar')) {
                $table->string('code_massar')->nullable()->after('numero_matricule');
            }
            if (!Schema::hasColumn('eleves', 'etablissement_actuel')) {
                $table->string('etablissement_actuel')->nullable()->after('niveau_scolaire');
            }
            if (!Schema::hasColumn('eleves', 'type_etablissement')) {
                $table->string('type_etablissement')->nullable()->after('etablissement_actuel');
            }
            if (!Schema::hasColumn('eleves', 'educateur_responsable')) {
                $table->string('educateur_responsable')->nullable()->after('annee_entree');
            }
            
            // Informations de contact
            if (!Schema::hasColumn('eleves', 'email')) {
                $table->string('email')->nullable()->after('sexe');
            }
            if (!Schema::hasColumn('eleves', 'telephone')) {
                $table->string('telephone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('eleves', 'telephone_parent')) {
                $table->string('telephone_parent')->nullable()->after('telephone');
            }
            if (!Schema::hasColumn('eleves', 'contact_urgence')) {
                $table->string('contact_urgence')->nullable()->after('adresse');
            }
            if (!Schema::hasColumn('eleves', 'nom_tuteur')) {
                $table->string('nom_tuteur')->nullable()->after('contact_urgence');
            }
            
            // Informations médicales
            if (!Schema::hasColumn('eleves', 'groupe_sanguin')) {
                $table->string('groupe_sanguin')->nullable()->after('nom_tuteur');
            }
            if (!Schema::hasColumn('eleves', 'allergies')) {
                $table->text('allergies')->nullable()->after('groupe_sanguin');
            }
            if (!Schema::hasColumn('eleves', 'remarques_medicales')) {
                $table->text('remarques_medicales')->nullable()->after('allergies');
            }
            if (!Schema::hasColumn('eleves', 'medecin_traitant')) {
                $table->string('medecin_traitant')->nullable()->after('remarques_medicales');
            }
            if (!Schema::hasColumn('eleves', 'numero_assurance')) {
                $table->string('numero_assurance')->nullable()->after('medecin_traitant');
            }
            
            // Autres champs
            if (!Schema::hasColumn('eleves', 'photo')) {
                $table->string('photo')->nullable()->after('numero_assurance');
            }
            if (!Schema::hasColumn('eleves', 'remarques')) {
                $table->text('remarques')->nullable()->after('photo');
            }
            if (!Schema::hasColumn('eleves', 'niveau_redouble')) {
                $table->string('niveau_redouble')->nullable()->after('redoublant');
            }
            
            // Relations
            if (!Schema::hasColumn('eleves', 'classe_id')) {
                $table->unsignedBigInteger('classe_id')->nullable()->after('type_etablissement');
                $table->index('classe_id');
            }
            if (!Schema::hasColumn('eleves', 'encadrant_id')) {
                $table->unsignedBigInteger('encadrant_id')->nullable()->after('classe_id');
                $table->index('encadrant_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eleves', function (Blueprint $table) {
            // Supprimer les colonnes dans l'ordre inverse
            // Supprimer les contraintes étrangères
            $table->dropForeign(['encadrant_id']);
            $table->dropForeign(['classe_id']);
            // Supprimer les colonnes
            $table->dropColumn([
                'encadrant_id',
                'classe_id',
                'niveau_redouble',
                'remarques',
                'photo',
                'numero_assurance',
                'medecin_traitant',
                'remarques_medicales',
                'allergies',
                'groupe_sanguin',
                'nom_tuteur',
                'contact_urgence',
                'telephone_parent',
                'telephone',
                'email',
                'educateur_responsable',
                'type_etablissement',
                'etablissement_actuel',
                'code_massar',
                'lieu_naissance'
            ]);
        });
    }
};
