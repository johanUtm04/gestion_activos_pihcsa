<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Ubicacion;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipo>
 */
class EquipoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
    return [
        // --- Relaciones BELONGS TO ---
        'usuario_id' => User::factory(), 
        'ubicacion_id' => Ubicacion::factory(),

        // --- Datos Propios del Equipo ---
        'marca_equipo' => $this->faker->randomElement(['HP', 'Dell', 'Lenovo']),
        'tipo_equipo' => $this->faker->randomElement(['Desktop', 'Laptop', 'Servidor']),
        'serial' => $this->faker->unique()->bothify('??-########'),
        'sistema_operativo' => $this->faker->randomElement(['Windows 10', 'Windows 11', 'Ubuntu']),
        'valor_inicial' => $this->faker->randomFloat(2, 500, 3500), 
        'fecha_adquisicion' => $this->faker->dateTimeBetween('-5 years', 'now'),
        'vida_util_estimada' => $this->faker->numberBetween(3, 8),
    ];
    }
}
