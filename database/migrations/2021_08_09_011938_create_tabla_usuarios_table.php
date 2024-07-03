<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablaUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabla_usuarios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tabla_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('tabla_id')->references('id')->on('tablas');
            $table->foreign('user_id')->references('id')->on('users');
            
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
        Schema::dropIfExists('tabla_usuarios');
    }
}
