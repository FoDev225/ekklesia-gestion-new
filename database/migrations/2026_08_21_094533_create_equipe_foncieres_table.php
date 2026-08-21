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
        Schema::create('equipe_foncieres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('believer_id')->constrained()->cascadeOnDelete();
            $table->string('role'); // Responsable, Membre, Conseiller juridique...
            $table->string('contact')->nullable();
            $table->date('joined_at')->nullable();
            $table->date('left_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipe_foncieres');
    }
};
