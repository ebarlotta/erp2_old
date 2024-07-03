<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('cuil')->default('');
            $table->string('direccion');
            $table->string('email')->nullable();
            $table->bigInteger('telefono');
            $table->unsignedBigInteger('iva_id')->default(1);
            $table->unsignedBigInteger('empresa_id');
            
            $table->timestamps();
            
            $table->foreign('iva_id')->references('id')->on('ivas');
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
        Schema::dropIfExists('clientes');
    }
}
