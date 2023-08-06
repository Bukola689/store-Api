<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->realText(15),
            'caption' => $this->faker->realText(30),
            'details' => $this->faker->paragraph(3, true),
        ];
    }
}
