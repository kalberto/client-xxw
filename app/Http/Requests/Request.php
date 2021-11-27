<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

abstract class Request extends FormRequest
{
	protected static $modulo = 0;
	protected static $permission = 2;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$perfil = Auth::guard('admin')->user() != null ? Auth::guard('admin')->user()->perfil : null;
		if (isset($perfil)) {
			$count = DB::table('perfil_mod_adm_permissao')
			           ->join('mod_adm_permissao', 'perfil_mod_adm_permissao.mod_adm_perm_id', '=', 'mod_adm_permissao.id')
			           ->where('perfil_id', $perfil->id)
			           ->where([['mod_adm_permissao.mod_adm_id', '=', $this::$modulo], ['mod_adm_permissao.permissao_id', '=', $this::$permission]])
			           ->count();
			if ($count > 0)
				return true;
		}
		return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [];
	}

	/**
	 * Handle a failed validation attempt.
	 *
	 * @param \Illuminate\Contracts\Validation\Validator|Validator $validator
	 *
	 * @return void
	 */
	protected function failedValidation(Validator $validator)
	{
		$errors = (new ValidationException($validator))->errors();
		throw new HttpResponseException(
			response()->json(['msg' => "Preencha os campos corretamente", 'error_validate' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
		);
	}

	/**
	 * Handle a failed authorization attempt.
	 *
	 * @return void
	 *
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	protected function failedAuthorization()
	{
		throw new AuthorizationException('Unauthorized');
	}
}
