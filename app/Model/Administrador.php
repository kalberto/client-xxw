<?php

namespace App\Model;

use App\Helpers\VariableHelper;
use App\Model\AcessoAdministrador;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use App\Notifications\PasswordResetN;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class Administrador extends Authenticatable
{
    use Notifiable;
	use SoftDeletes;

    protected $table = 'administradores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome','sobrenome', 'email', 'password','telefone','celular','ativo','perfil_id','api_token','ultimo_acesso','media_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','api_token','super_user'
    ];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function perfil(){
		return $this->belongsTo('App\Model\Perfil');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function media(){
		return $this->belongsTo('App\Model\Media');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function acessos(){
		return $this->hasMany('App\Model\AcessoAdministrador');
	}

	public function get(){
		return $this->with(['perfil','media.media_root']);
	}

	public static function login($id,$request){
		try{
			$adm = Administrador::find($id);
			//dd($adm);
			$acesso = new AcessoAdministrador();
			$acesso->data = Carbon::now();
			$acesso->ip = $request->ip();
			$acesso->administrador()->associate($adm);
			$acesso->save();
			$adm->ultimo_acesso = Carbon::now();
			$adm->save();
			return true;
		}catch (Exception $e){
			return false;
		}
	}

	public static function findById($id){
		$administrador = Administrador::find($id);
		if($administrador->count() > 0){
			return $administrador;
		}else{
			return false;
		}
	}

	public static function findByEmail($email){
		$administrador = Administrador::where('email', $email)->limit(1)->first();
		return $administrador;
	}

	public static function getAdministrador($id){
		$administrador = Administrador::with('media.media_root')->find($id);
		return $administrador;
	}

	public function editPass($data, $ip){
		$this->password = $data['new_password'];
		$data = array(
			'administrador_id' => Auth::user()->id,
			'registro_id' => $this->id,
			'tabela' => 'administradores',
			'tipo' => 'update',
			'ip' => $ip
		);
		LogAdministrador::store($data);
		return $this->save();
	}
	//TODO PIANARO
	public static function edit($id, $data, $ip)
	{
		$administrador = self::find($id);
		$data['email'] = $administrador->email;
		if(isset($data['telefone'])){
			preg_match_all('!\d+!', $data['telefone'], $matches);
			$data['telefone'] = implode('', $matches[0]);
		}
		if(isset($data['celular'])){
			preg_match_all('!\d+!', $data['celular'], $matches);
			$data['celular'] = implode('', $matches[0]);
		}
		VariableHelper::convert_string_bool($data['ativo']);
		$administrador->fill($data);
		$data_log = array(
			'administrador_id' => Auth::user()->id,
			'registro_id' => $id,
			'tabela' => 'administradores',
			'tipo' => 'update',
			'ip' => $ip
		);
		LogAdministrador::store($data_log);
		return $administrador->save();
	}

	public static function store($data,$ip){
		$administrador = new self;
		if(!isset($data['api_token']))
			$data['api_token'] = Str::random(46).date("YmdHis");
		if(isset($data['telefone'])){
			preg_match_all('!\d+!', $data['telefone'], $matches);
			$data['telefone'] = implode('', $matches[0]);
		}
		if(isset($data['celular'])){
			preg_match_all('!\d+!', $data['celular'], $matches);
			$data['celular'] = implode('', $matches[0]);
		}
		VariableHelper::convert_string_bool($data['ativo']);
		$administrador->fill($data);
		if($administrador->save()){
			$data_log = array(
				'administrador_id' => Auth::user()->id,
				'registro_id' => $administrador->id,
				'tabela' => 'administradores',
				'tipo' => 'insert',
				'ip' => $ip
			);
			LogAdministrador::store($data_log);
			return true;
		}else{
			return false;
		}
	}

	public static function getByResetToken($token){
		$administrador = DB::table('password_resets')->select('administradores.*')
			->join('administradores','password_resets.email','=','administradores.email')
			->where('password_resets.token','=',$token)->limit(1)->first();
		//$pass_reset = PasswordReset::where('token',$token)->limit(1)->fisrt();
		//$administrador = Administrador::where('email', $pass_reset->email)->limit(1)->first();
		return $administrador;
	}

	public function setPasswordAttribute($password)
	{
		$this->attributes['password'] = bcrypt($password);
	}

	public function sendResetPasswordNotification($token,$nome)
	{
		$this->notify(new PasswordResetN($token,$nome));
	}
}
