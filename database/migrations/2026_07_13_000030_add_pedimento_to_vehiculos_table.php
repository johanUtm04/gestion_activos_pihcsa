<?php

use App\Models\Sucursal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $bases = Sucursal::on('mysql')
            ->where('estatus', 'activo')
            ->pluck('database_name')
            ->toArray();

        foreach ($bases as $databaseName) {
            config([
                'database.connections.temp_sucursal' => array_merge(
                    config('database.connections.mysql'),
                    ['database' => $databaseName]
                ),
            ]);

            DB::purge('temp_sucursal');
            DB::reconnect('temp_sucursal');

            if (
                Schema::connection('temp_sucursal')->hasTable('vehiculos') &&
                ! Schema::connection('temp_sucursal')->hasColumn('vehiculos', 'pedimento')
            ) {
                Schema::connection('temp_sucursal')->table('vehiculos', function (Blueprint $table) {
                    $table->string('pedimento', 100)
                        ->nullable()
                        ->after('cilindros');
                });
            }
        }
    }

    public function down(): void
    {
        $bases = Sucursal::on('mysql')
            ->where('estatus', 'activo')
            ->pluck('database_name')
            ->toArray();

        foreach ($bases as $databaseName) {
            config([
                'database.connections.temp_sucursal' => array_merge(
                    config('database.connections.mysql'),
                    ['database' => $databaseName]
                ),
            ]);

            DB::purge('temp_sucursal');
            DB::reconnect('temp_sucursal');

            if (
                Schema::connection('temp_sucursal')->hasTable('vehiculos') &&
                Schema::connection('temp_sucursal')->hasColumn('vehiculos', 'pedimento')
            ) {
                Schema::connection('temp_sucursal')->table('vehiculos', function (Blueprint $table) {
                    $table->dropColumn('pedimento');
                });
            }
        }
    }
};