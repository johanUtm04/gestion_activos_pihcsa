<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('procesadores', function (Blueprint $table) {
            $table->id();
            //llave foranea 
            $table->foreignId('equipo_id')->constrained('equipos');

            $table->string('marca');
            $table->string('descripcion_tipo');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('procesadores');
    }
};
