<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprasProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras__productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('productos_id');
            $table->unsignedBigInteger('comprobantes_id');
            $table->double('precio')->default(0);
            $table->double('cantidad')->default(0);
            $table->unsignedBigInteger('user_id');

            $table->foreign('productos_id')->references('id')->on('productos');
            $table->foreign('comprobantes_id')->references('id')->on('comprobantes');
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
        Schema::dropIfExists('compras__productos');
    }
}
