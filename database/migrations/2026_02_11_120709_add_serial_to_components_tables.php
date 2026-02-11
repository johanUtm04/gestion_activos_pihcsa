<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar a Discos Duros
        Schema::table('discos_duros', function (Blueprint $table) {
            $table->string('serial')->nullable()->after('equipo_id');
        });

        // Agregar a RAMs
        Schema::table('rams', function (Blueprint $table) {
            $table->string('serial')->nullable()->after('equipo_id');
        });
    }

    public function down(): void
    {
        Schema::table('discos_duros', function (Blueprint $table) {
            $table->dropColumn('serial');
        });

        Schema::table('rams', function (Blueprint $table) {
            $table->dropColumn('serial');
        });
    }
};
