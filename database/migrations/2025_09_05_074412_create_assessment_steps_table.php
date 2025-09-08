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
        Schema::create('assessment_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->enum('step', [
                'MATRIX_X', 'NORMALIZED_R', 'WEIGHTED_Y',
                'IDEAL_SOLUTION', 'DISTANCES', 'CLOSENESS_COEFF', 'RANKING'
            ]);
            $table->json('payload'); // simpan data step
            $table->timestamps();
            $table->index(['assessment_id', 'step']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_steps');
    }
};