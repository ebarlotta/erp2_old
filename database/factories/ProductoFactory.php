<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->name(),
            'descripcion'=>$this->faker->word(),
            'precio_compra'=>100.28,
            'existencia'=>0,
            'stock_minimo'=>0,
            'ruta' => 'sin_imagen.jpg',

            'unidads_id'=>1,
            'categoriaproductos_id'=>1,
            'estados_id'=> 1,
            'proveedor_id'=> 1,
            'empresa_id'=> 1,

            // 'unidads_id'=>$this->faker->ramdomElement([0,1]),
            // 'categoriaproductos_id'=>$this->faker->ramdomElement([0,1]),
            // 'estados_id'=> $this->faker->ramdomElement([0,1]),
            // 'proveedor_id'=> $this->faker->ramdomElement([0,1]),
            // 'empresa_id'=> $this->faker->ramdomElement([0,1]),

            // 'unidads_id'=>$this->faker->ramdomElement('App\Models\Unidad'),
            // 'categoriaproductos_id'=>$this->faker->ramdomElement('App\Models\Categoriaproducto'),
            // 'estados_id'=> $this->faker->ramdomElement('App\Models\Estado'),
            // 'proveedor_id'=> $this->faker->ramdomElement('App\Models\Proveedor'),
            // 'empresa_id'=> $this->faker->ramdomElement('App\Models\Empresa'),
        ];
    }
}
