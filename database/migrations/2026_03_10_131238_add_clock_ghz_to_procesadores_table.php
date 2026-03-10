<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('procesadores', function (Blueprint $blueprint) {
            $blueprint->decimal('clock_ghz', 8, 2)->nullable()->after('descripcion_tipo');
        });
    }

    public function down(): void
    {
        Schema::table('procesadores', function (Blueprint $blueprint) {
            $blueprint->dropColumn('clock_ghz');
        });
    }
};