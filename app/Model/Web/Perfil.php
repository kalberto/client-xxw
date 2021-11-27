<?php

namespace App\Model\Web;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    public $timestamps = false;

    protected $table = 'perfis_usuarios';
	protected $fillable = ['nome','deleted_at'];

    public function listasRsvp(){
		return $this->belongsToMany('App\Model\ListaRsvp','listas_rsvp');
	}

}
