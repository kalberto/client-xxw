<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosConteudosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_conteudos', function (Blueprint $table) {
            $table->increments('id');
	        $table->string('file',250);
	        $table->string('nome_original',250);
	        $table->string('type',50);
	        $table->integer('conteudo_id')->unsigned()->nullable();
	        $table->foreign('conteudo_id')->references('id')->on('conteudos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documentos_conteudos');
    }
}
