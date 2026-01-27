<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ram>
 */
class RamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
    return [
        'capacidad_gb' => $this->faker->randomElement([
            4, 8, 16, 32, 64
        ]),

        'clock_mhz' => $this->faker->randomElement([
            2133, 2400, 2666, 3000, 3200, 3600, 4000
        ]),

        'tipo_chz' => $this->faker->randomElement([
            'DDR3',
            'DDR4',
            'DDR5'
        ]),
    ];
    }
}
