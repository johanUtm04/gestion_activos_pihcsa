<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Periferico>
 */
class PerifericoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
    return [
        'tipo' => $this->faker->randomElement([
            'Mouse',
            'Teclado',
            'Audífonos',
            'Bocinas',
            'Micrófono',
            'Webcam',
            'Impresora',
            'Scaner'
        ]),

        'marca' => $this->faker->randomElement([
            'Logitech',
            'HP',
            'Dell',
            'Microsoft',
            'Genius',
            'Razer',
            'Corsair',
            'Canon',
            'Epson',
            'Lenovo'
        ]),

        'serial' => strtoupper($this->faker->bothify('PER-####-??')),

        'interface' => $this->faker->randomElement([
            'USB-A',
            'USB-C',
            'Bluetooth',
            'Wireless 2.4GHz',
            'HDMI',
            'DisplayPort'
        ]),
    ];
    }
}
