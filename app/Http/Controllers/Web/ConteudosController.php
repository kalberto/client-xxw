<?php

namespace App\Http\Controllers\Web;


use App\Helpers\PaginatorHelper;
use App\Model\Canais\Conteudo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ConteudosController extends Controller {

	protected function guard() {
		return Auth::guard( 'web' );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function load( Request $request ) {
		$params     = $request->all();
		$limit      = ( isset( $params['limit'] ) && is_numeric( $params['limit'] ) && $params['limit'] > 0 ) ? $params['limit'] : 10;
		$order_by   = "created_at";
		$asc        = false;
		$categoria  = ( isset( $params['categoria'] ) && is_numeric( $params['categoria'] ) ) ? $params['categoria'] : 1;
		$confirmado = ( isset( $params['confirmado'] ) && is_numeric( $params['confirmado'] ) ) ? $params['confirmado'] : 1;
		$completo   = ( isset( $params['completo'] ) && is_numeric( $params['completo'] ) ) ? $params['completo'] : 1;
		if ( isset( $params['sort'] ) ) {
			$order_by = $params['sort'];
			if ( substr( $order_by, 0, 1 ) == '-' ) {
				$asc      = false;
				$order_by = substr( $order_by, 1 );
			}
		}
		$documento = Auth::user('api')->documento;
		$perfil_id = DB::table('usuarios')->select('niveis.perfil_id')->where('documento',$documento)
		               ->join('niveis','niveis.id','nivel_id')->pluck('perfil_id')->first();
		if ( $categoria == 2 ) {
			$registros = Conteudo::where([['perfil_id','=',$perfil_id],['evento', '=', false]])->orWhere('perfil_id','=',null);
		} elseif ( $categoria == 3 ) {
			$conteudos_id = $this->getAvailableEventosId( $confirmado, $completo );
			$registros    = Conteudo::where( 'evento', '=', true )->whereIn( 'conteudos.id', $conteudos_id );
		} else {
			$conteudos_id = $this->getAvailableEventosId( $confirmado, $completo );
			$registros    = Conteudo::where(function(Builder $query) use ($perfil_id){
				$query->where([['perfil_id','=',$perfil_id],['evento', '=', false]])->orWhere('perfil_id','=',null);
			})->orWhere( function ( Builder $query ) use ( $conteudos_id ) {
				                        $query->where( 'evento', '=', true )->whereIn( 'conteudos.id', $conteudos_id );
			                        } );
		}
		if ( Schema::hasColumn( 'conteudos', $order_by ) ) {
			if ( $asc ) {
				$registros = $registros->orderBy( 'conteudos.' . $order_by )->orderBy( 'conteudos.titulo' )->paginate( $limit );
			} else {
				$registros = $registros->orderBy( 'conteudos.' . $order_by, 'desc' )->orderBy( 'conteudos.titulo', 'desc' )->paginate( $limit );
			}
		} else {
			$registros = $registros->paginate( $limit );
		}
		if ( $registros->total() > 0 ) {
			$statusCode = 200;
			$response   = [
				'pagination' => PaginatorHelper::paginate( $registros->lastPage(), $registros->currentPage() ),
				'registros'  => $registros->appends( $params ),
				'msg'        => $registros->total() . ' registro(s) encontrado(s)'
			];
		} else {
			$statusCode = 200;
			$response   = [
				'pagination' => false,
				'registros'  => [],
				'msg'        => 'Nenhum registro encontrado'
			];
		}

		return response()->json( $response, $statusCode );
	}

	public function show( $slug ) {
		$conteudo = Conteudo::where( 'slug', '=', $slug )->with( [ 'medias.media_root' ] )->first()->append('documentos');
		$medias   = [];
		if ( isset( $conteudo ) ) {
			if ( count( $conteudo->medias ) > 0 ) {
				foreach ( $conteudo->medias as $media ) {
					$medias[] = [
						'url_imagem'    => url( $media['media_root']['path'] . $media['file'] ),
						'video_is_link' => $media->video_is_link == 1 ? true : false,
						'video_link'    => $media->video_is_link ? $media->video_link : null,
						'video'         => $media->tipo == 2 ? true : false,
					];
				}
			} else {
				$medias = [];
			}
			$conteudos_id              = $this->getAvailableEventosId();
			$conteudos_relacionados_id = DB::table( 'conteudos_relacionados' )
			                               ->join( 'conteudos', 'conteudos_relacionados.relacionado_id', '=', 'conteudos.id' )
			                               ->where( [
				                               [ 'conteudo_id', '=', $conteudo->id ],
				                               [ 'conteudos.ativo', '=', true ]
			                               ] )->where( function ( QueryBuilder $query ) use ( $conteudos_id ) {
												$query->where( 'evento', '=', false )
												      ->orWhere( function ( QueryBuilder $query ) use ( $conteudos_id ) {
													      $query->where( 'evento', '=', true )->whereIn( 'conteudos.id', $conteudos_id );
												      } );
											} )->pluck( 'conteudos.id' )->toArray();

			$conteudos_relacionados    = Conteudo::whereIn( 'conteudos.id', $conteudos_relacionados_id )
			                                     ->get()->makeHidden( ['conteudos_relacionados','autor','evento','ativo','data_fim','link_google_calendar','link_transmissao','texto'] );
			$data                      = [
				'id'                     => $conteudo->id,
				'nome'                   => $conteudo->nome,
				'slug'                   => $conteudo->slug,
				'titulo'                 => $conteudo->titulo,
				'link_google_calendar'   => $conteudo->link_google_calendar,
				'link_transmissao'       => $conteudo->link_transmissao,
				'texto'                  => $conteudo->texto,
				'conteudos_relacionados' => $conteudos_relacionados->toArray(),
				'categoria'              => $conteudo->categoria()->select( 'nome', 'slug' )->where( 'ativo', 1 )->get(),
				'medias'                 => $medias,
				'data_inicio'            => $conteudo->data_inicio,
				'evento'                 => $conteudo->evento,
				'confirmado'             => $conteudo->confirmado,
				'documentos'             => $conteudo->documentos,
			];
			$response                  = [
				'registros' => $data
			];
		} else {
			$response = [
				'registros' => 'Nenhum registro'
			];
		}

		return response()->json( $response, 200 );
	}

	public function participar( $id ) {
		$user      = Auth::user();
		$documento = isset( $user ) ? $user->documento : false;
		if ( $documento === false ) {
			return response()->json( [ 'error' => 'Unauthorized' ], 401 );
		}
		$convidado_lista = DB::table( 'convidados_listas_rsvp' )->select( ['lista_rsvp_id','documento','confirmado'] )
		                     ->join( 'listas_rsvp', 'convidados_listas_rsvp.lista_rsvp_id', '=', 'listas_rsvp.id' )
		                     ->where( [
			                     [ 'listas_rsvp.conteudo_id', '=', $id ],
			                     [ 'convidados_listas_rsvp.documento', '=', $documento ]
		                     ] )->first();
		if ( ! isset( $convidado_lista ) ) {
			return response()->json( [ 'error' => 'Não encontrado' ], 422 );
		}
		DB::table( 'convidados_listas_rsvp' )->where( [
			[ 'lista_rsvp_id', '=', $convidado_lista->lista_rsvp_id ],
			[ 'documento', '=', $convidado_lista->documento ]
		] )
		  ->update( [ 'confirmado' => ! $convidado_lista->confirmado ] );

		return response()->json( [ 'msg' => 'Sucesso', 'confirmado' => ! $convidado_lista->confirmado ], 200 );
	}

	protected function getAvailableEventosId( $confirmado = 1, $completo = 1 ) {
		$user  = Auth::guard()->user();
		$query = DB::table( 'convidados_listas_rsvp' )->join( 'listas_rsvp', 'convidados_listas_rsvp.lista_rsvp_id', '=', 'listas_rsvp.id' )
		           ->join( 'conteudos', 'listas_rsvp.conteudo_id', '=', 'conteudos.id' )
		           ->where( [ [ 'convidados_listas_rsvp.documento', '=', $user->documento ] ] );

		if ( $confirmado == 2 ) {
			$query->where( 'convidados_listas_rsvp.confirmado', '=', true );
		} elseif ( $confirmado == 3 ) {
			$query->where( 'convidados_listas_rsvp.confirmado', '=', false );
		}
		if ( $completo == 2 ) {
			$query->where( 'conteudos.data_inicio', '>', date( 'Y-m-d' ) );
		} elseif ( $completo == 3 ) {
			$query->where( 'conteudos.data_inicio', '<=', date( 'Y-m-d' ) )
			      ->where( 'conteudos.data_fim', '>=', date( 'Y-m-d' ) );
		} elseif ( $completo == 4 ) {
			$query->where( 'conteudos.data_fim', '<', date( 'Y-m-d' ) );
		}
		$conteudos_id = $query->pluck( 'conteudos.id' )->toArray();

		return $conteudos_id;
	}
}
