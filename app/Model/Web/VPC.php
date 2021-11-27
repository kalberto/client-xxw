<?php

namespace App\Model\Web;

use App\Helpers\MediaHelper;
use App\Helpers\VariableHelper;
use App\Helpers\VPCHelper;
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
		'assunto_vpc_id','dados','documento'
	];

	protected $appends = ['acao','status'];

	public function setDadosAttribute($value)
	{
		$this->attributes['dados'] = json_encode($value);
	}

	public function getAcaoAttribute(){
		$acao = DB::table('assuntos_vpc')->where('id','=',$this->assunto_vpc_id)->select("nome")->pluck('nome')->first();
		return $acao;
	}
	public function getCreatedAtAttribute($value){
		VariableHelper::convertDateFormat($value,'Y-m-d H:i:s','d/m/Y');
		return $value;
	}

	public function getUpdatedAtAttribute($value){
		VariableHelper::convertDateFormat($value,'Y-m-d H:i:s','d/m/Y');
		return $value;
	}

	public function getDadosAttribute($value){
		if(isset($value))
			return json_decode($value);
		return [];
	}

	public function getCustoAttribute(){
		if(isset($this->dados->custo)){
			$custo = $this->dados->custo;
			$custo = str_replace('R$','',$custo);
			$custo = str_replace('.','',$custo);
			$custo = str_replace(',','.',$custo);
			return floatval($custo);
		}
		return 0;
	}

	public function getCamposAttribute(){
		$status = DB::table('vpc_has_status')->select(['status_vpc.id',])->join('status_vpc','vpc_has_status.status_id','=','status_vpc.id')
		            ->where('vpc_id','=',$this->id)->orderBy('created_at','desc')->first();
		$registros = [];
		$db_record = AssuntoVPC::find($this->assunto_vpc_id);
		if(isset($db_record)){
			$registros = VPCHelper::getCampos($db_record->campos,$status->id);
		}
		return $registros;
	}

	public function getStatusAttribute(){
		$status = DB::table('vpc_has_status')->select(['status_vpc.nome','status_vpc.status','vpc_has_status.comentarios'])->join('status_vpc','vpc_has_status.status_id','=','status_vpc.id')
		                                     ->where('vpc_id','=',$this->id)->orderBy('created_at','desc')->first();
		return $status;
	}

	public function getAllStatusAttribute(){
		$status = DB::table('vpc_has_status')->select(['status_vpc.nome','status_vpc.status',DB::raw('DATE_FORMAT(created_at,"%d/%m/%Y") as criado')])->join('status_vpc','vpc_has_status.status_id','=','status_vpc.id')
		            ->where('vpc_id','=',$this->id)->orderBy('created_at','asc')->get()->toArray();
		return $status;
	}

	public function getAnexosAttribute(){
		$documentos_db = DB::table('arquivos_vpc')->where('vpc_id','=',$this->id)->get();
		$documentos = [];
		foreach ($documentos_db as $documento){
			$documentos[] = ['id' => $documento->id,'file' => url('upload/vpc/anexos/'.$documento->file),'name' => $documento->nome_original];
		}
		return $documentos;
	}

	public function getAnexosAdminAttribute(){
		$documentos_db = DB::table('arquivos_admin_vpc')->where('vpc_id','=',$this->id)->get();
		$documentos = [];
		foreach ($documentos_db as $documento){
			$documentos[] = ['id' => $documento->id,'file' => url('upload/vpc/anexos/admin/'.$documento->file),'name' => $documento->nome_original];
		}
		return $documentos;
	}

	public function getComprovantesAttribute(){
		$documentos_db = DB::table('comprovantes_vpc')->where('vpc_id','=',$this->id)->get();
		$documentos = [];
		foreach ($documentos_db as $documento){
			$documentos[] = ['id' => $documento->id,'file' => url('upload/vpc/comprovantes/'.$documento->file),'name' => $documento->nome_original];
		}
		return $documentos;
	}

	public static function store($data){
		$vpc = new self();
		$vpc->fill($data);
		$vpc->documento = Auth::guard('api')->user()->documento;
		$vpc->save();
		$vpc->saveStatus('SOLICITADA');
		$vpc->handleFiles($data,'anexos');
		$vpc->saveSaldoProvisionado();
		return $vpc;
	}

	public static function edit($data) {
		$registro = self::query()->find($data['id']);
		if($registro->status->nome == 'REVISÃO'){
			$registro->fill($data);
			$registro->save();
			$registro->saveStatus('SOLICITADA');
			$registro->handleFiles($data,'anexos');
		}elseif ($registro->status->nome == 'COMPROVAÇÃO' || $registro->status->nome == 'aprovado'){
			$registro->saveStatus('COMPROVADO');
			$registro->handleFiles($data,'comprovantes');
		}
		return $registro;
	}

	public function cancelar(){
		$this->dados->saldo = "R$ 0,00";
		$this->save();
		$this->saveStatus("CANCELADA");
		DB::table('saldo_vpc_utilizado')->where('vpc_id','=',$this->id)->update(['provisionado' => 0]);
		//TODO Recalcular todos os saldos anteriores.
	}

	protected function saveSaldoProvisionado(){
		$saldos_user = SaldoVpc::query()->where([['documento','=',$this->documento],['data_validade','>',date('Y-m-d')],['saldo_vpc','>',0]])
		                 ->orderBy('data_validade')->get();
		$custo = $this->custo;
		foreach ($saldos_user as $item){
			if($item->saldo_disponivel >= $custo){
				$provisionado = $custo;
				$custo = 0;
			}else{
				$provisionado = $item->saldo_disponivel;
				$custo = $custo - $item->saldo_disponivel;
			}
			DB::table('saldo_vpc_utilizado')->insert([
				'provisionado' => $provisionado,
				'saldo_id' => $item->id,
				'vpc_id' => $this->id,
				'created_at' => date('Y-m-d')
			]);
			if($custo === 0)
				break;
		}
	}

	protected static function checkSaldoProvisionado($data){
		$valor = $data['valor'];
		$data_inicio = (new Carbon($data['data_inicio']));
		$valor = str_replace('R$','',$valor);
		$valor = str_replace('.','',$valor);
		$valor = str_replace(',','.',$valor);
		$valor = floatval($valor);
		if($valor <= 0){
			$resultado = [
				'provisonado' =>[],
				'disponivel' => [],
				'sucesso' => false,
				'msg' => 'O Valor precisa ser maior que 0'
			];
			return $resultado;
		}
		$custo = $valor;
		$documento = Auth::user()->documento;
		$saldos_user = SaldoVpc::query()->where([['documento','=',$documento],['data_validade','>=',$data_inicio->format('Y-m-d')],['saldo_vpc','>',0]])
		                       ->orderBy('data_validade')->get();
		$arrayP = [];
		$arrayD = [];
		foreach ($saldos_user as $item){
			if($item->saldo_disponivel >= $custo){
				$provisionado = $custo;
				$custo = 0;
			}else{
				$provisionado = $item->saldo_disponivel;
				$custo = $custo - $item->saldo_disponivel;
			}
			$arrayP[] = number_format($provisionado + $item->saldo_provisionado,2,",",".");
			$arrayD[] = number_format($item->saldo_disponivel - $provisionado,2,",",".");
			if($custo === 0)
				break;
		}
		$valor_disponivel = ($custo > 0 ? $valor - $custo : $valor);
		$valor_tratado = number_format($valor_disponivel,2,",",".");
		if($valor_disponivel == 0)
			$msg = "Você ainda não possui saldo para solitar VPCS";
		else
			$msg = 'O valor máximo disponivel é: '. $valor_tratado;
		$resultado = [
			'provisonado' => array_reverse($arrayP),
			'disponivel' => array_reverse($arrayD),
			'sucesso' => ($custo > 0 ? false : true),
			'msg' => $msg,
			'valor' => $valor_tratado
		];
		return $resultado;
	}

	protected function saveStatus($status){
		$status_id = DB::table('status_vpc')->where('nome','=',$status)->pluck('id')->first();
		if(isset($status_id)){
			DB::table('vpc_has_status')->insert([
				'vpc_id' => $this->id,
				'status_id' => $status_id,
				'created_at' => Carbon::now()
			]);
		}
	}

	protected function handleFiles($data,$key){
		if(isset($data[$key])){
			foreach ($data[$key] as $item){
				$this->saveFile($item, $key);
			}
		}
	}

	protected function saveFile($arquivo,$tipo){
		if(!isset($this->id))
			return false;
		if(!is_uploaded_file($arquivo))
			return false;
		$nome_original = $arquivo->getClientOriginalName();
		$file = MediaHelper::upload($arquivo, 'upload/vpc/'.$tipo, str_replace(".".$arquivo->getClientOriginalExtension(),'',$arquivo->getClientOriginalName()));
		$table = ($tipo == 'comprovantes' ? 'comprovantes_vpc' : 'arquivos_vpc');
		DB::table($table)->insert(['file' => $file,'vpc_id' => $this->id,'nome_original' => $nome_original]);
		return true;
	}
}
