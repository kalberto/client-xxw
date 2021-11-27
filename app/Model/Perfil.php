<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perfil extends Model
{
	use SoftDeletes;

	protected $table = 'perfis';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'nome'
	];

	protected $hidden = [
		'id','updated_at','deleted_at'
	];

	protected $casts = [
		//'status' => 'boolean',
	];

	public static function getLoggedPerfil(){
		return [['nome' => Auth::user()->perfil->nome,'id'=>Auth::user()->perfil->id]];
	}

	public static function getAll(){
		return Perfil::whereNotNull('id')->get()->makeVisible('id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public function get(){
		return $this;
	}

	public static function getWithModPerm($id){
		 $mod_adm_perm = DB::table('perfil_mod_adm_permissao')
			->select('mod_adm_permissao.id')
			->join('mod_adm_permissao','perfil_mod_adm_permissao.mod_adm_perm_id','=','mod_adm_permissao.id')
			->where([['perfil_id','=',$id]])
			->get();
		$perfil = Perfil::find($id);
		 foreach ($mod_adm_perm as $mod){
			 $perfil->attributes['mod_adm_permissao'][$mod->id] = true;
		 }
		return $perfil;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function mod_adm_permissao(){
		return $this->belongsToMany('App\Model\ModAdmPermissao','perfil_mod_adm_permissao','perfil_id','mod_adm_perm_id');
	}

	public function store($data,$ip){
		$perfil = new self();
		$perfil->fill($data);
		$mod_adm_permissao_obrigatorio = DB::table('modulo_administrador')
		                                   ->join('mod_adm_permissao','modulo_administrador.id','=','mod_adm_permissao.mod_adm_id')
		                                   ->where('modulo_administrador.obrigatorio','=','1')
		                                   ->pluck('mod_adm_permissao.id');
		foreach ($mod_adm_permissao_obrigatorio as $item){
			if(!isset($data['mod_adm_permissao'][$item]) || !$data['mod_adm_permissao'][$item] || $data['mod_adm_permissao'][$item] == "false"){
				$data['mod_adm_permissao'][$item] = true;
			}
		}
		$permissions = array();
		foreach ($data['mod_adm_permissao'] as $key => $item){
			if($item == 'true' || (is_bool($item) && $item == true)){
				$required = $this->findRequired($key);
				foreach ($required as $iterator){
					if(!in_array($iterator,$permissions) && isset($iterator)){
						$permissions[] = $iterator;
					}
				}
				if(!in_array($key,$permissions) && isset($key)){
					$permissions[] = $key;
				}
			}
		}
		if($perfil->save()){
			$perfil->mod_adm_permissao()->sync($permissions);
			$data_log = array(
				'administrador_id' => Auth::user()->id,
				'registro_id' => $perfil->id,
				'tabela' => 'Perfil',
				'tipo' => 'create',
				'ip' => $ip
			);
			LogAdministrador::store($data_log);
			return (object)(['saved'=> true,'url'=>'/admin/perfis']);
		}else{
			return (object)(['saved' => false,'error' => 'Ocorreu um erro ao salvar no DB!']);
		}
	}

	public function edit($id, $data, $ip){
		$perfil = self::find($id);
		$perfil->fill($data);
		$mod_adm_permissao_obrigatorio = DB::table('modulo_administrador')
			->join('mod_adm_permissao','modulo_administrador.id','=','mod_adm_permissao.mod_adm_id')
			->where('modulo_administrador.obrigatorio','=','1')
			->pluck('mod_adm_permissao.id');
		foreach ($mod_adm_permissao_obrigatorio as $item){
			if(!isset($data['mod_adm_permissao'][$item]) || !$data['mod_adm_permissao'][$item] || $data['mod_adm_permissao'][$item] == "false"){
				$data['mod_adm_permissao'][$item] = true;
			}
		}
		$permissions = array();
		foreach ($data['mod_adm_permissao'] as $key => $item){
			if($item == 'true' || (is_bool($item) && $item == true)){
				$required = $this->findRequired($key);
				foreach ($required as $iterator){
					if(!in_array($iterator,$permissions) && isset($iterator)){
						$permissions[] = $iterator;
					}
				}
				if(!in_array($key,$permissions) && isset($key)){
					$permissions[] = $key;
				}
			}
		}
		$perfil->mod_adm_permissao()->sync($permissions);
		if($perfil->save()){
			$data_log = array(
				'administrador_id' => Auth::user()->id,
				'registro_id' => $id,
				'tabela' => 'Perfil',
				'tipo' => 'update',
				'ip' => $ip
			);
			LogAdministrador::store($data_log);
			return (object)(['saved'=> true,'url'=>'/admin/perfis']);
		}else{
			return (object)(['saved' => false,'error' => 'Ocorreu um erro ao salvar no DB!']);
		}
	}

	public function getId($id,$mod_id){
		$perm = DB::table('permissoes')->find($id);
		$array = array();
		if(isset($perm->required_id)){
			$ids = $this->getId($perm->required_id,$mod_id);
			foreach ($ids as $id_db){
				$array[] = $id_db;
			}
		}
		$mod = DB::table('mod_adm_permissao')
		         ->where('mod_adm_id','=',$mod_id)
		         ->where('permissao_id','=',$id)
		         ->value('id');
		$array[] = $mod;
		return $array;
	}

	public function findRequired($id){
		$required = DB::table('mod_adm_permissao')
			->select('b.*','mod_adm_permissao.mod_adm_id')
			->join('permissoes as a','mod_adm_permissao.permissao_id', '=','a.id')
			->join('permissoes as b','a.required_id','=','b.id')
			->where('mod_adm_permissao.id','=',$id)
			->first();
		if(isset($required)){
			return $this->getId($required->id, $required->mod_adm_id);
		}else{
			return array();
		}
	}
}
