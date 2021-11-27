<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MetaUsuario extends Model
{
    public $timestamps = false;

    protected $table = 'metas_usuario';
	protected $fillable = ['documento','ano','mes','meta_mes','meta_trimestre'];
}
