<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->string('cuenta_contable')->nullable()->after('pedimento');
        });

        Schema::table('vehiculos', function (Blueprint $table) {
            $table->string('cuenta_contable')->nullable()->after('no_motor');
        });
    }

    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropColumn('cuenta_contable');
        });

        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropColumn('cuenta_contable');
        });
    }
};