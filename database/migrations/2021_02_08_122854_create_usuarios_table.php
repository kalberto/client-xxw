<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('perfis_usuarios',function (Blueprint $table) {
	    	$table->increments('id');
		    $table->string('nome', 50);
		    $table->softDeletes();
	    });
	    Schema::create('niveis',function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('nome', 50);
		    $table->integer('perfil_id')->unsigned();
		    $table->foreign('perfil_id')->references('id')->on('perfis_usuarios');
		    $table->softDeletes();
        });
        Schema::create('usuarios', function (Blueprint $table) {
	        $table->string('documento',14)->unique();
			$table->string('nome', 190)->nullable();
			$table->timestamp('data_nascimento')->nullable();
	        $table->string('password', 190);
	        $table->integer('nivel_id')->unsigned();
	        $table->foreign('nivel_id')->references('id')->on('niveis');
	        $table->string('razao_social', 150);
	        $table->string('nome_fantasia', 150);
	        $table->integer('cidade_id')->unsigned()->nullable();
	        $table->foreign('cidade_id')->references('id')->on('cidades');
	        $table->string('contato_responsavel', 150)->nullable();
	        $table->string('email', 150)->nullable();
	        $table->string('telefone', 150)->nullable();
	        $table->boolean('consentimento_lgpd')->default(false);
	        $table->boolean('conta_atualizada')->default(false);
	        $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
	    Schema::create('password_resets_usuarios', function (Blueprint $table) {
		    $table->string('email')->index()->unique();
		    $table->string('token', 250);
		    $table->timestamp('created_at')->nullable();
	    });
	    Schema::create('logs_acessos_usuarios', function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('documento',14)->nullable();
		    $table->foreign('documento')->references('documento')->on('usuarios');
		    $table->timestamp('data')->nullable();
		    $table->ipAddress('ip')->nullable();
		    $table->softDeletes();
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs_acessos_usuarios');
        Schema::dropIfExists('password_resets_usuarios');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('niveis');
        Schema::dropIfExists('perfis_usuarios');
    }
}
