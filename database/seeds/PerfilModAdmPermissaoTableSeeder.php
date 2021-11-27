<?php

use App\Model\Perfil;
use App\Model\ModAdmPermissao;
use Illuminate\Database\Seeder;

class PerfilModAdmPermissaoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $perfis = Perfil::all(['id','nome']);
        $modAdmPermissoes = ModAdmPermissao::all('id');
        foreach($perfis as $perfil) {
            foreach($modAdmPermissoes as $perms) {
				if(!DB::table('perfil_mod_adm_permissao')->where([['perfil_id','=',$perfil->id],['mod_adm_perm_id','=',$perms->id]])->count()) {
					DB::table('perfil_mod_adm_permissao')->insert([
						'perfil_id'=>$perfil->id,
						'mod_adm_perm_id'=>$perms->id
					]);
					echo "Permissao " . $perms->id . " para o perfil " . $perfil->nome . " criada com sucesso :) \n\r";
				}
            }
        }
    }
}
