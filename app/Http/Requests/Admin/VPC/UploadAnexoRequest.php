<?php

namespace App\Http\Requests\Admin\VPC;

class UploadAnexoRequest extends Request
{
	protected static $permission = 2;

	public function rules()
	{
		return [
			'id' => ['required', 'exists:vpc,id'],
			'file' => ['required','file','mimes:jpg,jpeg,png,pdf','max:2000']
		];
	}

	protected function validationData()
	{
		$data = $this->all();
		$data['id'] = $this->route('id');
		return $data;
	}
}
