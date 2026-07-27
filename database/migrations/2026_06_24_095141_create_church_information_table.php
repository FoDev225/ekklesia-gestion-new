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
        Schema::create('church_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('believer_id')->constrained('believers')->cascadeOnDelete();
            $table->string('connaissance_eglise')->nullable(); // comment a-t-il connu l'église
            $table->string('original_church')->nullable();     // église d'origine
            $table->unsignedSmallInteger('arrival_year')->nullable();
            $table->date('conversion_date')->nullable();
            $table->string('conversion_place')->nullable();
            $table->boolean('baptised')->default(false);
            $table->date('baptism_date')->nullable();
            $table->string('baptism_place')->nullable();
            $table->string('baptism_pastor')->nullable();
            $table->string('baptism_card_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('church_information');
    }
};
