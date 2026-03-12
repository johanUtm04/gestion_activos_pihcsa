<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inpc_indices', function (Blueprint $table) {
            $table->id();
            $table->integer('anio'); 
            $table->integer('mes'); // 1 al 12
            $table->decimal('valor', 10, 4); // Ej: 113.0180
            $table->timestamps();
            
            // Evitamos que se repita el mismo mes y año
            $table->unique(['anio', 'mes']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inpc');
    }
};
