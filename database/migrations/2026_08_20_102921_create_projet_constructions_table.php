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
        Schema::create('projet_constructions', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->date('date_lancement');
            $table->date('date_fin')->nullable();
            $table->decimal('cout', 14, 2)->nullable();
            $table->enum('status', ['en_cours', 'realise'])->default('en_cours');
            $table->string('rapport_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projet_constructions');
    }
};
