<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('procesadores', function (Blueprint $table) {
            $table->string('clock_ghz', 50)->nullable()->after('descripcion_tipo');
        });
    }

    public function down(): void
    {
        Schema::table('procesadores', function (Blueprint $table) {
            $table->dropColumn('clock_ghz');
        });
    }
};