<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PasswordResetsUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::dropIfExists('password_resets_usuarios');
	    Schema::create('password_resets_usuarios', function (Blueprint $table) {
		    $table->string('documento')->index()->unique();
		    $table->string('token', 250);
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
	    Schema::dropIfExists('password_resets_usuarios');
    }
}
