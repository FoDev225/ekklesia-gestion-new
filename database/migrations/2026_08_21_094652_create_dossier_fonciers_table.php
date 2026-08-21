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
        Schema::create('dossier_fonciers', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('localisation')->nullable();
            $table->decimal('superficie', 12, 2)->nullable(); // en m²
            $table->enum('statut', ['recherche', 'negociation', 'acquis', 'titre_obtenu', 'abandonne'])->default('recherche');
            $table->decimal('cout', 14, 2)->nullable();
            $table->date('date_debut');
            $table->date('date_acquisition')->nullable();
            $table->text('notes')->nullable();
            $table->string('document_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossier_fonciers');
    }
};
