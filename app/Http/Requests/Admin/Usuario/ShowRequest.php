<?php

namespace App\Http\Requests\Admin\Usuario;

class ShowRequest extends Request
{
	protected static $permission = 1;

	public function rules()
	{
		return [
			'documento' => ['required', 'exists:usuarios,documento']
		];
	}

	protected function validationData()
	{
		$data = $this->all();
		$data['documento'] = $this->route('documento');
		return $data;
	}
}
