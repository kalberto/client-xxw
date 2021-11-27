<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaResize extends Model
{
	use SoftDeletes;

	protected $table = 'media_resize';
	protected $fillable = ['width','height','path','action','media_root_id'];
	protected $hidden = [];

	public $timestamps = false;

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function media_roots(){
		return $this->belongsTo('App\Model\MediaRoot');
	}
}