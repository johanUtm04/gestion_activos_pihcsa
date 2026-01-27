<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ubicacion; 

class UbicacionSeeder extends Seeder
{
    public function run(): void
    {
        $ubicaciones = [
            [
                'nombre' => 'Oficina Central (CDMX)',
                'codigo' => 19,
            ],
            [
                'nombre' => 'Sucursal Guadalajara',
                'codigo' => -103,
            ],
            [
                'nombre' => 'Almacén Monterrey',
                'codigo' => -100,
            ],
        ];

        // 2. Insertar usando Eloquent
        foreach ($ubicaciones as $ubicacion) {
            Ubicacion::create($ubicacion);
        }
    }
}
