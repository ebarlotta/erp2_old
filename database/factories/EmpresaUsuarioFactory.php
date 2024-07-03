<?php

namespace Database\Factories;

use App\Models\EmpresaUsuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Empresa;
use App\Models\User;

class EmpresaUsuarioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmpresaUsuario::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'empresa_id'=>Empresa::inRandomOrder()->value('id'),
            'user_id'=>User::inRandomOrder()->value('id'),
        ];
    }
}
