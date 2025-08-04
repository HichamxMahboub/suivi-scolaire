Schema::create('dossier_medicaux', function (Blueprint $table) {
    $table->id();
    $table->foreignId('eleve_id')->constrained()->onDelete('cascade');
    $table->date('visite_date');
    $table->text('commentaire')->nullable();
    $table->timestamps();
});