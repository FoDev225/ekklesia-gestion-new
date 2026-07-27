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
         Schema::create('new_comers', function (Blueprint $table) {
            $table->id();
            $table->string('lastname');
            $table->string('firstname');
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->enum('category', ['Passage', 'Court_sejour', 'Demeurant', 'Nouveau_converti']);
            // is_recommended non applicable aux nouveaux convertis (géré par la logique métier)
            $table->boolean('is_recommended')->nullable();
            $table->string('recommended_by')->nullable(); // nom ou ID du fidèle qui recommande
            $table->date('first_visit_date');
            $table->text('notes')->nullable();
            // Si la personne (demeurant) devient fidèle
            $table->foreignId('believer_id')->nullable()->constrained('believers')->nullOnDelete();
            $table->date('converted_to_believer_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_comers');
    }
};
