<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('Rams', function (Blueprint $table) {
            $table->id();
            //llave foranea 
            $table->foreignId('equipo_id')->constrained('equipos');

            $table->string('capacidad_gb');
            $table->string('clock_mhz')->nullable();
            $table->string('tipo_chz')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Rams');
    }
};
