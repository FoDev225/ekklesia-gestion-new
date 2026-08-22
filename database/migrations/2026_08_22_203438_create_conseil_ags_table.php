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
        Schema::create('conseil_ags', function (Blueprint $table) {
            $table->id();
            $table->enum('ag_type', ['Ordinaire', 'Extraordinaire'])->default('Ordinaire');
            $table->date('ag_date');
            $table->time('ag_time')->nullable();
            $table->string('ag_objective', 255);
            $table->string('rapport_path', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conseil_ags');
    }
};
