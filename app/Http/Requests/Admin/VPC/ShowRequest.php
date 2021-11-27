<?php

namespace App\Http\Requests\Admin\VPC;

class ShowRequest extends Request
{
	protected static $permission = 2;

	public function rules()
	{
		return [
			'id' => ['required', 'exists:vpc,id']
		];
	}

	protected function validationData()
	{
		$data = $this->all();
		$data['id'] = $this->route('id');
		return $data;
	}
}
