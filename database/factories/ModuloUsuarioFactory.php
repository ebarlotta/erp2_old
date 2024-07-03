<?php

namespace Database\Factories;

use App\Models\ModuloUsuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Modulo;

class ModuloUsuarioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ModuloUsuario::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'=>User::inRandomOrder()->value('id'),
            'modulo_id'=>Modulo::inRandomOrder()->value('id'),
        ];
    }
}
