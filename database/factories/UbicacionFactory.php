<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ubicacion>
 */
class UbicacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
    return [
        'nombre' => $this->faker->randomElement([
            'Sucursal Centro',
            'Sucursal Norte',
            'Sucursal Sur',
            'Sucursal Oriente',
            'Sucursal Poniente',
            'Sucursal Plaza',
            'Sucursal Principal',
            'Sucursal Industrial'
        ]),

        'codigo' => strtoupper($this->faker->bothify('SUC-###')),
    ];
    }
}
