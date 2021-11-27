<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Model\Configuracao;
use App\Model\Lead;
use App\Http\Requests\Web\Lead\ContatoRequest;
use App\Http\Requests\Web\Lead\Request;
use App\Http\Requests\Web\Lead\DocumentoRequest;

class ContatosController extends Controller
{

	public function __construct() {
		$config = Configuracao::first();
		if (env('APP_ENV') == 'local') {
			define('TO_EMAIL', 'alberto.almeida@etools.com.br');
			define('FROM_EMAIL', 'noreply@mobvox.com.br');
		}else {
			if(isset($config) && isset($config->email_destinatario) && $config->email_destinatario != null){
				define('TO_EMAIL',$config->email_destinatario);
			}else{
				define('TO_EMAIL','falecomselect@xxw.com');
			}
			if(isset($config) && isset($config->email_remetente) && $config->email_remetente != null){
				define('FROM_EMAIL',$config->email_remetente);
			}else{
				define("FROM_EMAIL",'falecomselect@xxw.com');
			}
		}
	}

	public function store(ContatoRequest $request) {
		$data = $request->all();
		$mailTo = TO_EMAIL;
		$data['form_origem'] = "contato site";
		Lead::store($data);
		$data['assunto'] = DB::table('assuntos')->select('assunto')->where('id',$data['assunto_id'])->get()->first()->assunto;
		$subject = "Elextrolux | Canais - ".$data['assunto'];
		$return = $this->send('email/lead',$data,$mailTo,$subject);
		return response()->json($return, 200);
	}

	public function send($view,$data,$mailTo,$subject){
		if(!Mail::send($view, $data, function($message) use ($mailTo, $data, $subject){
			$message->from(FROM_EMAIL, utf8_decode($data['documento']));
			$message->subject($subject);
			$message->to($mailTo);
			if(isset($data['email']))
				$message->replyTo($data['email'],$data['documento']);
		})){
			$statusCode = 200;
			$response = [
				'status' => $statusCode,
				'msg' => 'Mensagem enviada com sucesso!'
			];
		}else {
			$statusCode = 200;
			$response   = [
				'status' => $statusCode,
				'msg'    => 'Mensagem enviada com sucesso!'
			];
		}
		return $response;
	}

	public function assuntos() {
		return response()->json(DB::table("assuntos")->orderBy('assunto')->get(), 200);
	}
}
