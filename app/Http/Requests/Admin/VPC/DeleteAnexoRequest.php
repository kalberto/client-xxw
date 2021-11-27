<?php

namespace App\Http\Requests\Admin\VPC;

class DeleteAnexoRequest extends Request
{
	protected static $permission = 1;

	public function rules()
	{
		return [
			'id' => ['required', 'exists:arquivos_admin_vpc,id']
		];
	}

	protected function validationData()
	{
		$data = $this->all();
		$data['id'] = $this->route('id');
		return $data;
	}
}
