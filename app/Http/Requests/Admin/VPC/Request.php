<?php

namespace App\Http\Requests\Admin\VPC;

use App\Http\Requests\Request as BaseRequest;

class Request extends BaseRequest
{
	protected static $modulo = 12;
	protected static $permission = 1;
}
