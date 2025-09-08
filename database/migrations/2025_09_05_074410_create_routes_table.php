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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mountain_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->decimal('distance_km', 6, 2)->nullable();     // C18
            $table->integer('elevation_gain_m')->nullable();    // C16
            $table->decimal('slope_deg', 5, 2)->nullable();       // opsi numerik C19
            $table->tinyInteger('slope_class')->nullable();     // opsi ordinal C19
            $table->string('land_cover_key', 50)->nullable();    // C17 (key → score via category_maps)
            $table->tinyInteger('water_sources_score')->nullable();   // 0–10 (C20)
            $table->tinyInteger('support_facility_score')->nullable();// 0–10 (C21)
            $table->boolean('permit_required')->default(false);
            $table->timestamps();
            $table->index('mountain_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};