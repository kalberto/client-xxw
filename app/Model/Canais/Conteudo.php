<?php

namespace App\Model\Canais;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @method static Builder whereNotNull(string $column,string $boolean = 'and')
 * @method static Builder where(string|array|\Closure $column,mixed $operand = null, mixed $value = null, string $boolean = 'and')
 * @method static Conteudo find($id)
 */
class Conteudo extends Model
{
	use SoftDeletes;
    protected $table = 'conteudos';

	protected $appends = ['conteudos_relacionados','medias_relacionadas','thumb','confirmado'];

	protected $hidden = ['categoria_id','created_at','updated_at','deleted_at','medias_relacionadas'];

	public function getThumbAttribute()
	{
		$media = $this->medias()->where('thumb',1)->first();
		if(!isset($media))
			$media = $this->medias()->first();
		if(isset($media))
			return url($media->media_root->path . $media->file);
		else
			return '';
	}

	public function getDocumentosAttribute(){
		$documentos_db = DB::table('documentos_conteudos')->where('conteudo_id','=',$this->id)->get();
		$documentos = [];
		foreach ($documentos_db as $documento){
			$documentos[] = ['file' => url('upload/conteudo-evento/documentos/'.$documento->file),'name' => $documento->nome_original, 'type' => $documento->type];
		}
		return $documentos;
	}

	public function getDataInicioAttribute($value){
		$time = strtotime($value);
		setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
		date_default_timezone_set('America/Sao_Paulo');
		return strftime('%d de %B %Y',$time);
	}

	public function getConfirmadoAttribute(){
		if(!$this->evento)
			return false;
		$user = Auth::user();
		if(!isset($user))
			return false;
		$confirmado = DB::table( 'convidados_listas_rsvp' )->select( ['confirmado'] )
		                ->join( 'listas_rsvp', 'convidados_listas_rsvp.lista_rsvp_id', '=', 'listas_rsvp.id' )
		                ->where( [
			                [ 'listas_rsvp.conteudo_id', '=', $this->id ],
			                [ 'convidados_listas_rsvp.documento', '=', $user->documento ]
		                ] )->pluck('confirmado')->first();
		return $confirmado;
	}

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope('ativos', function (Builder $builder) {
			$builder->where('ativo','=',true);
		});
	}

	public function getConteudosRelacionadosAttribute()
    {
        $itens = $this->conteudosRelacionados()->pluck('relacionado_id')->toArray();
        return $itens;
    }

	public function getMediasRelacionadasAttribute()
    {
        $itens = $this->medias()->pluck('id')->toArray();
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
		return $this->belongsToMany('App\Model\Canais\Conteudo','conteudos_relacionados','conteudo_id','relacionado_id');
	}

	public function medias(){
		return $this->hasMany('App\Model\Media','conteudo_id');
	}

	public function categoria()
	{
		return $this->belongsTo('App\Model\Categoria', 'categoria_id');
	}
}
