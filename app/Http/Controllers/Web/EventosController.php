<?php

namespace App\Http\Controllers\Web;

use App\Model\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Helpers\PaginatorHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\VariableHelper;

class EventosController extends Controller
{
    public function load(Request $request){
	    $params = $request->all();
		$user = Auth::guard()->user();
		$conteudos_id = DB::table('convidados_listas_rsvp')->join('listas_rsvp','convidados_listas_rsvp.lista_rsvp_id','=','listas_rsvp.id')
										->join('conteudos','listas_rsvp.conteudo_id','=','conteudos.id')
										->where([['convidados_listas_rsvp.documento','=',$user->documento]])
										->pluck('conteudos.id')->toArray();
		$limit = (isset($params['limit']) && is_numeric($params['limit']) && $params['limit'] > 0) ? $params['limit'] : 10;
		$order_by = "created_at";
		$asc = false;
		$q = '';
		$registros = Evento::whereIn('conteudos.id',$conteudos_id)->where(['deleted_at' => null, 'evento' => 1])
							->with(['categoria','media.media_root','eventosRelacionados']);
		if (isset($params['sort'])) {
			$order_by = $params['sort'];
			if (substr($order_by, 0, 1) == '-') {
				$asc = false;
				$order_by = substr($order_by, 1);
			}
		}
		if(isset($params['q'])) {
			$q = $params['q'];
			$registros = $registros->search($params['q']);
		}
		if(isset($params['categoria'])) {
			if($params['categoria'] == 1)
				$registros = $registros->where('evento',1);
		}
		if(isset($params['inicio'])) {
			$inicio = $params['inicio'];
			VariableHelper::convertDateFormat($inicio,"d/m/Y","Y-m-d H:i:s");
			$registros = $registros->where('created_at', '>=', $inicio);
		}
		if(isset($params['termino'])) {
			$termino = $params['termino'];
			VariableHelper::convertDateFormat($termino,"d/m/Y","Y-m-d H:i:s");
			$registros = $registros->where('created_at', '<=', $termino);
		}
		if(Schema::hasColumn('conteudos', $order_by)) {
			if ($asc)
				$registros = $registros->orderBy('conteudos.' . $order_by)->orderBy('conteudos.nome')->paginate($limit);
			else
				$registros = $registros->orderBy('conteudos.' . $order_by, 'desc')->orderBy('conteudos.nome', 'desc')->paginate($limit);
		}else {
			$registros = $registros->paginate($limit);
		}
		foreach($registros as $i => $registro){
			foreach($registro['media'] as $i => $media){
				if($media->thumb != null){
					$registros[$i]['thumb'] = url($registro['media'][$i]['media_root']['path'].$registro['media'][$i]['file']);
				}
			}
		}
		if($registros->total() > 0){
			$statusCode = 200;
			$response = [
				'pagination' => PaginatorHelper::paginate($registros->lastPage(), $registros->currentPage()),
				'registros' => $registros->appends($params),
				'msg' => $registros->total() . ' registro(s) encontrado(s)' . ($q != '' ? ' para ' . $q . '!' : '!')
			];
		}else {
			$statusCode = 200;
			$response = [
				'pagination' => false,
				'registros' => [],
				'msg' => 'Nenhum registro encontrado' . ($q != '' ? ' para ' . $q . '!' : '!')
			];
		}
		return response()->json($response, $statusCode);
    }

	public function show($slug){
		$evento = Evento::where('slug', $slug)->with(['media.media_root'])->first();
		$medias = [];
		if(isset($evento)){
			if(count($evento['media']) > 0){
				foreach($evento['media'] as $media){
					if($media->thumb != 1){
						$medias[] = [
							'url_imagem' => url($media['media_root']['path'].$media['file']),
							'video_is_link' => $media->video_is_link == 1 ? true : false,
							'video_link' => $media->video_is_link ? $media->video_link : null,
							'video' => $media->tipo == 2 ? true : false,
						];
					}
				}
			}else
				$medias = [];
			$data = [
				'nome' => $evento->nome,
				'slug' => $evento->slug,
				'titulo' => $evento->titulo,
				'link_google_calendar' => $evento->link_google_calendar,
				'link_transmissao' => $evento->link_transmissao,
				'texto' => $evento->texto,
				'conteudos-relacionados' => $evento->conteudosRelacionados()->with('media')->get()->toArray(),
				'categoria' => $evento->categoria()->select('nome','slug')->where('ativo',1)->get(),
				'medias' => $medias
				,
			];
			$response = [
				'registros' => $data
			];
		}else {
			$response = [
				'registros' => 'Nenhum registro'
			];
		}	
		return response()->json($response,200);
	}
}
