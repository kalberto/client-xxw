<?php

namespace App\Http\Requests\Admin\Usuario;

class EditRequest extends UsuarioRequest
{
	protected static $permission = 2;


	/**
	 * Get the validation rules that apply to the request.
	 *;
	 * @return array
	 */
	public function rules()
	{
		$rules = parent::rules();
		$rules['documento'] = [];
		return $rules;
	}
}
