<?php

namespace App\Http\Requests\Web\VPC;

use App\Helpers\VPCHelper;
use App\Model\Web\AssuntoVPC;
use Carbon\Carbon;

class VPCRequest extends Request
{
	protected $custom_rules = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
    	$this->custom_rules['assunto_vpc_id'] = ['required','exists:assuntos_vpc,id'];
    	return $this->custom_rules;
    }

    public function validationData() {
	    date_default_timezone_set('America/Sao_Paulo');
	    $data = $this->all();
	    if(isset($data['assunto_vpc_id'])){
		    $db_record = AssuntoVPC::find($data['assunto_vpc_id']);
		    if(isset($db_record)){
			    $this->custom_rules = VPCHelper::getRules($db_record->campos);
				if(isset($this->custom_rules['comprovantes']))
					unset($this->custom_rules['comprovantes']);
		    }
	    }
	    return $data;
    }
}
