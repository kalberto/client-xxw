<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuloAdministradorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_acoes_administrador',function (Blueprint $table){
		    $table->increments('id');
		    $table->integer('administrador_id')->unsigned()->nullable();
		    $table->foreign('administrador_id')->references('id')->on('administradores');
		    $table->ipAddress('ip')->nullable();
		    $table->string('tipo',40)->nullable();
		    $table->string('tabela',50)->nullable();
		    $table->integer('registro_id')->nullable();
			$table->text('alteracoes')->nullable();
		    $table->timestamps();
		    $table->softDeletes();
        });
        Schema::create('log_acesso_administrador',function (Blueprint $table){
		    $table->increments('id');
		    $table->integer('administrador_id')->unsigned()->nullable();
		    $table->foreign('administrador_id')->references('id')->on('administradores');
		    $table->timestamp('data')->nullable();
		    $table->ipAddress('ip')->nullable();
		    $table->longText('alteracoes')->nullable();
		    $table->softDeletes();
        });
        Schema::create('permissoes',function (Blueprint $table){
	    	$table->increments('id');
	    	$table->string('nome',100);
		    $table->softDeletes();
        });
        Schema::table('permissoes',function (Blueprint $table){
		    $table->integer('required_id')->unsigned()->nullable();
		    $table->foreign('required_id')->references('id')->on('permissoes');
        });
        Schema::create('modulo_administrador', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome',255);
		    $table->string('icone',40)->nullable();
		    $table->string('modulo_list',45);
		    $table->string('modulo_url',45);
		    $table->boolean('obrigatorio')->default(false);
			$table->boolean('menu')->default(true);
			$table->boolean('is_father')->default(false);
		    $table->integer('order')->nullable();
		    $table->softDeletes();
        });
        Schema::table('modulo_administrador',function (Blueprint $table){
		    $table->integer('father')->unsigned()->nullable();
		    $table->foreign('father')->references('id')->on('modulo_administrador');
	    });
	    Schema::create('mod_adm_permissao',function (Blueprint $table){
	    	$table->increments('id');
		    $table->integer('mod_adm_id')->unsigned();
		    $table->foreign('mod_adm_id')->references('id')->on('modulo_administrador');
		    $table->integer('permissao_id')->unsigned();
		    $table->foreign('permissao_id')->references('id')->on('permissoes');
	    });
	    Schema::create('perfil_mod_adm_permissao',function (Blueprint $table){
		    $table->integer('perfil_id')->unsigned();
		    $table->foreign('perfil_id')->references('id')->on('perfis');
		    $table->integer('mod_adm_perm_id')->unsigned();
		    $table->foreign('mod_adm_perm_id')->references('id')->on('mod_adm_permissao');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perfil_mod_adm_permissao');
        Schema::dropIfExists('mod_adm_permissao');
	    Schema::dropIfExists('modulo_administrador');
	    Schema::table('permissoes', function (Blueprint $table) {
		    $table->dropForeign(['required_id']);
		    $table->dropColumn('required_id');
	    });
	    Schema::dropIfExists('permissoes');
	    Schema::dropIfExists('log_acesso_administrador');
	    Schema::dropIfExists('log_acoes_administrador');
    }
}
