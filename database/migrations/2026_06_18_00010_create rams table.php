<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->string('capacidad_gb')->nullable();
            $table->string('clock_mhz')->nullable();
            $table->string('tipo_chz')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('motivo_inactivo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rams');
    }
};