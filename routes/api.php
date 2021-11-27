<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Esqueci minha Senha
Route::group(['prefix' => 'password'], function () {
	Route::post('email', 'Web\ResetPasswordsController@sendResetLinkEmail');
	Route::post('reset', 'Web\ResetPasswordsController@reset');

	Route::group(['middleware' => ['auth:api']], function () {
		Route::put('redefine', 'Web\ResetPasswordsController@redefinePassword');
	});
});

// Não Autenticado
Route::get('csrf', 'Auth\LoginController@refreshToken');
Route::get("cidades", "Web\UsuariosController@cidades");
Route::post("login", "Web\UsuariosController@login");
Route::post("login-admin", "Web\UsuariosController@loginAdmin");
Route::get('estados', 'Web\UsuariosController@estados');
Route::get('cidades/{estado_id}', 'Web\UsuariosController@cidades');


Route::post('usuario/pre-cadastro','Web\UsuariosController@preCadastro');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('usuario', 'Web\UsuariosController@is_authenticated');

 // Autenticado
Route::group(['middleware' => ['auth:api']], function () {

    // Usuário
	Route::get('logout', 'Web\UsuariosController@logout');
	Route::group(['prefix' => 'usuario'], function () {
		Route::get('perfil', 'Web\UsuariosController@load');
		Route::get('perfil-interno', 'Web\UsuariosController@perfilInterno');
		Route::post('salvar', 'Web\UsuariosController@updatePerfil');
		Route::post('atualizar', 'Web\UsuariosController@update');
	});
	// Conteúdos
	Route::group(['prefix' => 'conteudo'], function () {
		Route::get("/", "Web\ConteudosController@load");
		Route::get("/{slug}", "Web\ConteudosController@show");
		Route::post("/{id}/participar","Web\ConteudosController@participar")->where('id','[0-9]+');
	});
	// Eventos
	Route::group(['prefix' => 'evento'], function () {
		Route::get("/", "Web\EventosController@load");
		Route::get("/{slug}", "Web\EventosController@show");
		Route::post('/{slug}/participar', 'Web\UsuariosController@participar');
	});
	// Nivel
	Route::group(['prefix' => 'nivel'], function () {
		Route::get('/', 'Web\NiveisController@load');
	});
	// Meta
	Route::group(['prefix' => 'meta'], function () {
		Route::get('/', 'Web\MetasController@load');
		Route::get('/anos','Web\MetasController@anos');
		Route::get('/anos/{ano}/trimestres','Web\MetasController@trimestres')->where('ano','[0-9]+');
		Route::get('/anos/{ano}/detalhamento','Web\MetasController@detalhamentoAno')->where('ano','[0-9]+');
		Route::get('/anos/{ano}/meses','Web\MetasController@meses')->where('ano','[0-9]+');
		Route::get('/anos/{ano}/meses/{mes}','Web\MetasController@meta')->where(['ano' => '[0-9]+','mes' => '[0-9]+']);
	});
	// Contato
	Route::group(['prefix' => 'contato'], function () {
		Route::post("", "Web\ContatosController@store");
		Route::get("assuntos", "Web\ContatosController@assuntos");
	});
	// Faq's
	Route::group(['prefix' => 'faq'], function () {
		Route::get('/', 'Web\FaqController@faq');
	});

	// VPC's
	Route::group(['prefix' => 'vpc'], function () {
		Route::post("", "Web\VPCController@store");
		Route::get('saldo','Web\UsuariosController@saldoVpc');
		Route::get('/load', 'Web\VPCController@load');
		Route::post('/check', 'Web\VPCController@checkValorVpc');
		Route::get("/{id}", "Web\VPCController@show")->where('id','[0-9]+');
		Route::get("/{id}/cancelar", "Web\VPCController@cancelar")->where('id','[0-9]+');
		Route::post("/{id}", "Web\VPCController@update")->where('id','[0-9]+');
		Route::get('/assuntos', 'Web\VPCController@assuntos');
		Route::get('/assuntos/{id}/campos', 'Web\VPCController@assuntoCampos');
	});
});
