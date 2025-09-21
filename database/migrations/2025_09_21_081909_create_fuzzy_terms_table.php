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
        Schema::create('fuzzy_terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criterion_id')->constrained('criteria')->onDelete('cascade');
            $table->string('code', 20); // e.g., 'RENDAH', 'SEDANG', 'TINGGI'
            $table->string('label', 50); // e.g., 'Rendah', 'Sedang', 'Tinggi'
            $table->enum('shape', ['triangular', 'trapezoidal']);
            $table->json('params_json'); // [a,b,c] for triangular, [a,b,c,d] for trapezoidal
            $table->timestamps();
            
            $table->unique(['criterion_id', 'code']);
            $table->index(['criterion_id', 'shape']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuzzy_terms');
    }
};