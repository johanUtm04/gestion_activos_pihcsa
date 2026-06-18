<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('marca_equipo')->nullable()->comment('Ej. Lenovo, Dell');
            $table->foreignId('marca_id')->nullable()->constrained('marcas');
            $table->string('tipo_equipo')->nullable();
            $table->foreignId('tipo_activo_id')->nullable()->constrained('tipo_activos');
            $table->string('serial')->nullable();
            $table->string('sistema_operativo')->nullable();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ubicacion_id')->constrained('ubicaciones')->cascadeOnDelete();
            $table->string('valor_inicial')->nullable();
            $table->string('fecha_adquisicion')->nullable();
            $table->string('vida_util_estimada')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};