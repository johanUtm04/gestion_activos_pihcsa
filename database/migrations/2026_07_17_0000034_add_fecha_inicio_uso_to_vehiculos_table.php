<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            if (!Schema::hasColumn('vehiculos', 'fecha_inicio_uso')) {
                $table->date('fecha_inicio_uso')->nullable()->after('fecha_adquisicion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            if (Schema::hasColumn('vehiculos', 'fecha_inicio_uso')) {
                $table->dropColumn('fecha_inicio_uso');
            }
        });
    }
};