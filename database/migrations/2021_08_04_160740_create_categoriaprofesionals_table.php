<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriaprofesionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categoriaprofesionals', function (Blueprint $table) {
            $table->id();
            $table->string('name')->require();
            $table->string('cct');
            $table->string('observacion');
            $table->string('subcategoria');

            $table->double('preciomes');
            $table->double('preciodia');
            $table->double('preciohora');
            $table->double('preciounidad');

            $table->double('basico');
            $table->double('basico1');
            $table->double('basico2');

            $table->double('porcentaje')->default('0');

            $table->boolean('activo');

            $table->unsignedBigInteger('empresa_id');
            
            $table->foreign('empresa_id')->references('id')->on('empresas');

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
        Schema::dropIfExists('categoriaprofesionals');
    }
}