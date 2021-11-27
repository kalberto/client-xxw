<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddArquivosAdminVpcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('arquivos_admin_vpc', function (Blueprint $table){
		    $table->increments('id');
		    $table->string('file',250);
		    $table->string('nome_original',250);
		    $table->integer('vpc_id')->unsigned();
		    $table->foreign('vpc_id')->references('id')->on('vpc');
		    $table->integer('administrador_id')->unsigned()->nullable();
		    $table->foreign('administrador_id')->references('id')->on('administradores');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('arquivos_admin_vpc');
    }
}
