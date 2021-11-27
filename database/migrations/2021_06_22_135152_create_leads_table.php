<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
	        $table->string('documento',14)->nullable();
	        $table->foreign('documento')->references('documento')->on('usuarios');
	        $table->string('email');
            $table->integer('assunto_id')->unsigned()->nullable();
			$table->foreign('assunto_id')->references('id')->on('assuntos');
            $table->longText('mensagem');
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
        Schema::dropIfExists('leads');
    }
}
