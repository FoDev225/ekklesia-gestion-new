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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // ex: fr, en, dioula, senoufo
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('believer_language', function (Blueprint $table) {
            $table->foreignId('believer_id')->constrained('believers')->cascadeOnDelete();
            $table->foreignId('language_id')->constrained('languages')->cascadeOnDelete();
            $table->primary(['believer_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('believer_language');
        Schema::dropIfExists('languages');
    }
};
