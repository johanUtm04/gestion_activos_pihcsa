<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('monitores', function (Blueprint $table) {
            $table->id();
            //llave foranea 
            $table->foreignId('equipo_id')->constrained('equipos')
            ->onDelete('cascade'); 
            ;

            $table->string('marca');
            $table->string('serial');
            $table->string('escala_pulgadas');
            $table->string('interface');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('monitores');
    }
};
