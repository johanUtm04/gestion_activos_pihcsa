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
        Schema::table('mantenimientos_vehiculos', function (Blueprint $table) {
            $table->string('proveedor')->nullable()->after('tipo_evento');

            $table->string('orden_servicio_path')->nullable()->after('costo');
            $table->string('factura_path')->nullable()->after('orden_servicio_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mantenimientos_vehiculos', function (Blueprint $table) {
            $table->dropColumn(['proveedor', 'orden_servicio_path', 'factura_path']);
        });
    }
};