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
        Schema::table('procesadores', function (Blueprint $table) {
            // Creamos la columna después de descripcion_tipo y que acepte nulls
            $table->string('clock_ghz', 50)->nullable()->after('descripcion_tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('procesadores', function (Blueprint $table) {
            $table->dropColumn('clock_ghz');
        });
    }
};