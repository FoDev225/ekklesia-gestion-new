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
        Schema::create('finance_periods', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['S1', 'S2']); // S1 = jan-juin, S2 = juil-déc
            $table->unsignedSmallInteger('annee');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->date('date_ag_prevue')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->timestamps();

            $table->unique(['type', 'annee']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_periods');
    }
};
