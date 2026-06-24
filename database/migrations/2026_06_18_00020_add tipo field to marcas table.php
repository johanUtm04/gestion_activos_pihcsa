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
        Schema::table('marcas', function (Blueprint $table) {
            if (!Schema::hasColumn('marcas', 'tipo')) {
                // Using a string column with a default value to avoid breaking existing records
                $table->string('tipo')->default('TI')->after('nombre');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marcas', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }
};