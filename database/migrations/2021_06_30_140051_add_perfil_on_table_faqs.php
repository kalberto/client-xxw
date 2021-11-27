<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPerfilOnTableFaqs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('faqs',function (Blueprint $table){
		    $table->integer('perfil_id')->unsigned()->nullable();
		    $table->foreign('perfil_id')->references('id')->on('perfis_usuarios');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('faqs',function (Blueprint $table){
	    	$table->dropForeign(['perfil_id']);
		    $table->dropColumn('perfil_id');
	    });
    }
}
