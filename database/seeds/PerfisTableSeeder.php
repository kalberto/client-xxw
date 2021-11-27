<?php

use App\Model\ModAdmPermissao;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerfisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$perfis = [
    		[
    			'nome' => 'Administrador',
		    ],
		    [
			    'nome' => 'Editor',
			    'modulos' => ['Imagens e Vídeos','Conteúdos','Eventos']
		    ]
	    ];
    	foreach ($perfis as $perfil){

		    $id = DB::table('perfis')->where([['nome','=',$perfil['nome']]])->pluck('id')->first();
		    if(!isset($id)){
			    $id = DB::table('perfis')->insertGetId([
				    'nome'=>$perfil['nome'],
				    'created_at' => Carbon::now()
			    ]);
		    }
		    if(isset($perfil['modulos'])){
			    $modulos = DB::table('modulo_administrador')->whereIn('nome',$perfil['modulos'])->pluck('id')->toArray();
			    $modAdmPermissoes = DB::table('mod_adm_permissao')->whereIn('mod_adm_id',$modulos)->get('id')->toArray();
		    }else{
			    $modAdmPermissoes = ModAdmPermissao::all('id');
		    }
		    foreach($modAdmPermissoes as $perms) {
			    if(DB::table('perfil_mod_adm_permissao')->where([['perfil_id','=',$id],['mod_adm_perm_id','=',$perms->id]])->count() <= 0 ) {
				    DB::table('perfil_mod_adm_permissao')->insert([
					    'perfil_id'=>$id,
					    'mod_adm_perm_id'=>$perms->id
				    ]);
			    }
		    }
	    }
    }
}
