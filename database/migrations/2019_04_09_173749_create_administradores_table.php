<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdministradoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administradores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 45);
	        $table->string('sobrenome', 45)->nullable();
            $table->string('email');
            $table->string('password',60);
	        $table->string('api_token',60)->unique();
	        $table->string('telefone',20)->nullable();
	        $table->string('celular',20)->nullable();
	        $table->boolean('ativo')->default(false);
	        $table->integer('perfil_id')->unsigned();
	        $table->foreign('perfil_id')->references('id')->on('perfis');
            $table->rememberToken();
            $table->softDeletes();
            $table->unique(['email', 'deleted_at']);
            $table->timestamps();
	        $table->timestamp('ultimo_acesso')->nullable();
	        $table->boolean('super_user')->default(false)->nullable();
        });
        Schema::create('password_resets', function (Blueprint $table) {
		    $table->string('email')->index()->unique();
		    $table->string('token',250);
		    $table->timestamp('created_at')->nullable();
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('administradores');
    }
}
