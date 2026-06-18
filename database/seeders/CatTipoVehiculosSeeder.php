<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatTipoVehiculosSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 1,
                'nombre' => 'Automóvil',
                'frecuencia_meses' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nombre' => 'Pick Up',
                'frecuencia_meses' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nombre' => 'Camioneta',
                'frecuencia_meses' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nombre' => 'Camión',
                'frecuencia_meses' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($rows as $row) {
            DB::table('cat_tipo_vehiculos')->updateOrInsert(
                ['id' => $row['id']],
                $row
            );
        }
    }
}
