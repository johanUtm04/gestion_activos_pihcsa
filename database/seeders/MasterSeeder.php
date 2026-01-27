<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Equipo;
use App\Models\User;
use App\Models\Ubicacion;
use App\Models\Monitor;
use App\Models\DiscoDuro;
use App\Models\Ram;
use App\Models\Periferico;
use App\Models\Procesador;


class MasterSeeder extends Seeder
{
public function run(): void
    {

        $usuarios = User::factory()->count(5)->create();
        $ubicaciones = Ubicacion::factory()->count(3)->create();
        
        Equipo::factory()
            ->count(10) 

            ->has(Monitor::factory()->count(rand(1, 2)), 'monitores') 
            ->has(DiscoDuro::factory()->count(2), 'discosDuros')
            ->has(Ram::factory()->count(4), 'rams') 
            ->has(Periferico::factory()->count(1), 'perifericos') 
            ->has(Procesador::factory()->count(1), 'procesadores') 
            ->create([
                'usuario_id' => $usuarios->random()->id,
                'ubicacion_id' => $ubicaciones->random()->id,
            ]);

    }
}
