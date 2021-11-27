<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ListaRsvp extends Model
{
    protected $table = 'listas_rsvp';
	public $timestamps = false;

    protected $fillable = ['id','contudo_id'];

	// protected $appends = ['listas_rsvp_perfis'];

	public static function getNiveisId(){
		$idListaRsvp = self::first();
		$ids = DB::table('listas_rsvp_niveis')->select('nivel_id')->where('lista_rsvp_id',$idListaRsvp['id'])->get()->pluck('nivel_id');
		return $ids;
	}

	public static function getNiveisIdBy($id){
		$idListaRsvp = self::find($id);
		$ids = DB::table('listas_rsvp_niveis')->select('nivel_id')->where('lista_rsvp_id',$idListaRsvp['id'])->get()->pluck('nivel_id');
		return $ids;
	}

    public function niveis(){
		return $this->belongsToMany('App\Model\Nivel','listas_rsvp_niveis','lista_rsvp_id','nivel_id');
	}

    public function convidados(){
		return $this->belongsToMany('App\Model\Web\Usuario','convidados_listas_rsvp','lista_rsvp_id','documento');
		//return $this->belongsToMany('App\Model\Web\Usuario','convidados_listas_rsvp');
	}

	public static function getPerfilById(){
		$idListaRsvp = self::first();
		$id = DB::table('listas_rsvp_perfis')->where('lista_rsvp_id',$idListaRsvp['id'])->pluck('perfil_id');
		return $id[0];
	}

    public function perfis(){
		return $this->belongsToMany('App\Model\Web\Perfil','listas_rsvp_perfis','lista_rsvp_id','perfil_id');
	}

    public function eventos(){
		return $this->belongsTo('App\Model\Evento');
	}

    // public function perfil()
	// {
	// 	return $this->belongsToMany('App\Model\listas_rsvp_perfis','lista_rsvp_id','nivel_id');
	// }

    // public static function store($data){
	// 	$registro = new self;
	// 	$registro->fill($data);
	// 	$registro->save();
	// 	return true;
	// }

    // many to many niveis
    // many to many perfil
    // many to many usuario (convidadoListaRSVP)
    // one to many evento
}
