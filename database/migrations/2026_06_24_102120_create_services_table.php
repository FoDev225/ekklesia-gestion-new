<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Périodes (programme des cultes)
        Schema::create('periodes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('general_theme');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_archive')->default(false);
            $table->timestamps();
        });

        // Rôles possibles dans un culte
        Schema::create('service_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // prédicateur, président, louange, annonceur...
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Cultes
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periodes')->cascadeOnDelete();
            $table->date('service_date');
            $table->string('service_theme')->nullable();
            $table->string('verset');
            $table->enum('service_type', ['commun', 'francais', 'senoufo', 'special'])->default('commun');
            $table->timestamps();
        });

        // Attributions des responsabilités par culte
        Schema::create('service_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('believer_id')->constrained('believers')->cascadeOnDelete();
            $table->foreignId('service_role_id')->constrained('service_roles')->cascadeOnDelete();
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete(); // si rôle = louange
            $table->boolean('is_backup')->default(false); // suppléant
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_assignments');
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_roles');
        Schema::dropIfExists('periodes');
    }
};