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
        Schema::create('child_dedications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('father_id')->constrained('believers')->onDelete('cascade');
            $table->foreignId('mother_id')->constrained('believers')->onDelete('cascade');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->date('demande_date');
            $table->date('dedication_date');
            $table->string('child_lastname');
            $table->string('child_firstname');
            $table->enum('gender', ['Féminin', 'Masculin']);
            $table->date('child_birthdate');
            $table->string('child_birthplace');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_dedications');
    }
};
