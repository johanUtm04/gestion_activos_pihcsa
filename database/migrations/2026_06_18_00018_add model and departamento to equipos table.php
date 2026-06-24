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
        Schema::table('equipos', function (Blueprint $table) {
            // Safe guards to ensure columns don't duplicate if partially migrated
            if (!Schema::hasColumn('equipos', 'modelo')) {
                $table->string('modelo')->nullable()->after('tipo_activo_id');
            }
            
            if (!Schema::hasColumn('equipos', 'departamento_perteneciente')) {
                $table->string('departamento_perteneciente')->nullable()->after('ubicacion_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropColumn(['modelo', 'departamento_perteneciente']);
        });
    }
};