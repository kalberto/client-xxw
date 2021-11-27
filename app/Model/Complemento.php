<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Complemento extends Model{
	protected $table = 'complementos';
	public $timestamps = false;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'nome','valor','tipo'
	];
}
