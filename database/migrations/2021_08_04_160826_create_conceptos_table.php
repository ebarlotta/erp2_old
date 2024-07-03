<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConceptosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conceptos', function (Blueprint $table) {
            $table->id();
            $table->integer('orden');
            $table->string('name');
            $table->string('unidad');
            $table->double('haber');
            $table->double('rem');
            $table->double('norem');
            $table->double('descuento');
            $table->double('montofijo');
            $table->string('calculo');
            $table->double('montomaximo');
            $table->integer('activo')->default(true);
            $table->unsignedBigInteger('empresa_id')->default(2);
            
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
        Schema::dropIfExists('conceptos');
    }
}
