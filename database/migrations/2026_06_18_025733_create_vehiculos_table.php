<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            
            // CORREGIDO: Ahora apunta exactamente a 'cat_tipo_vehiculos'
            $table->foreignId('tipo_vehiculo_id')->constrained('cat_tipo_vehiculos');
            $table->foreignId('marca_id')->constrained('marcas'); 
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ubicacion_id')->constrained('ubicaciones')->onDelete('cascade');
            
            // Datos de Identificación
            $table->string('modelo');
            $table->string('anio');
            $table->string('placas')->nullable();
            $table->string('no_serie')->nullable(); 
            
            // Campos mecánicos planos 
            $table->string('no_motor')->nullable();
            $table->integer('cilindros')->nullable(); 
            $table->string('tipo_combustible')->nullable(); 
            
            // Estructura de costos y control de fechas
            $table->string('valor_inicial')->nullable();
            $table->string('fecha_adquisicion')->nullable();
            $table->string('vida_util_estimada')->nullable();
            $table->date('fecha_ultimo_mantenimiento')->nullable(); 
            
            // Estado operativo básico
            $table->tinyInteger('is_active')->default(1);
            $table->text('motivo_inactivacion')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};