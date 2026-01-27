<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Monitor>
 */
class MonitorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
    return [

        'marca' => $this->faker->randomElement([
            'Dell', 'HP', 'Samsung', 'Lenovo', 'Acer', 'Asus', 'LG', 'ViewSonic'
        ]),


        'serial' => strtoupper($this->faker->bothify('MON-####-??')),


        'escala_pulgadas' => $this->faker->randomElement([
            19, 21.5, 22, 23, 24, 27, 32
        ]),

        'interface' => $this->faker->randomElement([
            'HDMI',
            'VGA',
            'DVI',
            'DisplayPort',
            'USB-C'
        ]),
    ];
    }
}
