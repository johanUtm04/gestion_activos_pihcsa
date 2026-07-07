<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::connection('mysql')->create('sucursales', function (Blueprint $table) {
            $table->id();

            $table->string('clave', 50)->unique();

            $table->string('nombre', 100);

            $table->string('database_name', 100)->unique();

            $table->string('estatus', 30)->default('activo');

            $table->text('descripcion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('mysql')->dropIfExists('sucursales');
    }
};