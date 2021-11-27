<?php

namespace App\Model;

use App\Helpers\MediaHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Lakshmaji\Thumbnail\Thumbnail;
use Mockery\Exception;
use App\Http\Traits\AdministradorModelLog;
use App\Helpers\VariableHelper;
use Carbon\Carbon;

class Media extends Model
{
	protected $table = 'media';

	use AdministradorModelLog;

	protected $fillable = [
		'file','thumbnail','viewport','temp','media_root_id','tipo',
		'conteudo_id','data_ordenacao','legenda','nome','video_is_link',
		'video_link','thumb'
	];
	protected $hidden = ['id','media_root_id','created_at','updated_at'];
	protected $casts = [
		'ativo' => 'boolean',
	];

	protected $appends = ['url_media'];

	public function getDataOrdenacaoAttribute($value)
	{
		VariableHelper::convertDateFormat($value,"Y-m-d H:i:s", "Y-m-d");
		return $value;
	}

	public function setVideoIsLinkAttribute($value){
		VariableHelper::convert_string_bool($value);
		$this->attributes['video_is_link'] = $value;
	}

	public function setThumbAttribute($value){
		VariableHelper::convert_string_bool($value);
		$this->attributes['thumb'] = $value;
	}

	public function getUrlMediaAttribute($value){
		if (isset($this->media_root))
			return url($this->media_root->path . $this->attributes['file']);
		return '';
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function media_root(){
		return $this->belongsTo('App\Model\MediaRoot');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function noticias(){
		return $this->belongsToMany('App\Model\Noticia','noticia_has_media','media_id','noticia_id')->withPivot('nome','legenda','destaque','order')->using('App\Model\NoticiaMedia');
	}

	public function conteudo()
	{
		return $this->belongsTo('App\Model\Conteudo', 'conteudo_id');
	}

	public function make_real($legenda = null){
		try{
			if($this->temp) {
				$this->temp = false;
				if(isset($legenda))
					$this->legenda = $legenda;
				$media_root = $this->media_root()->first();
				$path       = $media_root->path;
				MediaHelper::move_file( $path . 'temp/', $path, $this->nome, null );
				$resizes = $media_root->media_resizes()->get();
				foreach ( $resizes as $resize ) {
					$comp_path = $media_root->path . $resize->path;
					MediaHelper::move_file( $comp_path . 'temp/', $comp_path, $this->nome, null );
				}
				$this->save();
			}
			else{
				if(isset($this->nome_temp)){
					if(isset($legenda))
						$this->legenda = $legenda;
					$media_root = $this->media_root()->first();
					$path       = $media_root->path;
					MediaHelper::delete_file($path,$this->nome);
					MediaHelper::move_file( $path . 'temp/', $path, $this->nome_temp, null );
					$resizes = $media_root->media_resizes()->get();
					foreach ( $resizes as $resize ) {
						$comp_path = $media_root->path . $resize->path;
						MediaHelper::delete_file($comp_path,$this->nome);
						MediaHelper::move_file( $comp_path . 'temp/', $comp_path, $this->nome_temp, null );
					}
					$this->nome = $this->nome_temp;
					$this->nome_temp = null;
					$this->save();
				}
			}
			return true;

		}catch (Exception $exception){
			return false;
		}
	}

	public function get(){
		return $this->with(['conteudo','media_root.media_resizes']);
	}

	public static function getMediaWithRoot($id){
		$media = Media::with('media_root')->find($id);
		if($media->temp){
			$media->media_root->path .= 'temp/';
		}
		return $media;
	}

	public static function storeVideoLink($data, $ip){
		$registro = new self;
		$registro->fill($data);
		$registro['media_root_id'] = 2;
		$registro['file'] = 'media-link-'.str_replace(':','-',str_replace(' ','-',Carbon::now()));
		$registro['nome'] = 'video-link-'.str_replace(':','-',str_replace(' ','-',Carbon::now()));
		$registro->save();
		$registro->saveLog($ip,'insert',$data);
		return (object)(['saved'=> true,'url'=>'admin/assets']);
	}

	public function store($data, $ip){
		if(isset($data['file']) && method_exists($data['file'], 'getMimeType')){
			$sub_str = substr($data['file']->getMimeType(), 0, 5);
			if($sub_str == 'image') {
				$data['tipo'] = 1;
				if(isset($data['alias']) && ($media_root  = MediaRoot::where( 'alias', $data['alias'] )->first()) != null) {
					$resizes = $media_root->media_resizes()->get();
					$data['nome'] = (isset($data['nome']) ? $data['nome'] : str_replace(".".$data['file']->getClientOriginalExtension(),'',$data['file']->getClientOriginalName()));
					if ( ! file_exists( $media_root->path ) )
						mkdir( $media_root->path, 0755,true );
					if(isset($data['thumb']))
						$data['file'] = MediaHelper::upload($data['file'], $media_root->path, $data['nome'],$data['thumb']);
					else
						$data['file'] = MediaHelper::upload($data['file'], $media_root->path, $data['nome']);
					foreach ($resizes as $resize){
						$new_path = $media_root->path.$resize->path;
						if ( ! file_exists( $new_path ) )
							mkdir( $new_path, 0755,true );
						if ( MediaHelper::copy_file( $media_root->path, $new_path, $data['file'], $data['file'] ) ) {
							$options = array(
								'width'  => $resize->width,
								'height' => $resize->height
							);
							MediaHelper::resize_image( $data['file'], $new_path, $options );
						}
					}
					$media = new Media();
					$media->fill($data);
					$media->media_root()->associate($media_root);
					if($media->save()){
						$data_log = array(
							'administrador_id' => Auth::user()->id,
							'registro_id' => $media->id,
							'tabela' => 'media',
							'tipo' => 'insert',
							'ip' => $ip
						);
						LogAdministrador::store($data_log);
						return (object)(['saved'=> true,'url'=>'admin/assets']);
					}else{
						MediaHelper::delete_file( $media_root->path, $data['nome'] );
						foreach ( $resizes as $resize ) {
							$new_path = $media_root->path . $resize->path;
							MediaHelper::delete_file( $new_path, $data['nome'] );
						}
						return (object)(['saved' => false,'error' => 'Ocorreu um erro ao salvar no DB!']);
					}
				}else{
					return (object)(['saved' => false,'error' => 'Não foi possível encontrar esse alias!']);
				}
			}elseif ($sub_str == 'video'){
				$data['tipo'] = 2;
				if(isset($data['alias']) && ($media_root  = MediaRoot::where( 'alias', $data['alias'] )->first()) != null){
					$data['nome'] = (isset($data['nome']) ? $data['nome'] : str_replace(".".$data['file']->getClientOriginalExtension(),'-',$data['file']->getClientOriginalName()));
					if ( ! file_exists( $media_root->path ) )
						mkdir( $media_root->path, 0755,true );
					if ( ! file_exists( $media_root->path.'/thumb/' ) )
						mkdir( $media_root->path.'/thumb/', 0777,true );
					$data['file'] = MediaHelper::upload($data['file'], $media_root->path, $data['nome']);
					$data['thumbnail'] = 'thumb_'.Str::slug($data['nome']).date('y-m-d-his').'.jpg';
					$thumbnail = new Thumbnail();
					$status = $thumbnail->getThumbnail(str_replace('\\','/',base_path('public/'.$media_root->path.$data['file'])),str_replace('\\','/',base_path('public/'.$media_root->path.'thumb')),$data['thumbnail'],01);
					if(!$status)
						$data['thumbnail'] = null;
					$media = new Media();
					$media->fill($data);
					$media->media_root()->associate($media_root);
					if($media->save()){
						$data_log = array(
							'administrador_id' => Auth::user()->id,
							'registro_id' => $media->id,
							'tabela' => 'media',
							'tipo' => 'insert',
							'ip' => $ip
						);
						LogAdministrador::store($data_log);
						return (object)(['saved'=> true,'url'=>'/admin/assets']);
					}else{
						MediaHelper::delete_file( $media_root->path, $data['nome'] );
						$new_path = $media_root->path . 'thumb/';
						MediaHelper::delete_file( $new_path, $data['thumbnail'] );
						return (object)(['saved' => false,'error' => 'Ocorreu um erro ao salvar no DB!']);
					}
				}else{
					return (object)(['saved' => false,'error' => 'Não foi possível encontrar esse alias!']);
				}
			}else{
				return (object)(['saved' => false,'error' => 'O arquivo não é um vídeo nem uma imagem!']);
			}
		}else{
			return (object)(['saved' => false,'error' => 'O arquivo não foi enviado!']);
		}
	}

	public function edit($id,$data, $ip){
		if(isset($data['file']) && method_exists($data['file'], 'getMimeType')){
			if(isset($data['alias']) && ($media_root  = MediaRoot::where( 'alias', $data['alias'] )->first()) != null){
				$sub_str = substr($data['file']->getMimeType(), 0, 5);
				$media = Media::find($id);
				MediaHelper::delete_file( $media_root->path, $media->file);
				if($media->tipo == '1'){
					$resizes_old = $media->media_root->media_resizes;
					foreach ( $resizes_old as $resize ) {
						$new_path = $media_root->path . $resize->path;
						MediaHelper::delete_file( $new_path, $media->file);
					}
				}else{
					$new_path = $media_root->path . 'thumb/';
					MediaHelper::delete_file( $new_path, $media->thumbnail);
				}
				if($sub_str == 'image'){
					$resizes = $media_root->media_resizes()->get();
					$data['nome'] = (isset($data['nome']) ? $data['nome'] : str_replace(".".$data['file']->getClientOriginalExtension(),'-',$data['file']->getClientOriginalName()));
					if ( ! file_exists( $media_root->path ) )
						mkdir( $media_root->path, 0755,true );
					$data['file'] = MediaHelper::upload($data['file'], $media_root->path, $data['nome']);
					foreach ($resizes as $resize){
						$new_path = $media_root->path.$resize->path;
						if ( ! file_exists( $new_path ) )
							mkdir( $new_path, 0755,true );
						if ( MediaHelper::copy_file( $media_root->path, $new_path, $data['file'], $data['file'] ) ) {
							$options = array(
								'width'  => $resize->width,
								'height' => $resize->height
							);
							MediaHelper::resize_image( $data['file'], $new_path, $options );
						}
					}
					$media->fill($data);
					$media->media_root()->associate($media_root);
					if($media->save()){
						$data_log = array(
							'administrador_id' => Auth::user()->id,
							'registro_id' => $media->id,
							'tabela' => 'media',
							'tipo' => 'update',
							'ip' => $ip
						);
						LogAdministrador::store($data_log);
						return (object)(['saved'=> true,'url'=>'admin/assets']);
					}else{
						MediaHelper::delete_file( $media_root->path, $data['nome'] );
						foreach ( $resizes as $resize ) {
							$new_path = $media_root->path . $resize->path;
							MediaHelper::delete_file( $new_path, $data['nome'] );
						}
						return (object)(['saved' => false,'error' => 'Ocorreu um erro ao salvar no DB!']);
					}
				}elseif($sub_str == 'video'){
					$data['nome'] = (isset($data['nome']) ? $data['nome'] : str_replace(".".$data['file']->getClientOriginalExtension(),'-',$data['file']->getClientOriginalName()));
					if ( ! file_exists( $media_root->path ) )
						mkdir( $media_root->path, 0777,true );
					if ( ! file_exists( $media_root->path.'thumb/' ) )
						mkdir( $media_root->path.'thumb/', 0777,true );
					$data['file'] = MediaHelper::upload($data['file'], $media_root->path, $data['nome']);
					$data['thumbnail'] = 'thumb_'.Str::slug($data['nome']).date('y-m-d-his').'.jpg';
					$thumbnail = new Thumbnail();
					$status = $thumbnail->getThumbnail(str_replace('\\','/',base_path('public/'.$media_root->path.$data['file'])),str_replace('\\','/',base_path('public/'.$media_root->path.'thumb')),$data['thumbnail'],01);
					if(!$status)
						$data['thumbnail'] = null;
					$media->fill($data);
					$media->media_root()->associate($media_root);
					if($media->save()){
						$data_log = array(
							'administrador_id' => Auth::user()->id,
							'registro_id' => $media->id,
							'tabela' => 'media',
							'tipo' => 'update',
							'ip' => $ip
						);
						LogAdministrador::store($data_log);
						return (object)(['saved'=> true,'url'=>'/admin/assets']);
					}else{
						MediaHelper::delete_file( $media_root->path, $data['nome'] );
						$new_path = $media_root->path . 'thumb/';
						MediaHelper::delete_file( $new_path, $data['thumbnail'] );
						return (object)(['saved' => false,'error' => 'Ocorreu um erro ao salvar no DB!']);
					}
				}else{
					return (object)(['saved' => false,'error' => 'O arquivo não é um vídeo nem uma imagem!']);
				}
			}else{
				return (object)(['saved' => false,'error' => 'Não foi possível encontrar esse alias!']);
			}
		}else{
			$media = self::find($id);
			$to_save = [
				'nome' => isset($data['nome']) ? $data['nome'] : '',
				'legenda' => isset($data['legenda']) ? $data['legenda'] : ''
			];
			$media->fill($to_save);
			if($media->save())
			{
				$data_log = array(
					'administrador_id' => Auth::user()->id,
					'registro_id' => $media->id,
					'tabela' => 'media',
					'tipo' => 'insert',
					'ip' => $ip
				);
				LogAdministrador::store($data_log);
				return (object)(['saved'=> true,'url'=>'/admin/assets']);
			}else{
				return (object)(['saved' => false,'error' => 'Ocorreu um erro ao salvar no DB!']);
			}
		}
	}

	public function delete_registro($id, $ip){
		$media = Media::find($id);
		$caminhos = [];
		$arquivos = [];
		$caminhos[] = $media->media_root->path;
		$arquivos[] = $media->file;
		if($media->tipo == '1'){
			$resizes_old = $media->media_root->media_resizes;
			foreach ( $resizes_old as $resize ) {
				$new_path = $media->media_root->path . $resize->path;
				$caminhos[] = $new_path;
				$arquivos[] = $media->file;
			}
		}else{
			$new_path = $media->media_root->path . 'thumb/';
			$caminhos[] = $new_path;
			$arquivos[] = $media->file;
		}
		$media->delete();
		$data  = array (
			'administrador_id' => Auth::user()->id,
			'tabela' => 'media',
			'registro_id' => $id,
			'tipo' => 'delete',
			'ip' => $ip
		);
		LogAdministrador::store($data);
		for ($i = 0; $i < sizeof($caminhos);$i++){
			MediaHelper::delete_file( $caminhos[$i], $arquivos[$i]);
		}
		return true;
	}
}
