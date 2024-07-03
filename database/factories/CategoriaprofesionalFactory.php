<?php

namespace Database\Factories;

use App\Models\Categoriaprofesional;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Empresa;

class CategoriaprofesionalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Categoriaprofesional::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'cct'=> $this->faker->word(),
            'observacion'=> $this->faker->word(),
            'subcategoria'=> $this->faker->numberBetween(20000000,40000000),
            'preciomes'=> $this->faker->randomElement([0,0,0,0,100]),
            'preciodia'=> $this->faker->randomElement([0,0,0,0,10]),
            'preciohora'=> $this->faker->randomElement([0,0,0,0,1]),
            'preciounidad'=> $this->faker->randomElement([0,0,0,0,20]),
            'basico'=> $this->faker->randomElement([0,0,0,0,100]),
            'basico1'=> $this->faker->randomElement([0,0,0,0,200]),
            'basico2'=> $this->faker->randomElement([0,0,0,0,300]),
            'porcentaje'=> $this->faker->randomElement([0,0,0,0,0,0,0,10]),
            'activo'=> $this->faker->randomElement([true,true,true,true,false]),

            'empresa_id' => Empresa::inRandomOrder()->value('id') ?: Empresa::factory(1)->create(),
        ];
    }
}
