<?php

namespace App\Http\Requests\Web\VPC;

use App\Helpers\VPCHelper;
use App\Model\Web\AssuntoVPC;
use App\Model\Web\VPC;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EditVPCRequest extends Request
{
	protected $custom_rules = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
	public function rules()
	{
		$this->custom_rules['id'] = ['required', Rule::exists('vpc')->where(function ($query) {
			$query->where('documento', Auth::guard('api')->user()->documento);
		})];
		return $this->custom_rules;
	}

	public function validationData() {
		$data = $this->all();
		$data['id'] = $this->route('id');
		if(isset($data['id'])){
			$vpc = VPC::query()->find($data['id']);
			if(isset($vpc)){
				$db_record = AssuntoVPC::find($vpc->assunto_vpc_id);
				if(isset($db_record)){
					$this->custom_rules = VPCHelper::getRules($db_record->campos,$vpc->status->nome);
				}
			}
		}
		unset($this->custom_rules['anexos']);
		$this->removeDbFiles($data,'anexos');
		$this->removeDbFiles($data,'comprovantes');
		return $data;
	}

	public function removeDbFiles(&$data, $index){
		if(isset($data[$index])){
			$array = $data[$index];
			foreach ($array as $key => $item){
				if(!is_uploaded_file($item))
					unset($data[$index][$key]);
			}
		}
	}
}
