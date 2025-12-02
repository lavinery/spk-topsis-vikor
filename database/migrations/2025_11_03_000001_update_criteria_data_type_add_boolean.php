<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('criteria', function (Blueprint $table) {
            // Change enum to include boolean
            $table->enum('data_type', ['numeric','ordinal','categorical','boolean'])->default('numeric')->change();
        });
    }

    public function down(): void
    {
        Schema::table('criteria', function (Blueprint $table) {
            // Revert to original set (without boolean)
            $table->enum('data_type', ['numeric','ordinal','categorical'])->default('numeric')->change();
        });
    }
};


