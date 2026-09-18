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
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['recette', 'depense']);
            $table->foreignId('category_id')->constrained('finance_categories')->restrictOnDelete();
            $table->foreignId('account_id')->constrained('finance_accounts')->restrictOnDelete();
            $table->foreignId('period_id')->nullable()->constrained('finance_periods')->nullOnDelete();
            $table->decimal('montant', 14, 2);
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('piece_justificative_path')->nullable();
            // Fléchage optionnel vers un projet (Construction ou Foncière)
            $table->string('fleche_type')->nullable(); // 'construction_project' | 'dossier_foncier'
            $table->unsignedBigInteger('fleche_id')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};
