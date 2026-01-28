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
        $tablas = ['procesadores', 'rams', 'discos_duros', 'monitores', 'perifericos'];
        foreach ($tablas as $tabla) {
            Schema::table($tabla, function (Blueprint $table) {
                $table->boolean('is_active')->default(true);
                $table->text('motivo_inactivo')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('components_tables', function (Blueprint $table) {
            //
        });
    }
};
