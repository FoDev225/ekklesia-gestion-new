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
        Schema::create('churches', function (Blueprint $table) {
            $table->id();
            // Informations d'en-tête
            $table->string('organisation');
            $table->string('organisation_name');
            $table->string('district');
            $table->string('church_name');
            $table->string('authorization');
            
            // Informations pied de page
            $table->string('address');
            $table->string('pastor_phone_number');
            $table->string('secretary_phone_number');
            $table->string('church_email')->nullable();
            $table->string('pastor_email')->nullable();
            $table->string('localisation');

            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('churches');
    }
};
