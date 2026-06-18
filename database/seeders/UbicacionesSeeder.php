<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbicacionesSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 1,
                'nombre' => 'Corporativo',
                'codigo' => 'CORP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nombre' => 'Bodega Morelia',
                'codigo' => 'MOR',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nombre' => 'Bodega Bajío',
                'codigo' => 'BAJ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nombre' => 'Taller Vehicular',
                'codigo' => 'TALLER',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($rows as $row) {
            DB::table('ubicaciones')->updateOrInsert(
                ['id' => $row['id']],
                $row
            );
        }
    }
}
