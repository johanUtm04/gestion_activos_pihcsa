<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DiscoDuroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
    return [
        'capacidad' => $this->faker->randomElement([
            '128 GB', '256 GB', '512 GB', '1 TB', '2 TB', '4 TB'
        ]),

        'tipo_hdd_ssd' => $this->faker->randomElement([
            'HDD',
            'SSD SATA',
            'SSD M.2',
            'NVMe'
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
