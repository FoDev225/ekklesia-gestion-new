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
        Schema::create('worship_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('leader_id')->nullable()->constrained('believers')->nullOnDelete();
            $table->timestamps();
        });

        // Pivot : fidèle <-> groupe de louange
        Schema::create('believer_worship_group', function (Blueprint $table) {
            $table->foreignId('believer_id')->constrained('believers')->cascadeOnDelete();
            $table->foreignId('worship_group_id')->constrained('worship_groups')->cascadeOnDelete();
            $table->date('joined_at')->nullable();
            $table->primary(['believer_id', 'worship_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worship_groups');
        Schema::dropIfExists('believer_worship_groups');
    }
};
