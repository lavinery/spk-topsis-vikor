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
        Schema::table('assessments', function (Blueprint $table) {
            // Change pure_formula default from 1 (TRUE) to 0 (FALSE)
            // This ensures new assessments include ALL criteria (USER + MOUNTAIN/ROUTE) by default
            $table->boolean('pure_formula')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            // Restore original default (though this was wrong)
            $table->boolean('pure_formula')->default(true)->change();
        });
    }
};
