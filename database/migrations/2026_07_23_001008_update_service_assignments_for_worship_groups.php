<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_assignments', function (Blueprint $table) {
            // Retire l'ancienne référence vers "groups" (non utilisée pour la louange)
            if (Schema::hasColumn('service_assignments', 'group_id')) {
                $table->dropForeign(['group_id']);
                $table->dropColumn('group_id');
            }
        });

        Schema::table('service_assignments', function (Blueprint $table) {
            $table->foreignId('worship_group_id')->nullable()->after('service_role_id')
                ->constrained('worship_groups')->nullOnDelete();

            // Un fidèle n'est plus obligatoire (le rôle Louange n'en a pas besoin)
            $table->foreignId('believer_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('service_assignments', function (Blueprint $table) {
            $table->dropForeign(['worship_group_id']);
            $table->dropColumn('worship_group_id');
            $table->foreignId('believer_id')->nullable(false)->change();
        });
    }
};