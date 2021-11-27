<?php

namespace App\Http\Controllers\Admin;

use App\Model\Lead;
use App\Model\LogAdministrador;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class LeadController extends Controller
{

	protected $searchable = ['area','created_at'];
	protected $editRoute = '';

	public function __construct()
	{
		$this->role = 11;
		$this->model = new Lead();
		$this->customFilter = false;
		$this->middleware('role:' . $this->role);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$this->beforeAction();
		if (!$this->hasPermission($this->role, 1))
			return view('errors.403', $this->data);
		return view('admin.leads.leads', $this->data);
	}

	public function leads()
	{
		if (!$this->hasPermission($this->role, 1))
			return response()->json(['error' => 'Unauthorized.'], 403);
		$response = [
			'total' => Lead::countTotal(),
		];
		$statusCode = 200;
		return Response::json($response, $statusCode);
	}

	public function export(Request $request)
    {
		if (!$this->hasPermission($this->role, 1)) {
			return Response::json(['error' => 'Unauthorized.'], 403);
		}
		$data = array(
			'administrador_id' => Auth::user()->id,
			'registro_id' => 0,
			'tabela' => 'leads',
			'tipo' => 'export',
			'ip' => $request->ip()
		);
		LogAdministrador::store($data);

		$type = 'xls';
		$should_filter = false;
		if(isset($request->filtro)){
			$should_filter = true;
			$filter = $request->filtro;
			$file = 'leads-'.'filtro-'.$filter.'-'.date('Y-m-d-h-i-s');
		}else
			$file = 'leads-'.date('Y-m-d-h-i-s');
		if($should_filter){
			$leads = Lead::where('form_origem','like','%'.$filter.'%')->with('assunto')
						->select('nome','email','telefone','mensagem','assunto_id','form_origem','created_at')
						->orderBy('id', 'DESC')->get();
		}else
			$leads = Lead::with('assunto')->select('nome','email','telefone','mensagem','assunto_id','form_origem','created_at')
						->orderBy('id', 'DESC')->get();
		foreach ($leads as $i => $lead){
			if($lead['assunto_id'] == null){
				$lead['assunto_id'] = 'Sem assunto';
			}else{
				$lead['assunto_id'] = $lead['assunto']['assunto'];
			}
			// var_dump($lead);
			// die();
			unset($lead['assunto']);
		}
		// $h = $this->getHeader();
        return Excel::create($file, function($excel) use ($leads) {
            $excel->sheet('mySheet', function($sheet) use ($leads)
            {
                $sheet->fromArray($leads);
				$sheet->row(1, array(
					'Nome','E-mail','Telefone','Mensagem','Assunto','Form Origem','Data'
				));
            });
        })->download($type);
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Request $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $id)
	{
		if ($id != null && is_numeric($id) && ($lead = Lead::find($id)) != null) {
			if ($lead->delete()) {
				$data = array(
					'administrador_id' => Auth::user()->id,
					'registro_id' => $id,
					'tabela' => 'leads',
					'tipo' => 'delete',
					'ip' => $request->ip()
				);
				LogAdministrador::store($data);
				$statusCode = 200;
				$response = [
					'msg' => 'Lead deletado com sucesso!'
				];
			} else {
				$statusCode = 500;
				$response = [
					'msg' => 'Erro ao deletar o lead!'
				];
			}
		} else {
			$statusCode = 404;
			$response = [
				'msg' => 'Não foi possível encontrar esse lead.'
			];
		}
		return Response::json($response, $statusCode);
	}

	// public function headings(): array
    // {
    //     return ['Nome','E-mail','Assunto','Mensagem','Telefone','Form origem','Data',];
    // }

	private function getHeader(){
		return '"Nome";"E-mail";"Assunto";"Mensagem";"Telefone";"Form origem";"Data"';
	}
	private function getRow($lead){
		return "\n".'"'.$lead->nome.'";"'.$lead->email.'";"'.$lead->assunto.'";"'.$lead->telefone.'";"'.$lead->form_origem.'";"'.$lead->form_origem.'";"'.$lead->created_at.'";';
	}
}
