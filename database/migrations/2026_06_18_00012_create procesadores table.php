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
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->string('marca', 50)->nullable();
            $table->string('descripcion_tipo', 50)->nullable();
            $table->string('clock_ghz', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('motivo_inactivo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procesadores');
    }
};