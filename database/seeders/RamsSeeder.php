<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RamsSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 1,
                'equipo_id' => 1,
                'capacidad_gb' => '16',
                'clock_mhz' => '3200',
                'tipo_chz' => 'DDR4',
                'is_active' => 1,
                'motivo_inactivo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'equipo_id' => 2,
                'capacidad_gb' => '8',
                'clock_mhz' => '2666',
                'tipo_chz' => 'DDR4',
                'is_active' => 1,
                'motivo_inactivo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'equipo_id' => 3,
                'capacidad_gb' => '16',
                'clock_mhz' => '3200',
                'tipo_chz' => 'DDR4',
                'is_active' => 1,
                'motivo_inactivo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($rows as $row) {
            DB::table('rams')->updateOrInsert(
                ['id' => $row['id']],
                $row
            );
        }
    }
}
