<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehiculosSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 1,
                'tipo_vehiculo_id' => 2,
                'marca_id' => 4,
                'usuario_id' => 4,
                'ubicacion_id' => 4,
                'modelo' => 'Hilux',
                'anio' => '2022',
                'placas' => 'ABC-123-A',
                'no_serie' => '8AJBA3CDX00123456',
                'no_motor' => '2GD123456',
                'cilindros' => 4,
                'tipo_combustible' => 'Diésel',
                'valor_inicial' => '520000.00',
                'fecha_adquisicion' => '2022-06-20',
                'vida_util_estimada' => '60 meses',
                'fecha_ultimo_mantenimiento' => '2026-05-10',
                'is_active' => 1,
                'motivo_inactivacion' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'tipo_vehiculo_id' => 3,
                'marca_id' => 5,
                'usuario_id' => 2,
                'ubicacion_id' => 2,
                'modelo' => 'NP300',
                'anio' => '2021',
                'placas' => 'DEF-456-B',
                'no_serie' => '3N6AD33A1MK123456',
                'no_motor' => 'QR25123456',
                'cilindros' => 4,
                'tipo_combustible' => 'Gasolina',
                'valor_inicial' => '410000.00',
                'fecha_adquisicion' => '2021-09-12',
                'vida_util_estimada' => '60 meses',
                'fecha_ultimo_mantenimiento' => '2026-04-25',
                'is_active' => 1,
                'motivo_inactivacion' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'tipo_vehiculo_id' => 1,
                'marca_id' => 6,
                'usuario_id' => 3,
                'ubicacion_id' => 1,
                'modelo' => 'Aveo',
                'anio' => '2023',
                'placas' => 'GHI-789-C',
                'no_serie' => 'LSGHD52H0PD123456',
                'no_motor' => 'L2B123456',
                'cilindros' => 4,
                'tipo_combustible' => 'Gasolina',
                'valor_inicial' => '285000.00',
                'fecha_adquisicion' => '2023-03-18',
                'vida_util_estimada' => '60 meses',
                'fecha_ultimo_mantenimiento' => '2026-03-14',
                'is_active' => 1,
                'motivo_inactivacion' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($rows as $row) {
            DB::table('vehiculos')->updateOrInsert(
                ['id' => $row['id']],
                $row
            );
        }
    }
}
