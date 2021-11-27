<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('media_root',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('alias',45);
			$table->string('path',255);
			$table->integer('width')->nullable();
	        $table->integer('height')->nullable();
		    $table->softDeletes();
	    });
	    Schema::create('media_resize',function (Blueprint $table){
		    $table->increments('id');
		    $table->integer('width');
		    $table->integer('height');
		    $table->string('path',255);
		    $table->string('action',40);
		    $table->integer('media_root_id')->unsigned();
		    $table->foreign('media_root_id')->references('id')->on('media_root');
		    $table->softDeletes();
	    });
        Schema::create('media',function (Blueprint $table){
		    $table->increments('id');
		    $table->string('viewport',5)->nullable();
		    $table->string('file',250);
		    $table->string('thumbnail',150)->nullable();
		    $table->integer('tipo')->nullable();
			$table->string('nome',190)->nullable();
			$table->string('legenda',190)->nullable();
		    $table->integer('media_root_id')->unsigned();
		    $table->foreign('media_root_id')->references('id')->on('media_root');
			$table->integer('conteudo_id')->unsigned()->nullable();
	        $table->foreign('conteudo_id')->references('id')->on('conteudos');
			// $table->boolean('video_thumb')->nullable();
			$table->boolean('thumb')->nullable();
		    $table->boolean('video_is_link')->nullable();
		    $table->string('video_link',255)->nullable();
			$table->timestamp('data_ordenacao')->nullable();
		    $table->timestamps();
	    });
	    Schema::table('administradores', function (Blueprint $table) {
		    $table->integer('media_id')->unsigned()->nullable();
		    $table->foreign('media_id')->references('id')->on('media');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('administradores', function (Blueprint $table) {
	    	$table->dropForeign(['media_id']);
		    $table->dropColumn('media_id');
	    });
        Schema::dropIfExists('media');
	    Schema::dropIfExists('media_resize');
	    Schema::dropIfExists('media_root');
    }
}
