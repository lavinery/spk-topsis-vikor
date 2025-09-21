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
        Schema::create('assessment_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->json('criteria');        // daftar kriterian (id, code, name, type, source, active, sort)
            $table->json('weights');         // [code => weight_norm]
            $table->json('category_maps');   // {criterion_code: {key: score}}
            $table->json('params');          // {top_k:5, pure:true, weight_preset_id:1}
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_snapshots');
    }
};