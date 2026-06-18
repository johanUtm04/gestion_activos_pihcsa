<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehiculoDocumentacionSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 1,
                'vehiculo_id' => 1,
                'no_poliza_seguro' => 'POL-HILUX-2026',
                'vigencia_seguro' => '2026-12-31',
                'tarjeta_circulacion' => 'TC-HILUX-001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'vehiculo_id' => 2,
                'no_poliza_seguro' => 'POL-NP300-2026',
                'vigencia_seguro' => '2026-11-30',
                'tarjeta_circulacion' => 'TC-NP300-002',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'vehiculo_id' => 3,
                'no_poliza_seguro' => 'POL-AVEO-2026',
                'vigencia_seguro' => '2026-10-31',
                'tarjeta_circulacion' => 'TC-AVEO-003',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($rows as $row) {
            DB::table('vehiculo_documentacion')->updateOrInsert(
                ['id' => $row['id']],
                $row
            );
        }
    }
}
