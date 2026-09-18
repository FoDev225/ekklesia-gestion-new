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
        Schema::create('finance_categories', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['recette', 'depense']);
            $table->string('groupe')->nullable(); // Fonctionnement, Activités de l'église, Activités des groupes, Épargne et autres
            $table->string('sous_groupe')->nullable(); // Sous-groupe spécifique à chaque groupe
            $table->string('name');
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_statutory')->default(false); // Part AEBECI / Part Vision
            $table->decimal('statutory_rate', 5, 2)->nullable(); // ex: 10.00, 40.00
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_categories');
    }
};
