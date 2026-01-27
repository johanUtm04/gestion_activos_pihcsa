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
            //
	$table->foreignId('marca_id')->nullable()->after('marca_equipo')->constrained('marcas');
        $table->foreignId('tipo_activo_id')->nullable()->after('tipo_equipo')->constrained('tipo_activos');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            //
	$table->dropForeign(['marca_id']);
	$table->dropForeign(['tipo_activo_id']);
	$table->dropColumn(['marca_id', 'tipo_activo_id']);
        });
    }
};
