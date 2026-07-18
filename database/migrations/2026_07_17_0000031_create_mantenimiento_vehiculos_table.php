<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mantenimientos_vehiculos', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users');

            $table->string('tipo_evento');
            $table->integer('kilometraje');
            $table->date('fecha_evento');
            $table->text('contexto');
            $table->decimal('costo', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mantenimientos_vehiculos');
    }
};