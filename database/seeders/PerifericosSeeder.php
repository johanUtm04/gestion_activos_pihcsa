<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerifericosSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 1,
                'equipo_id' => 1,
                'tipo' => 'Mouse',
                'marca' => 'Logitech',
                'serial' => 'PER-MOU-0001',
                'interface' => 'USB',
                'is_active' => 1,
                'motivo_inactivo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'equipo_id' => 1,
                'tipo' => 'Teclado',
                'marca' => 'Logitech',
                'serial' => 'PER-TEC-0002',
                'interface' => 'USB',
                'is_active' => 1,
                'motivo_inactivo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'equipo_id' => 2,
                'tipo' => 'Mouse',
                'marca' => 'HP',
                'serial' => 'PER-MOU-0003',
                'interface' => 'USB',
                'is_active' => 1,
                'motivo_inactivo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($rows as $row) {
            DB::table('perifericos')->updateOrInsert(
                ['id' => $row['id']],
                $row
            );
        }
    }
}
