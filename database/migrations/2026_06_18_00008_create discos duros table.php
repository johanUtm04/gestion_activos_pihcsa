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
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('capacidad')->nullable();
            $table->string('tipo_hdd_ssd')->nullable();
            $table->string('interface')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('motivo_inactivo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discos_duros');
    }
};