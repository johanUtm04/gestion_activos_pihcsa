<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->timestamps();
        });

        $departamentosIniciales = [
            'ADMINISTRACION', 'ALMACEN', 'CALIDAD', 'COBRANZA', 'COMPRAS',
            'CONTABILIDAD', 'CREDITO', 'CULTURA Y TALENTO', 'DIRECCION',
            'EMBARQUES', 'INVENTARIOS', 'JURIDICO', 'LOGISTICA',
            'SISTEMAS', 'VENTAS',
        ];

        $now = now();
        DB::table('departamentos')->insert(
            collect($departamentosIniciales)->map(fn($nombre) => [
                'nombre' => $nombre,
                'created_at' => $now,
                'updated_at' => $now,
            ])->toArray()
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('departamentos');
    }
};