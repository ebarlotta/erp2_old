<?php

namespace Database\Factories;

use App\Models\TablaUsuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tabla;
use App\Models\User;

class TablaUsuarioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TablaUsuario::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tabla_id' => Tabla::inRandomOrder()->value('id'),
            'user_id' => User::inRandomOrder()->value('id'),
        ];
    }
}
