<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTableUserAddOldNivel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('usuarios',function (Blueprint $table){
		    $table->integer('old_nivel_id')->unsigned()->nullable();
		    $table->foreign('old_nivel_id')->references('id')->on('niveis');
			$table->boolean('upgrade_nivel')->nullable();
			$table->boolean('show_upgrade')->default(false)->nullable();
			$table->boolean('send_email_upgrade')->default(false)->nullable();
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
		    $table->dropForeign(['old_nivel_id']);
		    $table->dropColumn('old_nivel_id');
		    $table->dropColumn('upgrade_nivel');
		    $table->dropColumn('show_upgrade');
		    $table->dropColumn('send_email_upgrade');
	    });
    }
}
