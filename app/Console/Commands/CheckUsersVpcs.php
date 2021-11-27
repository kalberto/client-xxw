<?php

namespace App\Console\Commands;

use App\Helpers\MailHelper;
use App\Model\Canais\VPC;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckUsersVpcs extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'checkuservpcs';

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
		$usuarios   = DB::table( 'usuarios' )->select( [ 'documento', 'nome_fantasia', 'email' ] )->where( [
			[ 'ativo', '=', 1 ],
			[ 'teste', '=', 0 ],
			[ 'deleted_at', '=', null ],
			[ 'send_email_upgrade', '=', 1 ],
			[ 'email', '!=', null ]
		] )->get();
		$documentos = [];
		foreach ( $usuarios as $usuario ) {
			$mail = (object) [
				'nome'  => $usuario->nome_fantasia,
				'email' => $usuario->email,
			];
			MailHelper::trocaDeCategoria( $mail );
			$documentos[] = $usuario->documento;
		}
		DB::table( 'usuarios' )->whereIn( 'documento', $documentos )->update( [ 'send_email_upgrade' => 0 ] );
		$status_id = DB::table( 'status_vpc' )->where( 'nome', '=', 'APROVADO' )->pluck( 'id' )->first();
		$registros = DB::table( 'vpc' )->select( [ 'vpc.id as vpc_id', 'vpc.dados', 'vhs1.*' ] )
		               ->join( 'vpc_has_status as vhs1', 'vpc.id', '=', 'vhs1.vpc_id' )
		               ->leftJoin( 'vpc_has_status as vhs2', function ( $join ) {
			               $join->on( 'vpc.id', '=', 'vhs2.vpc_id' )->on( 'vhs1.id', '<', 'vhs2.id' );
		               } )
		               ->whereNull( 'vhs2.vpc_id' )->where( 'vhs1.status_id', '=', $status_id )
		               ->where( 'vpc.dados->data_inicio', '<', DB::raw( 'NOW()' ) )->get();
		foreach ( $registros as $registro ) {
			$vpc = VPC::find( $registro->vpc_id );
			$vpc->saveStatus( 'COMPROVAÇÃO', "Sua solitação já pode ser comprovada." );
		}
	}
}
