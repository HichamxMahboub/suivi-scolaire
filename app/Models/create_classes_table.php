Schema::create('classes', function (Blueprint $table) {
    $table->id();
    $table->string('nom_classe');
    $table->string('niveau');
    $table->timestamps();
});