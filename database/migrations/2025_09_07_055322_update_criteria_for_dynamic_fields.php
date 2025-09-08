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
        Schema::table('criteria', function (Blueprint $table) {
            // Update existing columns
            $table->string('name', 120)->nullable()->change();         // resize name column
            $table->string('unit', 24)->nullable()->change();          // resize unit column
            
            // Add new columns
            $table->integer('sort_order')->default(0);                 // urutan dinamis
            $table->enum('scale',['numeric','categorical'])->default('numeric');
            $table->decimal('min_hint',12,3)->nullable();
            $table->decimal('max_hint',12,3)->nullable();
            $table->json('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('criteria', function (Blueprint $table) {
            $table->dropColumn([
                'sort_order', 'scale', 'min_hint', 'max_hint', 'notes'
            ]);
        });
    }
};