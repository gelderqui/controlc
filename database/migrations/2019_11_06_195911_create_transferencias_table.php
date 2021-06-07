<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transferencias', function (Blueprint $table) {
                $table->increments('id');
    
                $table->integer('idusuario')->unsigned();
                $table->foreign('idusuario')->references('id')->on('users');
                
                $table->integer('idusuariorecibido')->unsigned()->nullable();
                $table->foreign('idusuariorecibido')->references('id')->on('users');
    
                $table->integer('idbodegasalida')->unsigned();
                $table->foreign('idbodegasalida')->references('id')->on('bodegas');
    
                $table->integer('idbodegaentrada')->unsigned();
                $table->foreign('idbodegaentrada')->references('id')->on('bodegas');
    
                $table->integer('cantidad');
                $table->integer('condicion');
    
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
        Schema::dropIfExists('transferencias');
    }
}
