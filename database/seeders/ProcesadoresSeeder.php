<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcesadoresSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 1,
                'equipo_id' => 1,
                'marca' => 'Intel',
                'descripcion_tipo' => 'Core i5',
                'clock_ghz' => '2.60',
                'is_active' => 1,
                'motivo_inactivo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'equipo_id' => 2,
                'marca' => 'Intel',
                'descripcion_tipo' => 'Core i7',
                'clock_ghz' => '3.00',
                'is_active' => 1,
                'motivo_inactivo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'equipo_id' => 3,
                'marca' => 'AMD',
                'descripcion_tipo' => 'Ryzen 5',
                'clock_ghz' => '3.20',
                'is_active' => 1,
                'motivo_inactivo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($rows as $row) {
            DB::table('procesadores')->updateOrInsert(
                ['id' => $row['id']],
                $row
            );
        }
    }
}
