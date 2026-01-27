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
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('marca_equipo')->nullable()->comment('Ej. Lenovo, Dell');
            $table->string('tipo_equipo');
            $table->string('serial');
            $table->string('sistema_operativo', 11);
            //Llaves foraneas
            $table->foreignId('usuario_id')->constrained('users');
            $table->foreignId('ubicacion_id')->constrained('ubicaciones');
            
            $table->decimal('valor_inicial');
            $table->date('fecha_adquisicion');
            $table->string('vida_util_estimada');
            //depreciacion acumulada sera con codigo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
