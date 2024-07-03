<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpresaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Empresa::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'direccion' => $this->faker->address(),
            'cuit' => $this->faker->numberBetween(20000000,40000000),
            'ib' => $this->faker->numberBetween(10000,30000),
            'imagen' => 'Barber.png',
            'establecimiento' => $this->faker->randomElement([0,1]),
            'telefono' => $this->faker->numberBetween(2634000000,2634999999),
            'actividad' => $this->faker->word(10),
            'actividad1' => $this->faker->word(10),
            'menu' => 2,
        ];
    }
}
