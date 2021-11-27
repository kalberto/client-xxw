<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ConteudoRelacionado extends Model
{
    protected $table = 'conteudos_relacionados';

    protected $fillable = [
		'conteudo_id','relacionado_id'
	];

    
}