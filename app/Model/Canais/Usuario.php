<?php

namespace App\Model\Canais;

use App\Helpers\NivelHelper;
use App\Helpers\VariableHelper;
use App\Http\Traits\AdminModelLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @method static Usuario find($documento)
**/
class Usuario extends Authenticatable
{
	use AdminModelLog, SoftDeletes;

	protected $table = 'usuarios';
	protected $primaryKey = 'documento';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'documento','nome','data_nascimento','nivel_id','razao_social','password','nome_fantasia',
		'cidade_id','contato_responsavel','email','telefone','ativo','login_token','teste','desconto','vpc','rebate'
	];

	protected $hidden = [
		'updated_at','deleted_at','password'
	];

	protected $casts = [
		'ativo' => 'boolean','documento' => 'string','conta_atualizada' => 'boolean'
	];

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['password'];

	protected $appends = ['cidade','nivel'];

	public function setPasswordAttribute($value)
	{
		$this->attributes['password'] = bcrypt($value);
	}

	public function setDocumentoAttribute($value)
	{
		$this->attributes['documento'] = preg_replace("/[^0-9]/", '', $value);
	}

	public function setDataNascimentoAttribute($value)
	{
		$valor = $value;
		VariableHelper::convertDateFormat($valor, "d/m/Y", "Y-m-d");
		$this->attributes['data_nascimento'] = $valor;
	}

	public function setNivelIdAttribute($value){
		if($this->nivel_id != $value){
			$this->attributes['upgrade_nivel'] = NivelHelper::isUpgrade($value,$this->nivel_id);
			$this->attributes['old_nivel_id'] = $this->nivel_id;
			$this->attributes['nivel_id'] = $value;
			$this->attributes['show_upgrade'] = 1;
			$this->attributes['send_email_upgrade'] = 1;
		}
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

	public function getDataNascimentoAttribute($value)
	{
		VariableHelper::convertDateFormat($value,  "Y-m-d H:i:s", "Y-m-d");
		return $value;
	}

	public function cidade()
	{
		return $this->belongsTo('App\Model\Cidade');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Builder|static
	 */
	public function get(){
		return $this;
	}

	public static function store($data,$ip){
		$registro = new self();
		$data['password'] = preg_replace("/[^0-9]/", '', $data['documento']);
		$registro->fill($data);
		$registro->save();
		$registro->saveLog($ip,'insert',$data);
		return $registro;
	}

	public static function edit($data,$ip) {
		$registro = self::find($data['documento']);
		$registro->fill($data);
		$registro->saveLog($ip,'update',$data);
		$registro->save();
		return $registro;
	}

	public static function show($documento)
	{
		$model = self::find($documento);
		if (!isset($model))
			return null;
		return $model->append(['estado_id','perfil_id']);
	}
}
