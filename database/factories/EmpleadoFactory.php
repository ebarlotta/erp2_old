<?php

namespace Database\Factories;

use App\Models\Categoriaprofesional;
use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Empresa;
use App\Models\User;

class EmpleadoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Empleado::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //$a =$this->faker->randomElements([0,2000000000]);
        return [
            'legajo'=>random_int(0,1000),
            'name'=>$this->faker->name(),
            'domicilio'=>$this->faker->word(),
            'dni'=> $this->faker->numberBetween(20000000,40000000),
            'cuil'=> $this->faker->numberBetween(2000000000,4000000000),
            'nacimiento'=>$this->faker->dateTime(),
            'ingreso'=>$this->faker->dateTime(),
            'estadocivil'=>$this->faker->randomElement(['Casado/a','Soltero/a','Viudo/a','Separado/a']),
            'tipocontratacion'=>$this->faker->randomElement(['A tiempo parcial','Permanente','Por contrato']),
            'regimen'=>$this->faker->randomElement(['General','Jubilación ordinaria','Jubilación docente','Retiro por invalidez','Jubilación de trabajador/a minusválido/a']),
            'banco'=>$this->faker->randomElement(['Macro','Nación','Galicia','Supervielle','Ninguno']),
            'nrocuentabanco'=>$this->faker->numberBetween(200000000,4000000000),
            'telefono'=>$this->faker->numberBetween(2634000000,40000000),
            'mensualizado'=>$this->faker->randomElement([true, false]),
            'jornalizado'=>$this->faker->randomElement([true, false]),
            'hora'=>$this->faker->randomElement([true, false]),
            'unidad'=>$this->faker->randomElement([true, false]),
            'seccion'=>$this->faker->word(),
            'activo'=>$this->faker->randomElement([true, true, true, true, false]),
            'baja'=>$this->faker->randomElement([now(), null]),
            'categoriaprofesional_id'=>Categoriaprofesional::inRandomOrder()->value('id'),
            'empresa_id'=>Empresa::inRandomOrder()->value('id'),
            'user_id'=>User::inRandomOrder()->value('id'),
        ];
    }
}
