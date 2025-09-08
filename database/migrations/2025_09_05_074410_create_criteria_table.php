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
        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();                // C1..C21
            $table->string('name', 150);
            $table->enum('type', ['benefit', 'cost']);
            $table->enum('source', ['USER', 'ROUTE', 'MOUNTAIN']);
            $table->enum('data_type', ['numeric', 'ordinal', 'categorical']);
            $table->string('unit', 20)->nullable();
            $table->boolean('active')->default(true);
            $table->string('version', 20)->default('v1');        // simple versioning
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criteria');
    }
};