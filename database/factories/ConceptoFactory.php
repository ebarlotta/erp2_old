<?php

namespace Database\Factories;

use App\Models\Concepto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConceptoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Concepto::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=> $this->faker->name(),
            'orden'=> $this->faker->randomDigit,
            'unidad'=> $this->faker->randomElement(['unidaddia','unidadmes','unidadaño']),
            'haber'=> $this->faker->randomElement([0,0,111]),
            'rem'=> $this->faker->randomElement([0,0,0,0,222]),
            'norem'=> $this->faker->randomElement([0,0,0,0,333]),
            'descuento'=> $this->faker->randomElement([0,0,0,0,444]),
            'montofijo'=> $this->faker->randomElement([0,0,123]),
            'calculo'=> $this->faker->randomElement(['C','P','B','B1','B2','P*C']),
            'montomaximo'=> $this->faker->randomElement([0,0,0,0,0,0,321]),
        ];
    }
}
