<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedors', function (Blueprint $table) {
            $table->id();
            
            $table->string('name');
            $table->string('direccion');
            $table->bigInteger('cuit');
            $table->bigInteger('telefono');
            $table->string('email');
            $table->unsignedBigInteger('iva_id')->default(1);
            $table->unsignedBigInteger('empresa_id');

            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('iva_id')->references('id')->on('ivas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proveedors');
    }
}
