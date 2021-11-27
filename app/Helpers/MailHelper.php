<?php

namespace App\Helpers;

use App\Mail\Mail as MailH;
//  WEB
use App\Model\Web\PasswordReset;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailHelper {

	private static function getDebugUsers(){
		return (object)[
				'email' => 'alberto.almeida@etools.com.br',
				'name' => "Alberto"
			];
	}

	public static function contaCriada($usuario){
		//TODO Fazer email de enviar cadastro criado com sucesso
		$data = [];
		self::send(self::getUsers($usuario), new MailH("email.email-conta-criada",'Conta criada',$data));
	}

	public static function trocaDeSistema($usuario){
		$data = [];
		self::send(self::getUsers($usuario), new MailH("email.email-troca-sistema",'Mude sua senha',$data));
	}

	public static function convite($usuario){
		$data = [
			'nome' => $usuario->nome,
			'nome_evento' => $usuario->nome_evento,
			'url' => $usuario->url,
		];
		self::send(self::getUsers($usuario), new MailH("email.email-convite",'Convite para o evento',$data));
	}

	public static function trocaDeCategoria($usuario){
		$data = [
			'nome' => $usuario->nome,
		];
		self::send(self::getUsers($usuario), new MailH("email.email-aviso-categoria",'Atualização de categoria',$data));
	}

	private static function getUsers($usuario){
		if(getenv('APP_ENV') == "dev"){
			$users = self::getDebugUsers();
		}else{
			$users = [(object)[
				'email' => $usuario->email,
				'nome' => $usuario->nome
			]];
		}
		return $users;
	}

	public static function send($users, MailH $mail){
		try{
			Mail::to($users)->send($mail);
		}
		catch (\Exception $exception){
			Log::error($exception->getMessage());
		}
	}

}
