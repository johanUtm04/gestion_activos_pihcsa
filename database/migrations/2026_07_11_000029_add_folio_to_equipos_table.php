<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->unsignedInteger('folio')->nullable()->unique()->after('id');
        });

        $equipos = DB::table('equipos')
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->pluck('id');

        $contador = 1;
        foreach ($equipos as $id) {
            DB::table('equipos')
                ->where('id', $id)
                ->update(['folio' => $contador]);
            $contador++;
        }

        Schema::table('equipos', function (Blueprint $table) {
            $table->unsignedInteger('folio')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('equipos', function (Blueprint $table) {
            $table->dropUnique(['folio']);
            $table->dropColumn('folio');
        });
    }
};