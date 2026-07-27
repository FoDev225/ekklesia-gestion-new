<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cells', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('quartier')->nullable();
            $table->string('sous_quartier')->nullable();
            $table->foreignId('leader_id')->nullable()->constrained('believers')->nullOnDelete();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Pivot : fidèle <-> cellule
        Schema::create('believer_cell', function (Blueprint $table) {
            $table->foreignId('believer_id')->constrained('believers')->cascadeOnDelete();
            $table->foreignId('cell_id')->constrained('cells')->cascadeOnDelete();
            $table->date('joined_at')->nullable();
            $table->primary(['believer_id', 'cell_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('believer_cell');
        Schema::dropIfExists('cells');
    }
};