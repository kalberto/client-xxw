<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeVpcTableMultipleSaldo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

	    Schema::table('usuarios',function (Blueprint $table){
		    $table->dropColumn('saldo_vpc');
	    });
	    Schema::table('vpc',function (Blueprint $table){
		    $table->dropColumn('status');
			$table->dropColumn('comentarios');
	    });
		Schema::create('status_vpc', function (Blueprint $table){
			$table->increments('id');
			$table->string('nome',15);
			$table->string('status',15);
		});
		Schema::create('vpc_has_status',function (Blueprint $table){
			$table->increments('id');
			$table->integer('vpc_id')->unsigned();
			$table->foreign('vpc_id')->references('id')->on('vpc');
			$table->integer('status_id')->unsigned();
			$table->foreign('status_id')->references('id')->on('status_vpc');
			$table->text('comentarios')->nullable();
			$table->timestamp('created_at');
		});
	    Schema::create('saldo_vpc_usuario',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('documento',14)->nullable();
		    $table->foreign('documento')->references('documento')->on('usuarios');
		    $table->integer('ano')->unsigned();
		    $table->integer('mes')->unsigned();
		    $table->double('saldo_vpc',15,2)->nullable();
		    $table->date('data_validade');
	    });
		Schema::create('saldo_vpc_utilizado',function (Blueprint $table){
			$table->increments('id');
			$table->double('utilizado',15,2)->nullable();
			$table->double('provisionado',15,2)->nullable();
			$table->integer('saldo_id')->unsigned();
			$table->foreign('saldo_id')->references('id')->on('saldo_vpc_usuario');
			$table->integer('vpc_id')->unsigned();
			$table->foreign('vpc_id')->references('id')->on('vpc');
			$table->date('created_at');
		});
		Schema::create('arquivos_vpc', function (Blueprint $table){
			$table->increments('id');
			$table->string('file',250);
			$table->string('nome_original',250);
			$table->integer('vpc_id')->unsigned();
			$table->foreign('vpc_id')->references('id')->on('vpc');
		});
	    Schema::create('comprovantes_vpc', function (Blueprint $table){
		    $table->increments('id');
		    $table->string('file',250);
		    $table->string('nome_original',250);
		    $table->integer('vpc_id')->unsigned();
		    $table->foreign('vpc_id')->references('id')->on('vpc');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIfExists('comprovantes_vpc');
		Schema::dropIfExists('arquivos_vpc');
		Schema::dropIfExists('saldo_vpc_utilizado');
		Schema::dropIfExists('saldo_vpc_usuario');
		Schema::dropIfExists('vpc_has_status');
		Schema::dropIfExists('status_vpc');
	    Schema::table('vpc',function (Blueprint $table){
		    $table->double('saldo_vpc',15,2)->nullable();
	    });
	    Schema::table('usuarios',function (Blueprint $table){
		    $table->double('saldo_vpc',15,2)->nullable();
	    });
    }
}
