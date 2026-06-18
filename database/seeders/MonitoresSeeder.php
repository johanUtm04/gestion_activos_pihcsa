<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonitoresSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 1,
                'equipo_id' => 1,
                'marca' => 'Dell',
                'serial' => 'MON-DL-0001',
                'escala_pulgadas' => '24',
                'interface' => 'HDMI',
                'is_active' => 1,
                'motivo_inactivo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'equipo_id' => 2,
                'marca' => 'HP',
                'serial' => 'MON-HP-0002',
                'escala_pulgadas' => '22',
                'interface' => 'DisplayPort',
                'is_active' => 1,
                'motivo_inactivo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'equipo_id' => 3,
                'marca' => 'Lenovo',
                'serial' => 'MON-LN-0003',
                'escala_pulgadas' => '24',
                'interface' => 'HDMI',
                'is_active' => 1,
                'motivo_inactivo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($rows as $row) {
            DB::table('monitores')->updateOrInsert(
                ['id' => $row['id']],
                $row
            );
        }
    }
}
