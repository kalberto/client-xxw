<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaRoot extends Model
{
	use SoftDeletes;

	protected $table = 'media_root';
	protected $fillable = ['alias','path','width','height'];
	protected $hidden = ['id','deleted_at'];

	public $timestamps = false;

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function medias(){
		return $this->hasMany('App\Model\Media');
	}
	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function media_resizes(){
		return $this->hasMany('App\Model\MediaResize');
	}
}