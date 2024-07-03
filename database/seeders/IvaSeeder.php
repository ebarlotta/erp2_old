<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IvaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('ivas')->truncate();
        DB::table('ivas')->insert(['monto'=>0,'descripcion'=>'Iva 0%','activo'=>true]);
        DB::table('ivas')->insert(['monto'=>10.5,'descripcion'=>'Iva 10,5%','activo'=>true]);
        DB::table('ivas')->insert(['monto'=>21,'descripcion'=>'Iva 21%','activo'=>true]);
        DB::table('ivas')->insert(['monto'=>27,'descripcion'=>'Iva 27%','activo'=>true]);

    }
}
