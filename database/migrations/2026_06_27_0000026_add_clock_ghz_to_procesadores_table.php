<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Añade la columna SOLO si no existe en la tabla
        if (!Schema::hasColumn('procesadores', 'clock_ghz')) {
            Schema::table('procesadores', function (Blueprint $table) {
                $table->string('clock_ghz', 50)->nullable()->after('descripcion_tipo');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('procesadores', 'clock_ghz')) {
            Schema::table('procesadores', function (Blueprint $table) {
                $table->dropColumn('clock_ghz');
            });
        }
    }
};
