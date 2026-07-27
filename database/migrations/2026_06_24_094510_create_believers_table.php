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
        Schema::create('believers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')->nullable()->constrained('families')->nullOnDelete();
            $table->string('register_number')->nullable()->unique();
            $table->string('lastname');
            $table->string('firstname');
            $table->string('cni_number')->nullable()->unique();
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('nationality')->default('Ivoirienne');
            $table->enum('gender', ['M', 'F']);
            $table->enum('marital_status', ['Célibataire', 'Marié(e)', 'Veuf(ve)', 'Divorcé'])->nullable();
            $table->unsignedTinyInteger('number_of_children')->default(0);
            $table->string('profile_picture')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('believers');
    }
};
