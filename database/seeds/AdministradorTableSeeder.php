<?php

use Illuminate\Database\Seeder;
use App\Model\Administrador;

class AdministradorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$administradores = [
    		["nome"=>"Administrador","sobrenome"=>"Master","email"=>"dev@etools.com.br","password"=>"!m9Uo#Q3fx","api_token"=>"cEzbcwHKVfGDftAJYSkyCpZ2spUzM7AMiaNEjG5171DW20YwIDVcQ1MVMeYr","telefone"=>"41888888-8888","perfil_id"=>1,"celular"=>"","ativo" => true, "ultimo_acesso"=>null,'super_user' => true],
	    ];

    	foreach ($administradores as $administrador){
		    if(Administrador::where('email','=',$administrador["email"])->count()){
			    $admin = Administrador::where('email','=',$administrador["email"])->first();
			    $admin->nome = $administrador["nome"];
			    $admin->sobrenome = $administrador["sobrenome"];
			    $admin->email = $administrador["email"];
			    $admin->password = $administrador["password"];
			    $admin->api_token = $administrador["api_token"];
			    $admin->telefone = $administrador["telefone"];
			    $admin->perfil_id = $administrador["perfil_id"];
			    $admin->celular = $administrador["celular"];
			    $admin->ativo = $administrador["ativo"];
			    $admin->ultimo_acesso = $administrador["ultimo_acesso"];
			    if(isset($administrador['super_user']) && $administrador['super_user'])
			        $admin->super_user = true;
			    $admin->save();
			    echo "Usuario de admin atualizado com sucesso. :) \n\r";
		    }else{
			    $admin = new Administrador();
			    $admin->nome = $administrador["nome"];
			    $admin->sobrenome = $administrador["sobrenome"];
			    $admin->email = $administrador["email"];
			    $admin->password = $administrador["password"];
			    $admin->api_token = $administrador["api_token"];
			    $admin->telefone = $administrador["telefone"];
			    $admin->perfil_id = $administrador["perfil_id"];
			    $admin->celular = $administrador["celular"];
			    $admin->ativo = $administrador["ativo"];
			    $admin->ultimo_acesso = $administrador["ultimo_acesso"];
			    if(isset($administrador['super_user']) && $administrador['super_user'])
			        $admin->super_user = true;
			    $admin->save();
			    echo "Usuario de admin criado com sucesso. :) \n\r";
		    }
	    }
    }
}
