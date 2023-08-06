<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $brands = [
            'Gucci',
            'BurBerry',
            'Addidas',
            'Puma',
            'IPhone',
            'Samsung'
        ];

        return [
            'name' => $this->faker->randomElement($brands),
            'details' => $this->faker->sentence,
        ];
    }
}
