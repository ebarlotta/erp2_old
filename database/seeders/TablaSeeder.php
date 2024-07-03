<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TablaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tablas')->insert(['name'=>'Compras','encabezadocolumna'=>'columna','ordenarporcampo'=>'pepe','cantidadfila'=>3,'cantidadcolumna'=>2,'empresa_id'=>1]);
        DB::table('tablas')->insert(['name'=>'Ventas','encabezadocolumna'=>'columna','ordenarporcampo'=>'pepe','cantidadfila'=>3,'cantidadcolumna'=>5,'empresa_id'=>1]);
        DB::table('tablas')->insert(['name'=>'Paretto','encabezadocolumna'=>'columna','ordenarporcampo'=>'pepe','cantidadfila'=>3,'cantidadcolumna'=>5,'empresa_id'=>1]);
    }
}
