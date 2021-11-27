<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVpcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('assuntos_vpc', function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('nome',150);
		    $table->float('porcentagem',5,2);
		    $table->longText('campos')->nullable();
	    });
        Schema::create('vpc', function (Blueprint $table) {
            $table->increments('id');
	        $table->string('documento',14)->nullable();
	        $table->foreign('documento')->references('documento')->on('usuarios');
	        $table->integer('assunto_vpc_id')->unsigned()->nullable();
	        $table->foreign('assunto_vpc_id')->references('id')->on('assuntos_vpc');
	        $table->longText('dados')->nullable();
	        $table->string('status',10);
	        $table->text('comentarios')->nullable();
            $table->timestamps();
        });
	    Schema::table('usuarios',function (Blueprint $table){
		    $table->double('saldo_vpc',15,2)->nullable();
		    $table->boolean('vpc_disponivel')->default(false);
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('usuarios',function (Blueprint $table){
		    $table->dropColumn('saldo_vpc');
		    $table->dropColumn('vpc_disponivel');
	    });
        Schema::dropIfExists('vpc');
	    Schema::dropIfExists('assuntos_vpc');
    }
}
