Schema::create('eleves', function (Blueprint $table) {
    $table->id();
    $table->string('nom');
    $table->string('prenom');
    $table->date('date_naissance')->nullable();
    $table->foreignId('classe_id')->constrained()->onDelete('cascade');
    $table->timestamps();
});