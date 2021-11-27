<?php

namespace App\Http\Requests\Admin\Usuario;

use App\Http\Requests\Request as BaseRequest;

class Request extends BaseRequest
{
	protected static $modulo = 5;
	protected static $permission = 1;
}
