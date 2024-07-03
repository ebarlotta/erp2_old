<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresaModuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Asigna los módulos de básicos a la empresa de prueba
        DB::table('empresa_modulos')->insert(['modulo_id' => '1', 'empresa_id' => 1,]);
        DB::table('empresa_modulos')->insert(['modulo_id' => '2', 'empresa_id' => 1,]);
        DB::table('empresa_modulos')->insert(['modulo_id' => '3', 'empresa_id' => 1,]);
        DB::table('empresa_modulos')->insert(['modulo_id' => '4', 'empresa_id' => 1,]);
        DB::table('empresa_modulos')->insert(['modulo_id' => '5', 'empresa_id' => 1,]);
        DB::table('empresa_modulos')->insert(['modulo_id' => '6', 'empresa_id' => 1,]);
        DB::table('empresa_modulos')->insert(['modulo_id' => '7', 'empresa_id' => 1,]);
    }
}
