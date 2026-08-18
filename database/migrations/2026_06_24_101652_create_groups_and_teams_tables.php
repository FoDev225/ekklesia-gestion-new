<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Groupes de louange
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
             $table->string('slug')->unique();
            $table->foreignId('leader_id')->nullable()->constrained('believers')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Équipes (prière, évangélisation, jeunesse, femmes, ecodim...)
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // ex: jeunesse, femmes, evangelisation
            $table->string('description')->nullable();
            $table->foreignId('leader_id')->nullable()->constrained('believers')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Pivot : fidèle <-> groupe de louange
        Schema::create('believer_group', function (Blueprint $table) {
            $table->foreignId('believer_id')->constrained('believers')->cascadeOnDelete();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->date('joined_at')->nullable();
            $table->primary(['believer_id', 'group_id']);
        });

        // Pivot : fidèle <-> équipe
        Schema::create('believer_team', function (Blueprint $table) {
            $table->foreignId('believer_id')->constrained('believers')->cascadeOnDelete();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->date('joined_at')->nullable();
            $table->primary(['believer_id', 'team_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('believer_team');
        Schema::dropIfExists('believer_group');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('groups');
    }
};