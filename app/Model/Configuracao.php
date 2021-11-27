<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Configuracao extends Model
{
	protected $table = 'configuracoes';
	protected $fillable = ['nome_app','seo_syfux','tag_manager_id','email_remetente','email_destinatario','telefone','whatsapp',
		'social_facebook','social_twitter','social_instagram','social_linkedin','social_youtube','api_google_maps'];
	protected $hidden = [];
	public $timestamps = false;

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function complementos(){
		return $this->belongsToMany('App\Model\Complemento','configuracoes_has_complemento','configuracao_id','complemento_id');
	}

	public static function getWithComplementos(){
		$configuracao_db = Configuracao::first();
		$configuracao = (object)['tag_manager' => $configuracao_db->tag_manager_id,'seo_sufix' => $configuracao_db->seo_sufix, 'telefone' => $configuracao_db->telefone ];
		$configuracao->meta_tags = DB::table('configuracoes_has_complemento')->select(['complementos.nome','complementos.valor', 'complementos.tipo'])
		                             ->join('complementos','configuracoes_has_complemento.complemento_id','=','complementos.id')
		                             ->where('configuracoes_has_complemento.configuracao_id','=',$configuracao_db->id)->get()->keyBy('nome')->toArray();
		$configuracao->nome_app = $configuracao_db->nome_app;
		return $configuracao;
	}

	/**
	 * @param $data array
	 *
	 * @return boolean
	 */
	public  function edit($data,$ip){
		$this->fill($data);
		$i = 0;
		$size_complementos = isset($data['complementos']) ? sizeof($data['complementos']) : 0;
		$delete_array = [];
		foreach ($this->complementos as $key => $complemento){
			if($size_complementos > $key){
				$complemento->fill($data['complementos'][$key]);
				$complemento->save();
				$i = $key + 1;
			}else{
				$delete_array[] = $complemento->id;
			}
		}
		if($size_complementos > sizeof($this->complementos)){
			for ($x = $i; $x < $size_complementos;$x++){
				$complemento_db = new Complemento();
				$complemento_db->fill($data['complementos'][$x]);
				$complemento_db->save();
				$this->complementos()->attach($complemento_db->id);
			}
		}
		foreach ($delete_array as $item){
			$this->complementos()->detach($item);
			$complemento = Complemento::find($item);
			$complemento->delete();
		}
		$data_log = array(
			'administrador_id' => Auth::user()->id,
			'registro_id' => $this->id,
			'tabela' => 'configuracao',
			'tipo' => 'update',
			'ip' => $ip
		);
		LogAdministrador::store($data_log);
		return $this->save();
	}
}
