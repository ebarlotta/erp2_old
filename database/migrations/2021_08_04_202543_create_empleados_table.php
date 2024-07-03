<?php

use App\Models\Empresa;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();

            $table->integer('legajo');
            $table->string('name');
            $table->string('domicilio');
            $table->bigInteger('dni');
            $table->bigInteger('cuil');
            $table->dateTime('nacimiento');
            $table->dateTime('ingreso');
            $table->string('estadocivil');
            $table->string('tipocontratacion');
            $table->string('regimen');
            $table->string('banco');
            $table->bigInteger('nrocuentabanco');
            $table->bigInteger('telefono');
            $table->boolean('mensualizado');
            $table->boolean('jornalizado');
            $table->boolean('hora');
            $table->boolean('unidad');
            $table->string('seccion');
            $table->boolean('activo');
            $table->dateTime('baja')->nullable();
            $table->unsignedBigInteger('categoriaprofesional_id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('categoriaprofesional_id')->references('id')->on('categoriaprofesionals');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('user_id')->references('id')->on('users');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleados');
    }
}
