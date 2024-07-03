<?php

namespace Database\Factories;

use App\Models\Modulo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuloFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Modulo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // return [
        //     'name'=>$this->faker->name(),
        //     'pagina'=>$this->faker->randomElement(['compras', 'ventas','areas','cuentas','proveedores','clientes','empleados','modulos','empresas']),
        //     'imagen'=>'http://lorempixel.com/400/200/sports/',
        //     'leyenda'=>$this->faker->word(),
        // ];
    }
}
