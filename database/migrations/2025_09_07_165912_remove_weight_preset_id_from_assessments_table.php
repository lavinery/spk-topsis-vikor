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
        if (Schema::hasColumn('assessments', 'weight_preset_id')) {
            Schema::table('assessments', function (Blueprint $table) {
                // Try to drop FK + column using the conventional helper; if FK doesn't exist, just drop the column
                try {
                    $table->dropConstrainedForeignId('weight_preset_id');
                } catch (\Throwable $e) {
                    $table->dropColumn('weight_preset_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->unsignedBigInteger('weight_preset_id')->nullable();
        });
    }
};