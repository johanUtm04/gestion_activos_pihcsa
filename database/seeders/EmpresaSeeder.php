<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresas = [
            [
                'nombre' => 'PIHCSA Morelia',
                'rfc' => 'PIH123456M78', // Puedes poner un RFC ficticio o dejarlo vacío
                'activo' => true,
            ],
            [
                'nombre' => 'Corporación Azul',
                'rfc' => 'CAZ876543A21',
                'activo' => true,
            ],
        ];

        foreach ($empresas as $empresa) {
            Empresa::updateOrCreate(
                ['nombre' => $empresa['nombre']], // Condición para no duplicar
                [
                    'rfc' => $empresa['rfc'],
                    'activo' => $empresa['activo']
                ]
            );
        }
    }
}