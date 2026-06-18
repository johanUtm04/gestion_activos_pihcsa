<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 1,
                'name' => 'Administrador PIHCSA',
                'email' => 'admin@pihcsa.local',
                'rol' => 'ADMIN',
                'departamento' => 'SISTEMAS',
                'password' => Hash::make('password'),
                'estatus' => 'ACTIVO',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Soporte Sistemas',
                'email' => 'sistemas@pihcsa.local',
                'rol' => 'SISTEMAS',
                'departamento' => 'SISTEMAS',
                'password' => Hash::make('password'),
                'estatus' => 'ACTIVO',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Contabilidad General',
                'email' => 'contabilidad@pihcsa.local',
                'rol' => 'CONTABILIDAD',
                'departamento' => 'CONTABILIDAD',
                'password' => Hash::make('password'),
                'estatus' => 'ACTIVO',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Operaciones',
                'email' => 'operaciones@pihcsa.local',
                'rol' => 'SISTEMAS',
                'departamento' => 'OPERACIONES',
                'password' => Hash::make('password'),
                'estatus' => 'ACTIVO',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($rows as $row) {
            DB::table('users')->updateOrInsert(
                ['id' => $row['id']],
                $row
            );
        }
    }
}
