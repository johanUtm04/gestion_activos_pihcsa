<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('correo')->unique(); 
            $table->string('rol', 50)->default('SISTEMAS')->comment('ADMIN, SISTEMAS, CONTABILIDAD.');
            $table->string('departamento');
            $table->string('password'); 
            $table->string('estatus', 50)->default('ACTIVO');
            $table->rememberToken(); 
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};