<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('believer_id')->constrained('believers')->cascadeOnDelete();
            $table->enum('type', ['depart', 'deces']);          // quitter la communauté ou décès
            $table->date('departure_date');
            $table->string('destination')->nullable();           // ville/église d'accueil si départ
            $table->text('reason')->nullable();                  // motif
            $table->string('recorded_by')->nullable();           // qui a enregistré
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departures');
    }
};