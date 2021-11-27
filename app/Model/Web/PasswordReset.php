<?php

namespace App\Model\Web;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class PasswordReset extends Model
{
	public $timestamps = false;
	public $incrementing = false;
	protected $primaryKey = 'documento';
	protected $table = 'password_resets_usuarios';
	protected $fillable = ['documento', 'token', 'created_at'];

	public function setDocumentoAttribute($value)
	{
		$this->attributes['documento'] = preg_replace("/[^0-9]/", '', $value);
	}

	public static function store($data)
	{
		$pass_reset = new self;
		$data['created_at'] = Carbon::now();
		$pass_reset->fill($data);
		$pass_reset->save();
		return $pass_reset;
	}

	public function edit($data)
	{
		$data['created_at'] = Carbon::now();
		$data['documento'] = $this->documento;
		$this->fill($data);
		$this->save();
		return $this;
	}

	public static function findByDocumento($documento)
	{
		$pass_reset = PasswordReset::where('documento', $documento)->limit(1)->first();
		return $pass_reset;
	}

	public static function findByToken($token)
	{
		$pass_reset = PasswordReset::where('token', $token)->limit(1)->first();
		return $pass_reset;
	}
}
