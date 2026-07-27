<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acteur_cultes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('believer_id')->constrained('believers')->cascadeOnDelete();
            $table->foreignId('service_role_id')->constrained('service_roles')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Un fidèle ne peut avoir qu'un seul rôle principal par type dans la table
            $table->unique(['believer_id', 'service_role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acteur_cultes');
    }
};