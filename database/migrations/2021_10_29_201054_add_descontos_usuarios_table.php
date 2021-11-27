<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescontosUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('usuarios',function (Blueprint $table){
		    $table->string('desconto',10)->nullable()->after('nivel_id');
		    $table->string('vpc',10)->nullable()->after('desconto');
		    $table->string('rebate',10)->nullable()->after('vpc');
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
		    $table->dropColumn('desconto');
		    $table->dropColumn('vpc');
		    $table->dropColumn('rebate');
	    });
    }
}
