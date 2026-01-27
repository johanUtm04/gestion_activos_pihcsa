<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('historiales_log', function (Blueprint $table) {
            $table->id();
            //llaves foraneas
            $table->foreignId('activo_id')->constrained('equipos');
            $table->foreignId('usuario_accion_id')->constrained('users');
            $table->string('tipo_registro');
            $table->json('detalles_json')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('historiales_log');
    }
};
