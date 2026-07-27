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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete()->nullable();
            $table->string('title');
            $table->datetime('date')->nullable();
            $table->string('theme')->nullable();
            $table->string('location')->nullable();
            $table->string('moderator')->nullable();
            $table->string('preacher')->nullable();
            $table->string('attendance_list_path')->nullable();
            $table->string('report_path')->nullable();
            $table->decimal('budget', 12, 2)->nullable();
            $table->enum('status', ['en_cours', 'realisee', 'non_realisee'])->default('en_cours');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
