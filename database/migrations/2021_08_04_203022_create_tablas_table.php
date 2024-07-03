<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tablas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('encabezadocolumna');
            $table->string('ordenarporcampo');
            $table->integer('cantidadfila');
            $table->integer('cantidadcolumna');
            $table->unsignedBigInteger('empresa_id');
            
            $table->timestamps();
            
            $table->foreign('empresa_id')->references('id')->on('empresas');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tablas');
    }
}
