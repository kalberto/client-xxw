<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Model\ModuloAdministrador;

class ModuloAdministradorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$modulos = [
			["nome"=>"Dashboard","icone"=>"fa-home","modulo_list"=>"painel.dashboard","modulo_url"=>"admin","obrigatorio"=>true,"order"=>0],
			["nome"=>"Administradores","icone"=>"fa-users","modulo_list"=>"painel.administradores.listar","modulo_url"=>"admin.administradores","obrigatorio"=>false,"order"=>7],
			["nome"=>"Perfis","icone"=>"fa-users","modulo_list"=>"painel.perfis.listar","modulo_url"=>"admin.perfis","obrigatorio"=>false,"order"=>7],
			["nome"=>"Imagens e Vídeos","icone"=>"fa-picture-o","modulo_list"=>"painel.assets.listar","modulo_url"=>"admin.assets","obrigatorio"=>false,"order"=>2],
			["nome"=>"Usuários","icone"=>"fa-users","modulo_list"=>"painel.usuarios.listar","modulo_url"=>"admin.usuarios","obrigatorio"=>false,"order"=>1],
			["nome"=>"Conteúdos","icone"=>"fa-file-text-o","modulo_list"=>"painel.conteudos.listar","modulo_url"=>"admin.conteudos","obrigatorio"=>false,"order"=>3],
			["nome"=>"Eventos","icone"=>"fa-quote-left","modulo_list"=>"painel.eventos.listar","modulo_url"=>"admin.eventos","obrigatorio"=>false,"order"=>5],
			["nome"=>"Categorias","icone"=>"fa-circle","modulo_list"=>"painel.categorias.listar","modulo_url"=>"admin.categorias","obrigatorio"=>false,"order"=>6],
			["nome"=>"Configurações","icone"=>"fa-gear","modulo_list"=>"painel.configuracoes","modulo_url"=>"admin.configuracoes","obrigatorio"=>false,"order"=>9],
			["nome"=>"Faq","icone"=>"fa-question-circle","modulo_list"=>"painel.faqs","modulo_url"=>"admin.faqs","obrigatorio"=>false,"order"=>6],
			["nome"=>"Fale conosco","icone"=>"fa-envelope","modulo_list"=>"painel.leads","modulo_url"=>"admin.leads","obrigatorio"=>false,"order"=>8],
			["nome"=>"Solicitações VPC","icone"=>"fa-envelope","modulo_list"=>"painel.vpc","modulo_url"=>"admin.vpc","obrigatorio"=>false,"order"=>4]
		];
		foreach ($modulos as $modulo){
			if(ModuloAdministrador::where('nome','=',$modulo['nome'])->count()){
				$modAdm = ModuloAdministrador::where('nome','=',$modulo['nome'])->first();
				$modAdm->fill($modulo);
				$modAdm->save();
				echo "Modulo administrador | ",Str::slug($modulo['nome']),". ","Editado"," com sucesso. ;) \n\r";
			}else{
				$modAdm = new ModuloAdministrador();
				$modAdm->nome = $modulo['nome'];
				$modAdm->icone = $modulo['icone'];
				$modAdm->modulo_list = $modulo['modulo_list'];
				$modAdm->modulo_url = $modulo['modulo_url'];
				$modAdm->obrigatorio = $modulo['obrigatorio'];
				$modAdm->order = $modulo['order'];
				$modAdm->save();
				echo "Modulo administrador | ",Str::slug($modulo['nome']),". ","Criado"," com sucesso. ;) \n\r";
			}
		}
    }
}
