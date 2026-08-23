<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquiposSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 1,
                'folio' => 1001,
                'marca_equipo' => 'Dell',
                'marca_id' => 1,
                'tipo_equipo' => 'Laptop',
                'tipo_activo_id' => 1,
                'serial' => 'DL-LAT-0001',
                'sistema_operativo' => 'Windows 11 Pro',
                'usuario_id' => 2,
                'ubicacion_id' => 1,
                'valor_inicial' => '18500.00',
                'fecha_adquisicion' => '2024-01-15',
                'vida_util_estimada' => '36 meses',
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'folio' => 1002,
                'marca_equipo' => 'HP',
                'marca_id' => 2,
                'tipo_equipo' => 'Desktop',
                'tipo_activo_id' => 2,
                'serial' => 'HP-ELI-0002',
                'sistema_operativo' => 'Windows 10 Pro',
                'usuario_id' => 3,
                'ubicacion_id' => 1,
                'valor_inicial' => '14500.00',
                'fecha_adquisicion' => '2023-08-10',
                'vida_util_estimada' => '48 meses',
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'folio' => 1003,
                'marca_equipo' => 'Lenovo',
                'marca_id' => 3,
                'tipo_equipo' => 'Laptop',
                'tipo_activo_id' => 1,
                'serial' => 'LN-TP-0003',
                'sistema_operativo' => 'Windows 11 Pro',
                'usuario_id' => 4,
                'ubicacion_id' => 2,
                'valor_inicial' => '21000.00',
                'fecha_adquisicion' => '2025-02-05',
                'vida_util_estimada' => '36 meses',
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($rows as $row) {
            DB::table('equipos')->updateOrInsert(
                ['id' => $row['id']],
                $row
            );
        }
    }
}
