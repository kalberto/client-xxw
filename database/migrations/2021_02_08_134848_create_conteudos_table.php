<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConteudosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conteudos', function (Blueprint $table) {
            $table->increments('id');
	        $table->string('nome',150);
	        $table->string('slug',150);
			$table->string('titulo',30)->nullable();
	        $table->longText('texto')->nullable();
			$table->boolean('ativo')->nullable()->default(false);
	        // $table->integer('media_id')->unsigned()->nullable();
	        // $table->foreign('media_id')->references('id')->on('media');
	        $table->integer('categoria_id')->unsigned()->nullable();
	        $table->foreign('categoria_id')->references('id')->on('categorias');
	        $table->boolean('evento')->nullable()->default(false);
	        $table->string('link_transmissao',255)->nullable();
	        $table->string('link_google_calendar',255)->nullable();
	        $table->date('data_inicio')->nullable();
	        $table->date('data_fim')->nullable();
	        $table->string('autor',50)->nullable();
	        $table->timestamps();
			$table->softDeletes();
        });
        Schema::create('conteudos_relacionados',function (Blueprint $table) {
	        $table->integer('conteudo_id')->unsigned();
	        $table->foreign('conteudo_id')->references('id')->on('conteudos');
	        $table->integer('relacionado_id')->unsigned();
	        $table->foreign('relacionado_id')->references('id')->on('conteudos');
        });
	    Schema::create('eventos_relacionados',function (Blueprint $table) {
		    $table->integer('conteudo_id')->unsigned();
		    $table->foreign('conteudo_id')->references('id')->on('conteudos');
		    $table->integer('relacionado_id')->unsigned();
		    $table->foreign('relacionado_id')->references('id')->on('conteudos');
	    });
	    // Schema::create('conteudo_medias', function (Blueprint $table) {
		//     $table->increments('id');
		//     $table->boolean('video')->nullable();
		//     $table->boolean('video_is_link')->nullable();
		//     $table->string('video_link',255)->nullable();
		//     $table->integer('conteudo_id')->unsigned();
		//     $table->foreign('conteudo_id')->references('id')->on('conteudos');
		//     $table->integer('media_id')->unsigned()->nullable();
		//     $table->foreign('media_id')->references('id')->on('media');
	    // });
	    Schema::create('listas_rsvp', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('conteudo_id')->unsigned();
		    $table->foreign('conteudo_id')->references('id')->on('conteudos');
	    });
	    Schema::create('listas_rsvp_perfis', function (Blueprint $table){
		    $table->integer('lista_rsvp_id')->unsigned();
		    $table->foreign('lista_rsvp_id')->references('id')->on('listas_rsvp');
		    $table->integer('perfil_id')->unsigned();
		    $table->foreign('perfil_id')->references('id')->on('perfis_usuarios');
	    });
	    Schema::create('listas_rsvp_niveis', function (Blueprint $table){
		    $table->integer('lista_rsvp_id')->unsigned();
		    $table->foreign('lista_rsvp_id')->references('id')->on('listas_rsvp');
		    $table->integer('nivel_id')->unsigned();
		    $table->foreign('nivel_id')->references('id')->on('niveis');
	    });
	    Schema::create('convidados_listas_rsvp', function (Blueprint $table){
		    $table->integer('lista_rsvp_id')->unsigned();
		    $table->foreign('lista_rsvp_id')->references('id')->on('listas_rsvp');
		    $table->string('documento',14);
		    $table->foreign('documento')->references('documento')->on('usuarios');
		    $table->boolean('confirmado')->nullable()->default(false);
			$table->boolean('email_enviado')->nullable()->default(false);	
			$table->date('data_envio_email')->nullable();
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('convidados_listas_rsvp');
        Schema::dropIfExists('listas_rsvp_niveis');
        Schema::dropIfExists('listas_rsvp_perfis');
        Schema::dropIfExists('listas_rsvp');
        // Schema::dropIfExists('conteudo_medias');
        Schema::dropIfExists('eventos_relacionados');
        Schema::dropIfExists('conteudos_relacionados');
        Schema::dropIfExists('conteudos');
    }
}
