<?php

namespace App\Http\Requests\Admin\Faq;

use App\Http\Requests\Admin\Request as BaseRequest;

class IdRequest extends BaseRequest
{

    protected static $modulo = 10;
	protected static $permission = 2;
	
}
