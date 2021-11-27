<?php

namespace App\Model\Web;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Conteudo extends Model
{
    public $timestamps = false;

    protected $table = 'conteudos';
	protected $fillable = ['nome','slug','titulo','texto','categoria_id','media_id','evento',
    'link_transmissao','link_google_calendar'];

    // public function categoria()
	// {
    //     $registro = DB::table('categorias')->where('id',$this->categoria_id);
	// 	return $this->belongsTo('App\Model\Categoria', 'categoria_id');
	// }
}
