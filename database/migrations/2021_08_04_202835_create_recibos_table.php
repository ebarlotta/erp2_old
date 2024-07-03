<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecibosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recibos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->integer('perpago');
            $table->string('lugarpago');
            $table->dateTime('fechapago');
            $table->double('totalhaberes');
            $table->double('noremunetativo');
            $table->double('descuentos');
            $table->integer('perultimaliq');
            $table->dateTime('fechaultliq');
            $table->boolean('estado');

            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('categoriaprofesional_id');
            
            //$table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('categoriaprofesional_id')->references('id')->on('categoriaprofesionals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recibos');
    }
}
