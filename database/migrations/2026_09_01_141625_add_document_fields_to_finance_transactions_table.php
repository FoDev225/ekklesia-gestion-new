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
        Schema::table('finance_transactions', function (Blueprint $table) {
            $table->string('numero_document')->nullable()->after('id');
            $table->enum('mode_paiement', ['espece', 'cheque'])->nullable()->after('numero_document');
            $table->string('numero_cheque')->nullable()->after('mode_paiement');
            $table->string('beneficiaire')->nullable()->after('numero_cheque'); // bon de sortie
            $table->string('emetteur')->nullable()->after('beneficiaire');
            $table->string('verificateur')->nullable()->after('emetteur');
            $table->string('recepteur')->nullable()->after('verificateur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finance_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'numero_document', 'mode_paiement', 'numero_cheque',
                'beneficiaire', 'emetteur', 'verificateur', 'recepteur',
            ]);
        });
    }
};
