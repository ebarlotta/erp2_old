<?php

namespace Database\Factories;

use App\Models\Empresa;
use App\Models\Tabla;
use Illuminate\Database\Eloquent\Factories\Factory;

class TablaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tabla::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        [
            // 'name' => 'pepep', //$this->faker->name(),
            // 'encabezadocolumna'=>$this->faker->name(),
            // 'ordenarporcampo'=>$this->faker->name(),
            // 'cantidadfila'=>$this->faker->randomElement([1, 2, 3]),
            // 'cantidadcolumna'=>$this->faker->randomElement([1, 2, 3]),
            // 'empresa_id'=>Empresa::inRandomOrder()->value('id'),        
        ];
    }
}
