<?php

namespace App\Model\Canais;

use Illuminate\Database\Eloquent\Model;

class Assunto extends Model
{
    protected $table = 'assuntos';

	public $timestamps = false;

	protected $fillable = [
		'assunto',
	];
}
