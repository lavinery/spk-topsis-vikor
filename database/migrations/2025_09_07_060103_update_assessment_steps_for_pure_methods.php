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
        Schema::table('assessment_steps', function (Blueprint $table) {
            // Change step from enum to string to support new step names
            $table->string('step', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_steps', function (Blueprint $table) {
            // Revert back to enum (this might cause data loss)
            $table->enum('step', [
                'MATRIX_X', 'NORMALIZED_R', 'WEIGHTED_Y',
                'IDEAL_SOLUTION', 'DISTANCES', 'CLOSENESS_COEFF', 'RANKING'
            ])->change();
        });
    }
};