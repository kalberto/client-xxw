<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplementosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complementos',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('nome',40);
		    $table->string('valor',250);
		    $table->integer('tipo');
	    });
	    Schema::create('configuracoes_has_complemento',function (Blueprint $table){
		    $table->integer('configuracao_id')->unsigned();
		    $table->foreign('configuracao_id')->references('id')->on('configuracoes');
		    $table->integer('complemento_id')->unsigned();
		    $table->foreign('complemento_id')->references('id')->on('complementos');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIfExists('configuracoes_has_complemento');
        Schema::dropIfExists('complementos');
    }
}
