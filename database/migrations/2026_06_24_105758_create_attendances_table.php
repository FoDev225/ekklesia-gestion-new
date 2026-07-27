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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendee_id')->constrained('believers')->onDelete('cascade');
            $table->string('attendee_type');
            $table->foreignId('attendable_id')->constrained('activities')->onDelete('cascade');
            $table->string('attendable_type');
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'late'])->default('present');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
