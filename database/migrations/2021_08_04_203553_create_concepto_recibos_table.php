<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConceptoRecibosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concepto_recibos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('concepto_id');
            $table->unsignedBigInteger('recibo_id');
            $table->double('cantidad');

            $table->timestamps();

            $table->foreign('concepto_id')->references('id')->on('conceptos');
            $table->foreign('recibo_id')->references('id')->on('recibos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('concepto_recibos');
    }
}
