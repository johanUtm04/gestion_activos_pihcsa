<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('equipos', function (Blueprint $table) {
        // Cambiamos a 100 caracteres para estar seguros
        $table->string('sistema_operativo', 100)->change();
    });
}

    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            //
        });
    }
};
