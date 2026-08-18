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
        Schema::create('worship_group_rapports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worship_group_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('fichier')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worship_group_rapports');
    }
};
