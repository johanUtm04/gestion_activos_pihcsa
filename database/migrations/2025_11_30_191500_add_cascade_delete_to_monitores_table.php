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
        Schema::table('monitores', function (Blueprint $table) {
        // Se elimina la clave foránea existente (paso necesario)
        $table->dropForeign(['equipo_id']);

        //llave foranea 
        $table->foreignId('equipo_id')->constrained('equipos')
        ->onDelete('cascade'); 
        ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitores', function (Blueprint $table) {
            //
        });
    }
};
