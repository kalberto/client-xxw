<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\AdminModelLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\VariableHelper;

class Conteudo extends Model
{
    protected $table = 'conteudos';

    use SoftDeletes, AdminModelLog;

    protected $fillable = [
		'ativo','nome','slug','titulo','texto','categoria_id','media_id','evento','link_transmissao','link_google_calendar','data_inicio','data_fim','autor','perfil_id'
	];

	protected $hidden = [
		'created_at', 'updated_at'
    ];

	protected $appends = ['conteudos_relacionados','medias_relacionadas','listas','documentos'];

	public function setAtivoAttribute($value){
		VariableHelper::convert_string_bool($value);
		$this->attributes['ativo'] = $value;
	}


	public function setSlugAttribute($value){
		$this->attributes['slug'] = Str::slug($value);
	}

	public function getDocumentosAttribute(){
		return DB::table('documentos_conteudos')->where('conteudo_id','=',$this->id)->get();
	}

	public function getListasAttribute()
    {
        $listas_rsvp = ListaRsvp::with('perfis','niveis')->where('conteudo_id', $this->id)->get();
		$registros = [];
        foreach($listas_rsvp as $i => $lista_rsvp){
			if(isset($lista_rsvp['niveis']) && isset($lista_rsvp['perfis']) && count($lista_rsvp['perfis']) > 0){
				$niveis_id = [];
				foreach($lista_rsvp['niveis'] as $nivel){
					$niveis_id[] = $nivel['id'];
				}
				$registros[] = [
					'perfil_nome' => $lista_rsvp['perfis'][0]['nome'],
					'perfil_id' => $lista_rsvp['perfis'][0]['id'],
					'niveis_id' => $niveis_id
				];
			}
        }
        return $registros;
    }

	public function getConteudosRelacionadosAttribute()
    {
        $itens = $this->conteudosRelacionados()->pluck('relacionado_id')->toArray();
        return $itens;
    }

	public function getEventosRelacionadosAttribute()
    {
        $itens = $this->eventosRelacionados()->pluck('relacionado_id')->toArray();
        return $itens;
    }

	public function getMediasRelacionadasAttribute()
    {
        $itens = $this->media()->pluck('id')->toArray();
        return $itens;
    }

	public function getMediasConteudoAttribute()
    {
        $itens = $this->medias()->with('media_root')->get();
		$mapped = [];
		foreach($itens as $media) {
			$mapped[] = [
				'id' => $media->id,
				'video' => $media->pivot->video,
				'video_link' => $media->pivot->video_link,
				'video_id_link' => $media->pivot->video_is_link,
				'media' => $media
			];
		}
        return $mapped;
    }

	public function conteudosRelacionados(){
		return $this->belongsToMany('App\Model\Conteudo','conteudos_relacionados','conteudo_id','relacionado_id');
	}

	public function medias(){
		return $this->belongsToMany('App\Model\Media', 'conteudo_medias','conteudo_id','media_id')->withPivot('video','video_is_link','video_link')->using('App\Model\ConteudoMedia');;
	}

	public function eventosRelacionados(){
		return $this->belongsToMany('App\Model\Conteudo','eventos_relacionados','conteudo_id','relacionado_id');
	}

    public function media(){
		return $this->hasMany('App\Model\Media','conteudo_id');
	}

	public function categoria()
	{
		return $this->belongsTo('App\Model\Categoria', 'categoria_id');
	}

    public function get(){
		return $this->where('evento','=',0)->with(['categoria']);
	}

    public static function store($data, $ip){
		$registro = new self;
		$registro->fill($data);
		$registro->save();
		$media_db = $registro->media()->pluck('id')->toArray();
		if(isset($data['medias_relacionadas'])){
			if(count($media_db) > 0){
				foreach($data['medias_relacionadas'] as $media) {
					if (($key = array_search($media, $media_db)) !== false) {
						unset($media_db[$key]);
					}
					$tmp = Media::find($media);
					$registro->media()->save($tmp);
				}
				foreach($media_db as $media) {
					Media::find($media)->update(['conteudo_id' => null]);
				}
			}else {
				foreach($data['medias_relacionadas'] as $media) {
					Media::find($media)->update(['conteudo_id' => $registro->id]);
				}
			}
		}
		if(!empty($data['conteudos_relacionados']))
			$registro->conteudosRelacionados()->sync($data['conteudos_relacionados']);
		$registro->saveLog($ip,'insert',$data);
		if($registro->evento == 0)
			return true;
		else
			return $registro;
	}

	public static function edit($id, $data, $ip){
		$registro = self::find($id);
		$registro->fill($data);
		$registro->save();
		if(count($data['medias_relacionadas']) > 0){
			$media_db = $registro->media()->pluck('id')->toArray();
			if(count($media_db) > 0){
				foreach($data['medias_relacionadas'] as $media) {
					if (($key = array_search($media, $media_db)) !== false) {
						unset($media_db[$key]);
					}
					$tmp = Media::find($media);
					$registro->media()->save($tmp);
				}
			}else {
				foreach($data['medias_relacionadas'] as $media) {
					Media::find($media)->update(['conteudo_id' => $registro->id]);
				}
			}
			foreach($media_db as $media) {
				Media::find($media)->update(['conteudo_id' => null]);
			}
		}
		if(!empty($data['conteudos_relacionados']))
			$registro->conteudosRelacionados()->sync($data['conteudos_relacionados']);
		$registro->saveLog($ip,'update',$data);
		if($registro->evento == 0)
			return true;
		else
			return $registro;
	}

	public function remove($ip){
		$this->saveLog($ip,'delete',[]);
		$this->delete();
	}
}
