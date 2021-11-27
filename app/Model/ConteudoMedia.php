<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ConteudoMedia extends Pivot {

	protected $table = 'conteudo_medias';
	protected $fillable = ['media_id','conteudo_id','video','video_is_link','video_link'];
}