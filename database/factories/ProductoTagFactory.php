<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tag_id' => $this->faker->ramdomElement('App\Models\Tag'),
            'valor' => $this->faker->name(),
            'producto_id' => $this->faker->ramdomElement('App\Models\Producto'),
        ];
    }
}
