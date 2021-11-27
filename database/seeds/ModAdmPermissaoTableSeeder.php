<?php

use App\Model\ModAdmPermissao;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Model\ModuloAdministrador;

class ModAdmPermissaoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $modulos = ModuloAdministrador::get();
	    foreach ($modulos as $modulo){
		    $count = 3;
		    if($modulo->nome == "Dashboard")
			    $count = 1;
		    if($modulo->nome == "Páginas" || $modulo->nome == "Configurações" )
			    $count = 2;
		    for ($i = 1; $i <= $count; $i++ ){
			    ModAdmPermissao::firstOrCreate(['mod_adm_id'=>$modulo->id,'permissao_id'=> $i]);
		    }
	    }
    }
}
