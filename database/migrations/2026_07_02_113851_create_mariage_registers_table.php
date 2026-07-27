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
        Schema::create('mariage_registers', function (Blueprint $table) {
            $table->id();
            // Fiancé (groom) reference
            $table->foreignId('groom_id')->nullable()->constrained('believers')->nullOnDelete('cascade');
            $table->string('groom_name')->nullable();
            $table->date('groom_birthdate')->nullable();
            $table->string('groom_birth_place')->nullable();
            $table->date('groom_bapistism_date')->nullable();
            $table->string('groom_bapistism_place')->nullable();
            $table->string('baptism_officer_groom')->nullable();
            $table->string('groom_profession')->nullable();
            $table->string('groom_photo')->nullable();
            // Fiancée (bride) reference
            $table->foreignId('bride_id')->nullable()->constrained('believers')->nullOnDelete('cascade');
            $table->string('bride_name')->nullable();
            $table->date('bride_birthdate')->nullable();
            $table->string('bride_birth_place')->nullable();
            $table->date('bride_bapistism_date')->nullable();
            $table->string('bride_bapistism_place')->nullable();
            $table->string('baptism_officer_bride')->nullable();
            $table->string('bride_profession')->nullable();
            $table->string('bride_photo')->nullable();
            // Détails mariage civil
            $table->date('civil_marriage_date');
            $table->string('civil_marriage_place');
            // Détails mariage religieux
            $table->date('religious_marriage_date');
            $table->string('religious_marriage_place');
            $table->string('wedding_mc')->nullable();
            $table->string('wedding_preacher');
            $table->string('hand_bible')->nullable();
            $table->string('officiant');
            // Témoins
            $table->string('groom_witness');
            $table->string('groom_witness_profession')->nullable();
            $table->string('bride_witness');
            $table->string('bride_witness_profession')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mariage_registers');
    }
};
