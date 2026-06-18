<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscosDurosSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 1,
                'equipo_id' => 1,
                'capacidad' => '512 GB',
                'tipo_hdd_ssd' => 'SSD',
                'interface' => 'NVMe',
                'is_active' => 1,
                'motivo_inactivo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'equipo_id' => 2,
                'capacidad' => '1 TB',
                'tipo_hdd_ssd' => 'HDD',
                'interface' => 'SATA',
                'is_active' => 1,
                'motivo_inactivo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'equipo_id' => 3,
                'capacidad' => '512 GB',
                'tipo_hdd_ssd' => 'SSD',
                'interface' => 'NVMe',
                'is_active' => 1,
                'motivo_inactivo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($rows as $row) {
            DB::table('discos_duros')->updateOrInsert(
                ['id' => $row['id']],
                $row
            );
        }
    }
}
