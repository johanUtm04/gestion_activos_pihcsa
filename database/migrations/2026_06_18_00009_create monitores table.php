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
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->string('marca')->nullable();
            $table->string('serial')->nullable();
            $table->string('escala_pulgadas')->nullable();
            $table->string('interface')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('motivo_inactivo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitores');
    }
};