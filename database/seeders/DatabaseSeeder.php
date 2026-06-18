<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            UsersSeeder::class,
            UbicacionesSeeder::class,
            MarcasSeeder::class,
            TipoActivosSeeder::class,
            CatTipoVehiculosSeeder::class,

            EquiposSeeder::class,
            VehiculosSeeder::class,

            ProcesadoresSeeder::class,
            RamsSeeder::class,
            DiscosDurosSeeder::class,
            MonitoresSeeder::class,
            PerifericosSeeder::class,
            VehiculoDocumentacionSeeder::class,
            HistorialesLogSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
