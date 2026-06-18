<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistorialesLogSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 1,
                'activo_id' => 1,
                'usuario_accion_id' => 1,
                'tipo_registro' => 'CREACION',
                'detalles_json' => '{"tabla":"equipos","accion":"Equipo registrado desde seeder"}',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'activo_id' => 2,
                'usuario_accion_id' => 1,
                'tipo_registro' => 'CREACION',
                'detalles_json' => '{"tabla":"equipos","accion":"Equipo registrado desde seeder"}',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'activo_id' => 3,
                'usuario_accion_id' => 1,
                'tipo_registro' => 'CREACION',
                'detalles_json' => '{"tabla":"equipos","accion":"Equipo registrado desde seeder"}',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($rows as $row) {
            DB::table('historiales_log')->updateOrInsert(
                ['id' => $row['id']],
                $row
            );
        }
    }
}
