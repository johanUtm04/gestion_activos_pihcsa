<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CleanupEquiposColumns extends Migration
{
    /**
     * Ejecuta la migración (Borra las columnas).
     */
    public function up()
    {
        Schema::table('equipos', function (Blueprint $table) {
            // Eliminamos las columnas que causan los 'undefined'
            $table->dropColumn(['marca_equipo', 'tipo_equipo']);
        });
    }

    /**
     * Revierte la migración (Vuelve a crear las columnas).
     */
    public function down()
    {
        Schema::table('equipos', function (Blueprint $table) {
            // Si algo falla, las volvemos a crear como estaban originalmente
            $table->string('marca_equipo')->nullable();
            $table->string('tipo_equipo')->nullable();
        });
    }
}