<?php

namespace App\Console\Commands;

use App\Helpers\MailHelper;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendInviteEventos extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'sendinviteeventos:cron';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	public function __construct() {
		parent::__construct();

	}

	public function handle() {
		$registros = DB::table( 'convidados_listas_rsvp' )
		               ->join( 'usuarios', 'convidados_listas_rsvp.documento', '=', 'usuarios.documento' )
		               ->join( 'listas_rsvp', 'convidados_listas_rsvp.lista_rsvp_id', '=', 'listas_rsvp.id' )
		               ->join( 'conteudos', 'listas_rsvp.conteudo_id', '=', 'conteudos.id' )
		               ->where( [
			               [ 'usuarios.ativo', '=', 1 ],
			               [ 'usuarios.conta_atualizada', '=', 1 ],
			               [ 'email_enviado', '=', 0 ]
		               ] )->select( [
				'convidados_listas_rsvp.lista_rsvp_id',
				'usuarios.documento',
				'usuarios.contato_responsavel',
				'usuarios.email',
				'conteudos.titulo',
				'conteudos.slug'
			] )->get();

		foreach ( $registros as $registro ) {
			$usuario = (object) [
				'nome'        => $registro->contato_responsavel,
				'email'       => $registro->email,
				'nome_evento' => $registro->titulo,
				'url'         => env( 'APP_URL' ) . '/posts/' . $registro->slug
			];
			MailHelper::convite( $usuario );
			DB::table( 'convidados_listas_rsvp' )
			  ->where( [
				  [ 'lista_rsvp_id', '=', $registro->lista_rsvp_id ],
				  [ 'documento', '=', $registro->documento ]
			  ] )
			  ->update( [ 'email_enviado' => 1, 'data_envio_email' => Carbon::now() ] );
		}
	}
}
