<?php

namespace App\Http\Middleware\Permissions;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckRole {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next,$role)
	{
		$id_perfil = Auth::guard('admin')->user()->perfil()->first()->id;
		$modulos = DB::table('perfil_mod_adm_permissao')
		             ->join('mod_adm_permissao','perfil_mod_adm_permissao.mod_adm_perm_id','=','mod_adm_permissao.id')
		             ->where([['perfil_id','=',$id_perfil],['mod_adm_permissao.mod_adm_id','=',$role]])
		             ->count();
		if($modulos >= 1) {
			return $next( $request );
		}
		else
			return response()->json(['error' => 'Unauthorized.'], 403);
	}
}