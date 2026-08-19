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
        Schema::create('comite_meetings', function (Blueprint $table) {
            $table->id();
            $table->enum('meeting_type', ['Ordinaire', 'Extraordinaire'])->default('Ordinaire');
            $table->date('meeting_date');
            $table->time('meeting_time')->nullable();
            $table->string('meeting_objective', 255);
            $table->string('rapport_path', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comite_meetings');
    }
};
