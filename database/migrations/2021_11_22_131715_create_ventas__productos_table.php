<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas__productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('productos_id');
            $table->unsignedBigInteger('ventas_id');
            $table->double('precio')->default(0);
            $table->double('cantidad')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->integer('orden');

            $table->foreign('productos_id')->references('id')->on('productos');
            $table->foreign('ventas_id')->references('id')->on('ventas');
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
        Schema::dropIfExists('ventas__productos');
    }
}
