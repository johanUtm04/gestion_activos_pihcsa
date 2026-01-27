<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Procesador>
 */
class ProcesadorFactory extends Factory
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
            'Intel',
            'AMD'
        ]),

        'descripcion_tipo' => $this->faker->randomElement([
            'Intel Core i3 10th Gen',
            'Intel Core i5 10th Gen',
            'Intel Core i7 10th Gen',
            'Intel Core i5 11th Gen',
            'Intel Core i7 11th Gen',
            'Intel Core i5 12th Gen',
            'Intel Core i7 12th Gen',
            'Intel Core i9 12th Gen',
            'AMD Ryzen 3 3200G',
            'AMD Ryzen 5 3600',
            'AMD Ryzen 5 5600X',
            'AMD Ryzen 7 3700X',
            'AMD Ryzen 7 5800X',
            'AMD Ryzen 9 5900X',
            'AMD Ryzen 9 5950X',
        ]),
    ];

    }
}
