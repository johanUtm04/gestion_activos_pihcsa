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
        Schema::table('equipos', function (Blueprint $table) {
            if (!Schema::hasColumn('equipos', 'fecha_inicio_uso')) {
                // Using date type since it holds 'YYYY-MM-DD' values based on your query error
                $table->date('fecha_inicio_uso')->nullable()->after('fecha_adquisicion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropColumn('fecha_inicio_uso');
        });
    }
};