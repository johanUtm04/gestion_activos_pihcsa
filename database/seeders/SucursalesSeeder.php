<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class SucursalesSeeder extends Seeder
{
public function run(): void
{
    DB::table('sucursales')->insert([
        'clave' => 'SUC-MORE-01',
        'nombre' => 'Sucursal Central',
        'database_name' => 'gestion_activos_pihcsa',
        'estatus' => 'activo',
        'descripcion' => 'Oficina principal corporativa y almacén central de activos.',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
}