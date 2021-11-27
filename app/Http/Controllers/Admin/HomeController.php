<?php

namespace App\Http\Controllers\Admin;

use App\Model\Configuracao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller {
	/**
	 * Instantiate a new controller instance.
	 *
	 */
	public function __construct()
	{
		$this->role = 1;
		$this->middleware('role:'.$this->role);
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$this->beforeAction();
		if(!$this->hasPermission($this->role,1))
			return view('errors.403',$this->data);
		return view('admin.dashboard',$this->data);
	}
}
