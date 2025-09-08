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
        Schema::dropIfExists('weight_preset_items');
        Schema::dropIfExists('weight_presets');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate weight_presets table
        Schema::create('weight_presets', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->json('weights_json');
            $table->text('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
        
        // Recreate weight_preset_items table
        Schema::create('weight_preset_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('weight_preset_id');
            $table->unsignedBigInteger('criterion_id');
            $table->decimal('weight', 8, 4);
            $table->timestamps();
            
            $table->foreign('weight_preset_id')->references('id')->on('weight_presets')->onDelete('cascade');
            $table->foreign('criterion_id')->references('id')->on('criteria')->onDelete('cascade');
        });
    }
};