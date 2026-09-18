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
        Schema::create('finance_engagements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('believer_id')->constrained()->cascadeOnDelete();
            $table->string('motif'); // ex: "Construction", "Cotisation FID 2026"
            $table->string('fleche_type')->nullable(); // 'construction_project' | 'dossier_foncier'
            $table->unsignedBigInteger('fleche_id')->nullable();
            $table->decimal('montant_engage', 14, 2);
            $table->date('date_engagement');
            $table->text('observations')->nullable();
            $table->timestamps();
        });

        Schema::create('finance_engagement_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('engagement_id')->constrained('finance_engagements')->cascadeOnDelete();
            $table->string('numero_recu')->nullable();
            $table->decimal('montant', 14, 2);
            $table->date('date');
            $table->string('encaisseur')->nullable();
            $table->foreignId('account_id')->nullable()->constrained('finance_accounts')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_engagement_payments');
        Schema::dropIfExists('finance_engagements');
    }
};
