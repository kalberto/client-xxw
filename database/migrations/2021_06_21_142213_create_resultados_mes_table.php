<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultadosMesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('niveis',function (Blueprint $table){
		    $table->string('desconto',10)->nullable()->after('perfil_id');
		    $table->string('vpc',10)->nullable()->after('desconto');
		    $table->string('rebate',10)->nullable()->after('vpc');
	    });
    	Schema::create('metas_usuario',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('documento',14)->nullable();
		    $table->foreign('documento')->references('documento')->on('usuarios');
		    $table->integer('ano')->unsigned();
		    $table->integer('mes')->unsigned();
		    $table->double('meta_mes',15,2);
		    $table->double('meta_trimestre',15,2);
	    });
        Schema::create('resultados_mes', function (Blueprint $table) {
            $table->increments('id');
	        $table->string('documento',14)->nullable();
	        $table->foreign('documento')->references('documento')->on('usuarios');
	        $table->double('valor_mes',15,2);
	        $table->double('valor_falta_vpc',15,2);
	        $table->double('valor_falta_rebate',15,2);
	        $table->integer('metas_usuario')->unsigned();
	        $table->foreign('metas_usuario')->references('id')->on('metas_usuario');
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
        Schema::dropIfExists('resultados_mes');
        Schema::dropIfExists('metas_usuario');
	    Schema::table('niveis',function (Blueprint $table){
		    $table->dropColumn('desconto');
		    $table->dropColumn('vpc');
		    $table->dropColumn('rebate');
	    });
    }
}
