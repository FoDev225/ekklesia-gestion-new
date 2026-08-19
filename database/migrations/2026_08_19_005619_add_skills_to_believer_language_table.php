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
        Schema::table('believer_language', function (Blueprint $table) {
            $table->boolean('lu')->default(false);
            $table->boolean('parle')->default(false);
            $table->boolean('ecrit')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('believer_language', function (Blueprint $table) {
            $table->dropColumn(['lu', 'parle', 'ecrit']);
        });
    }
};
