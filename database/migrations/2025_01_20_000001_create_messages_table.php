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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('eleve_id')->nullable()->constrained('eleves')->onDelete('cascade');
            $table->string('subject');
            $table->text('content');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('type', ['general', 'academic', 'behavior', 'health', 'parent_contact', 'other'])->default('general');
            $table->enum('status', ['sent', 'delivered', 'read'])->default('sent');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            
            $table->index(['recipient_id', 'read_at']);
            $table->index(['sender_id', 'created_at']);
            $table->index(['eleve_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
}; 