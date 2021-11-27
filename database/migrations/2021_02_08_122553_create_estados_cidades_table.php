<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstadosCidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('estados', function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('nome', 50);
		    $table->string('slug', 50);
		    $table->string('uf', 2);
	    });
	    Schema::create('cidades', function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('nome', 150);
		    $table->boolean('capital')->default(false);
		    $table->string('seo_slug', 200);
		    $table->integer('estado_id')->unsigned();
		    $table->foreign('estado_id')->references('id')->on('estados');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('cidades');
	    Schema::dropIfExists('estados');
    }
}
