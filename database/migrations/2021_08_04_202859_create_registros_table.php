<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tabla_id');
            $table->integer('fila');
            $table->integer('columna');
            $table->longText('expresion');
            $table->string('colorfondocelda');
            $table->string('alineacion');
            $table->timestamps();

            $table->foreign('tabla_id')->references('id')->on('tablas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registros');
    }
}
