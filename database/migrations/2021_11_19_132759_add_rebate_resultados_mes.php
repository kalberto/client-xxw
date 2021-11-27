<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRebateResultadosMes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('resultados_mes',function (Blueprint $table){
		    $table->double('rebate_disponivel',15,2);
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('resultados_mes',function (Blueprint $table){
		    $table->dropColumn('rebate_disponivel');
	    });
    }
}
