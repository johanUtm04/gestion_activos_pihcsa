<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('discos_duros', function (Blueprint $table) {
            $table->id();
            //Llave foranea
            $table->foreignId('equipo_id')->constrained('equipos');

            $table->string('capacidad');
            $table->string('tipo_hdd_ssd');
            $table->string('interface');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discos_duros');
    }
};
