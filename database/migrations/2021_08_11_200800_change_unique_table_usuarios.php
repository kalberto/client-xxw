<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUniqueTableUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios',function (Blueprint $table){
	        $table->unique(['documento', 'deleted_at']);
        	$table->dropUnique(['documento']);
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
		    $table->unique(['documento']);
		    $table->dropUnique(['documento', 'deleted_at']);
	    });
    }
}
