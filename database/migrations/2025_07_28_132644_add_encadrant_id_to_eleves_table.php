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
            $table->foreignId('encadrant_id')->nullable()->constrained('encadrants')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eleves', function (Blueprint $table) {
            $table->dropForeign(['encadrant_id']);
            $table->dropColumn('encadrant_id');
        });
    }
};
