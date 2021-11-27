<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class PasswordReset extends Model
{
	public $timestamps = false;
	public $incrementing = false;
	protected $primaryKey = 'email';
	protected $table = 'password_resets';
	protected $fillable = ['email', 'token', 'created_at'];

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
		$data['email'] = $this->email;
		$this->fill($data);
		$this->save();
		return $this;
	}

	public static function findByEmail($email)
	{
		$pass_reset = PasswordReset::where('email', $email)->limit(1)->first();
		return $pass_reset;
	}

	public static function findByToken($token)
	{
		$pass_reset = PasswordReset::where('token', $token)->limit(1)->first();
		return $pass_reset;
	}
}
