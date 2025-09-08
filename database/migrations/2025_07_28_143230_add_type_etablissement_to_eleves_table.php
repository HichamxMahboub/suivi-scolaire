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
        Schema::table('eleves', function (Blueprint $table) {
            $table->enum('type_etablissement', ['primaire', 'college', 'lycee'])
                  ->nullable()
                  ->after('educateur_responsable')
                  ->comment('Type d\'établissement: primaire, collège ou lycée');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eleves', function (Blueprint $table) {
            $table->dropColumn('type_etablissement');
        });
    }
};
