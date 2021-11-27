<?php

namespace App\Http\Controllers\Web;

use App\Helpers\PaginatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Model\Canais\Faq;

class FaqController extends Controller
{
	public function __construct() {
	}

	public function index() {
		$this->beforeAction();
		return view('web.faq', $this->data);
	}

	public function faq(Request $request){
		$user = Auth::user()->documento;
		$perfil_id = DB::table('usuarios')->select('niveis.perfil_id')->where('documento',$user)
		               ->join('niveis','niveis.id','nivel_id')->pluck('perfil_id')->first();
		$registros = Faq::where([['perfil_id','=',$perfil_id]])->orWhere('perfil_id','=',null)->get();
		return response()->json(['registros'=>$registros],200);
	}
}
