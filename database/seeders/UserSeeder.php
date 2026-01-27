<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $usuarios = [
    [
        'name' => 'Ana Torres',
        'email' => 'ana.torres@ejemplo.com',
        'rol' => 'ADMIN',
        'departamento' => 'DIRECCION',
        'password' => Hash::make('secreto123'), // Siempre hashear la contraseña
        'estatus' => 'ACTIVO',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'name' => 'Luis Mendoza',
        'email' => 'luis.mendoza@ejemplo.com',
        'rol' => 'SISTEMAS',
        'departamento' => 'SISTEMAS',
        'password' => Hash::make('syspass456'),
        'estatus' => 'ACTIVO',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'name' => 'Carla Ruiz',
        'email' => 'carla.ruiz@ejemplo.com',
        'rol' => 'CONTABILIDAD',
        'departamento' => 'Contabilidad',
        'password' => Hash::make('contabilidad789'),
        'estatus' => 'ACTIVO',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'name' => 'Pedro Solis',
        'email' => 'pedro.solis@ejemplo.com',
        'rol' => 'VENTAS',
        'departamento' => 'VENTAS',
        'password' => Hash::make('ventas2025'),
        'estatus' => 'ACTIVO',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'name' => 'Elena Castro',
        'email' => 'elena.castro@ejemplo.com',
        'rol' => 'ADMIN',
        'departamento' => 'RECURSOS HUMANOS',
        'password' => Hash::make('hrpassword'),
        'estatus' => 'ACTIVO',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
];

        foreach ($usuarios as $ubicacion) {
            User::create($ubicacion);
        }


    }
}
