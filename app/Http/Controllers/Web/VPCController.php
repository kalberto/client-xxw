<?php

namespace App\Http\Controllers\Web;

use App\Helpers\VPCHelper;
use App\Http\Requests\Web\VPC\EditVPCRequest;
use App\Http\Requests\Web\VPC\Request;
use App\Http\Requests\Web\VPC\VPCRequest;
use App\Model\Web\AssuntoVPC;
use App\Model\Web\VPC;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VPCController extends Controller
{

	public function assuntos(){
		$registros = AssuntoVPC::get();
		return response()->json(['registros'=>$registros],200);
	}

	public function assuntoCampos($id){
		$db_record = AssuntoVPC::find($id);
		if(isset($db_record)){
			$registros = VPCHelper::getCampos($db_record->campos);
			return response()->json(['registros'=>$registros],200);
		}
		return response()->json(['registros'=>[]],200);
	}

	public function store(VPCRequest $request) {
		$data = $request->all();
		$vpc = VPC::store($data);
		return response()->json(['msg' => 'Mensagem enviada com sucesso!'], 200);
	}

	public function load(){
		$registros = VPC::query()->where('documento','=', Auth::guard('api')->user()->documento)->get();
		return response()->json(['registros'=>$registros],200);
	}

	public function show( $id ) {
		$vpc = VPC::query()->find($id);
		if (isset($vpc)){
			$vpc->append(['campos','all_status','anexos','anexos_admin','comprovantes']);
			$response = [
				'registro' => $vpc
			];
			return response()->json( $response, 200 );
		} else {
			$response = [
				'registro' => 'Nenhum registro'
			];
		}
		return response()->json( $response, 404 );
	}

	public function update(EditVPCRequest $request, $id) {
		$data = $request->all();
		$data['id'] = $id;
		$vpc = VPC::edit($data);
		return response()->json(['msg' => 'Mensagem enviada com sucesso!'], 200);
	}

	public function checkValorVpc(Request $request){
		$data = $request->all();
		$resultado = VPC::checkSaldoProvisionado($data);
		return response()->json($resultado);
	}

	public function cancelar($id){
		$documento = Auth::user()->documento;
		if(DB::table('vpc')->where([['documento','=',$documento],['id','=',$id]])->count() == 0){
			return response()->json(['msg' => 'Essa VPC não existe.'],422);
		}
		$vpc = VPC::find($id);
		if($vpc->status->status == "aprovado" || $vpc->status->status == "pendente" || $vpc->status->status == "revisao"){
			$vpc->cancelar();
			return response()->json(['msg' => "A VPC foi cancelada."]);
		}else{
			return response()->json(['msg' => 'Essa VPC não pode ser cancelada.'],422);
		}
	}
}
