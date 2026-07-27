<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sanctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('believer_id')->constrained('believers')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();                // null = indéterminée
            $table->text('reason');
            $table->string('decided_by')->nullable();
            $table->boolean('is_active')->default(false);        // false = levée
            $table->date('lifted_at')->nullable();
            $table->text('lift_note')->nullable();
            $table->timestamps();
        });

        // Champs sur believers pour accès rapide
        Schema::table('believers', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('number_of_children');
            $table->enum('status', ['actif', 'inactif', 'sanctionne', 'parti', 'decede'])
                  ->default('actif')->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('believers', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'status']);
        });
        Schema::dropIfExists('sanctions');
    }
};