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
        Schema::create('funeral_registers', function (Blueprint $table) {
            $table->id();
            // Information du fidèle
            $table->foreignId('believer_id')->constrained('believers')->onDelete('cascade');
            // Parent décédé
            $table->string('parent_firstname');
            $table->string('parent_lastname');
            $table->date('death_date');
            $table->string('burial_place');
            $table->string('family_relationship');
            $table->string('cause_of_death')->nullable();
            $table->date('funeral_date');
            $table->string('funeral_place');
            // Assistance de l'église
            $table->string('loincloths_number');
            $table->string('amount_paid');
            // Assistance des fidèles
            $table->string('nbre_pagne')->nullable();
            $table->string('cash_amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funeral_registers');
    }
};
