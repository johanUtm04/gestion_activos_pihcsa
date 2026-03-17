<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveRedundantColumnsFromEquiposTableV2 extends Migration
{
    /**
     * Elimina las columnas marca_equipo y tipo_equipo.
     */
    public function up()
    {
        Schema::table('equipos', function (Blueprint $table) {
            // Verificamos si existen antes de intentar borrarlas por seguridad
            if (Schema::hasColumn('equipos', 'marca_equipo')) {
                $table->dropColumn('marca_equipo');
            }
            if (Schema::hasColumn('equipos', 'tipo_equipo')) {
                $table->dropColumn('tipo_equipo');
            }
        });
    }

    /**
     * Revierte los cambios volviendo a crear las columnas.
     */
    public function down()
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->string('marca_equipo')->nullable();
            $table->string('tipo_equipo')->nullable();
        });
    }
}