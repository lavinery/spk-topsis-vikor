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
        Schema::create('fuzzy_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criterion_id')->constrained('criteria')->onDelete('cascade');
            $table->decimal('input_min', 10, 2); // minimum input value (e.g., 1 for scale 1-5)
            $table->decimal('input_max', 10, 2); // maximum input value (e.g., 5 for scale 1-5)
            $table->string('default_term_code', 20)->nullable(); // default term when input is outside range
            $table->timestamps();
            
            $table->unique('criterion_id'); // one mapping per criterion
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuzzy_mappings');
    }
};