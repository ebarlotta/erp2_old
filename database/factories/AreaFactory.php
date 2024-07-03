<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Area::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'empresa_id' => Empresa::inRandomOrder()->value('id') ?: Empresa::factory(1)->create(),
        ];
    }
}
