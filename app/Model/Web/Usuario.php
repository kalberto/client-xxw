<?php

namespace App\Model\Web;

use App\Helpers\VariableHelper;
use App\Notifications\Web\AccountResetN;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\Web\PasswordResetN;
use Illuminate\Support\Carbon;

/**
 * @method static Usuario find($documento)
**/
class Usuario extends Authenticatable
{
	use HasApiTokens, Notifiable, SoftDeletes;

	protected $table = 'usuarios';
	protected $guard = "web";
	protected $primaryKey = 'documento';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['data_nascimento','password','contato_responsavel','email','telefone','conta_atualizada','consentimento_lgpd','login_token'];

	protected $hidden = [
		'updated_at','deleted_at'
	];

	protected $casts = [
		'ativo' => 'boolean','documento' => 'string','consentimento_lgpd' => 'boolean'
	];

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['password'];

	protected $appends = ['cidade','nivel','is_distribuidor'];

	protected static function boot() {
		parent::boot();

		static::addGlobalScope('ativos', function (EloquentBuilder $builder) {
			$builder->where('ativo','=',true);
		});
	}

	public function setPasswordAttribute($value) {
		$this->attributes['password'] = bcrypt($value);
	}

	public function getEstadoIdAttribute(){
		if(isset($this->cidade_id))
			return DB::table('cidades')->select('estado_id')->where('id',$this->cidade_id)->pluck('estado_id')->first();
		return '';
	}

	public function getCidadeAttribute(){
		$cidade = DB::table('cidades')->select(DB::raw("CONCAT(cidades.nome,'-',estados.uf) as cidade"))
									  ->join('estados','cidades.estado_id','=','estados.id')
									  ->where('cidades.id','=',$this->cidade_id)
									  ->pluck('cidade')->first();
		return $cidade;
	}

	public function getIsDistribuidorAttribute(){
		$nivel = DB::table('niveis')->join('perfis_usuarios','niveis.perfil_id','=','perfis_usuarios.id')
		           ->where([['niveis.id','=',$this->nivel_id],['perfis_usuarios.nome','=','Distribuidor']])->first();
		if(isset($nivel))
			return 1;
		return 0;
	}

	public function getPerfilIdAttribute(){
		if(isset($this->nivel_id))
			return DB::table('niveis')->select('perfil_id')->where('id',$this->nivel_id)->pluck('perfil_id')->first();
		return '';
	}

	public function getNivelAttribute(){
		$nivel = DB::table('niveis')->select(DB::raw("CONCAT(perfis_usuarios.nome,'/',niveis.nome) as nivel"))
		                            ->join('perfis_usuarios','niveis.perfil_id','=','perfis_usuarios.id')
		                            ->where('niveis.id','=',$this->nivel_id)->pluck('nivel')->first();
		return $nivel;
	}

	public function setDataNascimentoAttribute($value){
		$valor = $value;
		VariableHelper::convertDateFormat($valor, "d/m/Y", "Y-m-d");
		$this->attributes['data_nascimento'] = $valor;
	}

	public function getSaldoVpcAttribute(){
		return SaldoVpc::query()->where([['documento','=',$this->documento],['data_validade','>',date('Y-m-d')]])->orderBy('data_validade')->get();
	}

	public function getSelo(){
		$name = "selo_";
		if($this->is_distribuidor)
			$name .= "distribuidor_";
		else
			$name .= "rnsa_";
		$nivel = DB::table('niveis')->select("niveis.nome")
		           ->where('niveis.id','=',$this->nivel_id)->pluck('nome')->first();
		if($nivel == "RNSA" || $nivel == "Fora" || $nivel == "RNSA PRIME")
			return false;
		else
			$name .= Str::slug($nivel).".png";
		return url("images/selos/".$name);
	}

	public function getOldSelo(){
		$name = "selo_";
		if($this->is_distribuidor)
			$name .= "distribuidor_";
		else
			$name .= "rnsa_";
		$nivel = DB::table('niveis')->select("niveis.nome")
		           ->where('niveis.id','=',$this->old_nivel_id)->pluck('nome')->first();
		if($nivel == "RNSA" || $nivel == "Fora" || $nivel == "RNSA PRIME")
			return false;
		else
			$name .= Str::slug($nivel).".png";
		return url("images/selos/".$name);
	}

	public function getDataNascimentoAttribute($value) {
		VariableHelper::convertDateFormat($value,  "Y-m-d H:i:s", "Y-m-d");
		return $value;
	}

	public static function login($id, $request) {
		$usuario = Usuario::find($id);
		$acesso = new AcessoUsuario();
		$acesso->data = Carbon::now();
		$acesso->ip = $request->ip();
		$acesso->usuario()->associate($usuario);
		$acesso->save();
		return $usuario->save();
	}

	public static function findByEmail($email) {
		$registro = Usuario::where('email', $email)->limit(1)->first();
		return $registro;
	}

	public function cidade() {
		return $this->belongsTo('App\Model\Cidade');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public function get(){
		return $this;
	}

	public static function atualizar($data){
		$registro = self::find($data['documento']);
		$registro->fill($data);
		$registro->conta_atualizada = true;
		$registro->save();
		return $registro;
	}

	public static function show($documento) {
		$model = self::find($documento);
		if (!isset($model))
			return null;
		return $model->append(['estado_id','perfil_id']);
	}

	public function removeShowUpgrade(){
		$this->show_upgrade = false;
		$this->save();
	}

	public function sendResetPasswordNotification($token, $nome) {
		$this->notify(new PasswordResetN($token, $nome));
	}
}
