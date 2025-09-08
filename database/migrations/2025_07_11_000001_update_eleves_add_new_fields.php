<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('eleves', function (Blueprint $table) {
            if (!Schema::hasColumn('eleves', 'numero_matricule')) {
                $table->string('numero_matricule')->unique()->after('prenom');
            }
            if (!Schema::hasColumn('eleves', 'classe')) {
                $table->string('classe')->nullable()->after('niveau_scolaire');
            }
            if (!Schema::hasColumn('eleves', 'educateur_responsable')) {
                $table->string('educateur_responsable')->nullable()->after('annee_entree');
            }
            if (!Schema::hasColumn('eleves', 'sexe')) {
                $table->char('sexe', 1)->nullable()->after('educateur_responsable');
            }
            if (!Schema::hasColumn('eleves', 'email')) {
                $table->string('email')->nullable()->after('sexe');
            }
            if (!Schema::hasColumn('eleves', 'telephone_parent')) {
                $table->string('telephone_parent')->nullable()->after('email');
            }
            if (!Schema::hasColumn('eleves', 'photo')) {
                $table->string('photo')->nullable()->after('adresse');
            }
            if (!Schema::hasColumn('eleves', 'remarques')) {
                $table->text('remarques')->nullable()->after('photo');
            }
            if (!Schema::hasColumn('eleves', 'redoublant')) {
                $table->boolean('redoublant')->nullable()->after('remarques');
            }
            if (!Schema::hasColumn('eleves', 'niveau_redouble')) {
                $table->string('niveau_redouble')->nullable()->after('redoublant');
            }
            if (!Schema::hasColumn('eleves', 'annee_sortie')) {
                $table->integer('annee_sortie')->nullable()->after('niveau_redouble');
            }
            if (!Schema::hasColumn('eleves', 'cause_sortie')) {
                $table->string('cause_sortie')->nullable()->after('annee_sortie');
            }
            if (!Schema::hasColumn('eleves', 'etablissement_actuel')) {
                $table->string('etablissement_actuel')->nullable()->after('niveau_scolaire');
            }
        });
    }

    public function down()
    {
        Schema::table('eleves', function (Blueprint $table) {
            $table->dropColumn([
                'numero_matricule', 'classe', 'educateur_responsable', 'sexe', 'email', 'telephone_parent',
                'photo', 'remarques', 'redoublant', 'niveau_redouble', 'annee_sortie', 'cause_sortie'
            ]);
        });
    }
};
