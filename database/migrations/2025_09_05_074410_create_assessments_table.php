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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 200)->nullable();
            $table->enum('status', ['draft', 'running', 'done', 'failed'])->default('draft');
            $table->unsignedInteger('n_criteria')->default(0);
            $table->unsignedInteger('n_alternatives')->default(0);
            $table->json('weights_json')->nullable();  // override weights {C1:0.03,...}
            $table->json('filters_json')->nullable();
            $table->unsignedTinyInteger('top_k')->default(5);   // show Top-5
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};