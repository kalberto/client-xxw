<?php

namespace App\Model\Canais;

use App\Helpers\MediaHelper;
use App\Helpers\MonthHelper;
use App\Helpers\VariableHelper;
use App\Helpers\VPCHelper;
use App\Model\Web\SaldoVpc;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @method static VPC find($id)
 **/
class VPC extends Model
{
    protected $table = 'vpc';

	protected $fillable = [
		'status','comentarios',
	];

	protected $appends = ['razao_social','acao','status'];

	public function getRazaoSocialAttribute(){
		$razao_social = DB::table('usuarios')->where('documento','=',$this->documento)->select("razao_social")->pluck('razao_social')->first();
		return $razao_social;
	}

	public function getAcaoAttribute(){
		$acao = DB::table('assuntos_vpc')->where('id','=',$this->assunto_vpc_id)->select("nome")->pluck('nome')->first();
		return $acao;
	}

	public function getDadosAttribute($value){
		if(isset($value)){
			$array = json_decode($value);
			VariableHelper::convertDateFormat($array->data_inicio, 'Y-m-d','d/m/Y');
			VariableHelper::convertDateFormat($array->data_fim, 'Y-m-d','d/m/Y');
			return $array;
		}
		return [];
	}

	public function getCreatedAtAttribute($value){
		VariableHelper::convertDateFormat($value,'Y-m-d H:i:s','d/m/Y');
		return $value;
	}

	public function getUpdatedAtAttribute($value){
		VariableHelper::convertDateFormat($value,'Y-m-d H:i:s','d/m/Y');
		return $value;
	}

	public function getUsuarioAttribute(){
		$usuario = Usuario::find($this->documento);
		return $usuario;
	}

	public function getCamposAttribute(){
		$registros = [];
		$db_record = AssuntoVPC::find($this->assunto_vpc_id);
		if(isset($db_record)){
			$registros = VPCHelper::getCampos($db_record->campos);
		}
		return $registros;
	}

	public function getStatusAttribute(){
		$status = DB::table('vpc_has_status')->select(['status_vpc.nome','status_vpc.status'])->join('status_vpc','vpc_has_status.status_id','=','status_vpc.id')
		            ->where('vpc_id','=',$this->id)->orderBy('created_at','desc')->first();
		return $status;
	}

	public function getAllStatusAttribute(){
		$status = DB::table('vpc_has_status')->select(['status_vpc.nome','status_vpc.status',DB::raw('DATE_FORMAT(created_at,"%d/%m/%Y") as criado')])->join('status_vpc','vpc_has_status.status_id','=','status_vpc.id')
		            ->where('vpc_id','=',$this->id)->orderBy('created_at','asc')->get()->toArray();
		return $status;
	}

	public function getCustosAttribute(){

		$utilizados = DB::table('saldo_vpc_utilizado')->select(['provisionado','saldo_id'])->where('vpc_id', '=', $this->id)->get()->toArray();
		$registros = [];
		foreach ($utilizados as $item){
			$saldo = SaldoVpc::query()->find($item->saldo_id);
			$validade = $saldo->data_validade;
			VariableHelper::convertDateFormat($validade,'Y-m-d','d/m/Y');
			$registros[] = [
				'mes' => MonthHelper::getMonth($saldo->mes),
				'ano' => $saldo->ano,
				'saldo_total' => number_format($saldo->saldo_vpc,2,",","."),
				'saldo_provisionado' => number_format($saldo->saldo_provisionado,2,",","."),
				'saldo_disponivel' => number_format($saldo->saldo_disponivel,2,",","."),
				'validade' => $validade,
				'saldo_provisionado_atual' => number_format($item->provisionado,2,",",".")
			];
		}
		return $registros;
	}

	public function getAnexosAttribute(){
		$documentos_db = DB::table('arquivos_vpc')->where('vpc_id','=',$this->id)->get();
		$documentos = [];
		foreach ($documentos_db as $documento){
			$documentos[] = ['file' => url('upload/vpc/anexos/'.$documento->file),'name' => $documento->nome_original];
		}
		return $documentos;
	}

	public function getComprovantesAttribute(){
		$documentos_db = DB::table('comprovantes_vpc')->where('vpc_id','=',$this->id)->get();
		$documentos = [];
		foreach ($documentos_db as $documento){
			$documentos[] = ['file' => url('upload/vpc/comprovantes/'.$documento->file),'name' => $documento->nome_original];
		}
		return $documentos;
	}

	public function getAnexosAdminAttribute(){
		$documentos_db = DB::table('arquivos_admin_vpc')->where('vpc_id','=',$this->id)->get();
		$documentos = [];
		foreach ($documentos_db as $documento){
			$documentos[] = ['id'=>$documento->id,'file' => url('upload/vpc/anexos/admin/'.$documento->file),'name' => $documento->nome_original];
		}
		return $documentos;
	}

	public static function show($id)
	{
		$model = self::find($id);
		if (!isset($model))
			return null;
		return $model->append(['usuario','campos','all_status','custos','anexos','comprovantes','anexos_admin']);
	}

	public static function edit($data) {
		$registro = self::find($data['id']);
		$status = $data['status'];
		$comentarios = $data['comentarios'];
		$registro->saveStatus($status,$comentarios);
		$registro->save();
		return $registro;
	}

	public function addAnexo($data){
		$this->saveFile($data['file']);
		return true;
	}

	protected function saveFile($arquivo){
		if(!isset($this->id))
			return false;
		if(!is_uploaded_file($arquivo))
			return false;
		$nome_original = $arquivo->getClientOriginalName();
		$file = MediaHelper::upload($arquivo, 'upload/vpc/anexos/admin', str_replace(".".$arquivo->getClientOriginalExtension(),'',$arquivo->getClientOriginalName()));
		DB::table('arquivos_admin_vpc')->insert(['file' => $file,'vpc_id' => $this->id,'nome_original' => $nome_original,'administrador_id' => Auth::id()]);
		return true;
	}

	public function saveStatus($status,$comentarios = null){
		$status_id = DB::table('status_vpc')->where('nome','=',$status)->pluck('id')->first();
		if(isset($status_id)){
			DB::table('vpc_has_status')->insert([
				'vpc_id' => $this->id,
				'status_id' => $status_id,
				'created_at' => Carbon::now(),
				'comentarios' => $comentarios
			]);
			if($status =='REPROVADA')
				DB::table('saldo_vpc_utilizado')->where('vpc_id','=',$this->id)->update(['provisionado' => 0]);
			if($status == 'PAGO'){
				$saldos_utilizados = DB::table('saldo_vpc_utilizado')->where('vpc_id','=',$this->id)->get();
				foreach ($saldos_utilizados as $utilizado){
					$saldo = SaldoVpc::query()->find($utilizado->saldo_id);
					$saldo->saldo_vpc = $saldo->saldo_vpc - $utilizado->provisionado;
					$saldo->save();
					DB::table('saldo_vpc_utilizado')->where('id','=',$utilizado->id)->update([
						'provisionado' => 0.00,
						'utilizado' => $utilizado->provisionado
					]);
				}
			}
		}
	}
}
