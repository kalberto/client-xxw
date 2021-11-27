<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

/* ADMIN */

use Illuminate\Support\Facades\Route;

Route::group(array( 'prefix' => 'admin'), function(){

	//NÃO LOGADO
	Route::get('login', 'Auth\LoginController@showLoginForm')->name('auth.login');
	Route::get('password/forgot','Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.forgot');
	Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('auth.email');
	Route::get('password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('password.reset');
	Route::post('password/reset','Auth\ResetPasswordController@reset')->name('password.new');
	Route::post('login', 'Auth\LoginController@login')->name('login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');

    Route::group(['middleware' => ['auth:admin']], function (){

        Route::get('','Admin\HomeController@index')->name('admin');

        //ADMINISTRADORES
		Route::get('administradores','Admin\AdministradorController@index')->name('admin.administradores');
		Route::get('api/administradores','Admin\AdministradorController@load')->name('admin.loadAdministradores');
		Route::get('administradores/adicionar','Admin\AdministradorController@create')->name('admin.administradores.adicionar');
		Route::post('administradores/salvar','Admin\AdministradorController@store')->name('admin.administradores.salvar');
		Route::get('administradores/editar/{id}','Admin\AdministradorController@show')->name('admin.administradores.editar');
		Route::get('api/administradores/{id}','Admin\AdministradorController@loadAdministrador')->name('admin.loadAdministradores');
		Route::put('administradores/atualizar/{id}','Admin\AdministradorController@update')->name('admin.administradores.atualizar');
		Route::put('administradores/atualizar/senha/{id}','Admin\AdministradorController@senha')->name('admin.administradores.atualizar.senha');
        Route::delete('administradores/deletar/{id}','Admin\AdministradorController@destroy')->name('admin.administradores.deletar');
        //PERFIS
		Route::get('perfis','Admin\PerfilController@index')->name('admin.perfis');
		Route::get('perfis/adicionar', 'Admin\PerfilController@create')->name('admin.perfis.adicionar');
		Route::get('perfis/editar/{id}', 'Admin\PerfilController@show')->name('admin.perfis.editar');
		Route::get('api/perfis','Admin\PerfilController@load')->name('admin.api.perfis');
		Route::get('api/perfis/all','Admin\PerfilController@all')->name('admin.perfis.all');
		Route::post('api/perfis','Admin\PerfilController@store');
		Route::get('api/perfis/{id}','Admin\PerfilController@loadResource');
		Route::get('api/mod_adm_permissao','Admin\PerfilController@mod_adm_permissao');
		Route::put('api/perfis/{id}','Admin\PerfilController@update');
        Route::delete('api/perfis/{id}','Admin\PerfilController@destroy');
        //LOGS
		Route::get('logs','Admin\LogController@index')->name('admin.log');
		Route::get('api/log_acessos','Admin\LogController@load')->name('admin.loadLogs');
		//ASSETS
		Route::get('assets','Admin\AssetsController@index')->name('admin.assets');
		Route::get('api/assets','Admin\AssetsController@load')->name('admin.api.assets');
		Route::get('assets/adicionar','Admin\AssetsController@create')->name('admin.assets.adicionar');
		Route::get('assets/editar/{id}','Admin\AssetsController@edit')->name('admin.assets.editar')->where('id','[0-9]+');
		Route::get('api/assets/all','Admin\AssetsController@all');
		Route::get('api/assets/{id}','Admin\AssetsController@show')->where('id','[0-9]+');
		Route::get('api/curso-areas/all','Admin\AssetsController@all');
		Route::post('assets/salvar/media','Admin\AssetsController@storeMedia')->name('admin.api.assets.save');
		Route::post('assets/salvar','Admin\AssetsController@store')->name('admin.assets.salvar');
		Route::post("api/assets/{id}/check-url","Admin\AssetsController@checkUrl");
		Route::post("api/assets/check-url","Admin\AssetsController@checkUrlAll");
		Route::post('assets/atualizar/{id}','Admin\AssetsController@update')->name('admin.assets.atualizar')->where('id','[0-9]+');
		Route::delete('api/assets/{id}','Admin\AssetsController@destroy')->where('id','[0-9]+');
		// Route::get('assets','Admin\AssetsController@index')->name('admin.assets');
		// Route::get('assets/adicionar', 'Admin\AssetsController@create')->name('admin.assets.adicionar');
		// Route::get('assets/editar/{id}', 'Admin\AssetsController@show')->name('admin.assets.editar');
		// Route::get('api/assets','Admin\AssetsController@load')->name('admin.api.assets');
		// Route::get('api/media/{slug}','Admin\AssetsController@loadMediaRoot');
		// Route::post('api/assets','Admin\AssetsController@store')->name('admin.api.assets.save');
		// Route::get('api/assets/{id}','Admin\AssetsController@loadResource');
		// Route::post('api/assets/{id}','Admin\AssetsController@update');
		// Route::delete('api/assets/{id}','Admin\AssetsController@destroy');
	    //CONFIGURACOES
	    Route::get('configuracoes','Admin\ConfiguracaoController@edit')->name('admin.configuracoes');
	    Route::get('api/configuracoes','Admin\ConfiguracaoController@show');
	    Route::put('api/configuracoes','Admin\ConfiguracaoController@update');
	    //USUARIOS
		Route::get('api/total/usuarios','Admin\UsuarioController@totalUsuarios');
	    Route::get('usuarios','Admin\UsuarioController@index')->name('admin.usuarios');
	    Route::get('api/usuarios','Admin\UsuarioController@load')->name('admin.usuarios.assets');
	    Route::get('usuarios/adicionar', 'Admin\UsuarioController@create')->name('admin.usuarios.adicionar');
	    Route::post('api/usuarios','Admin\UsuarioController@store');
	    Route::get('usuarios/editar/{documento}', 'Admin\UsuarioController@edit')->where('documento', '[0-9]+')->name('admin.usuarios.editar');
	    Route::get('usuarios/acessar/{documento}', 'Admin\UsuarioController@acessar')->where('documento', '[0-9]+')->name('admin.usuarios.acessar');
	    Route::get('api/usuarios/{documento}', 'Admin\UsuarioController@show')->where('documento', '[0-9]+');
	    Route::put('api/usuarios/{documento}', 'Admin\UsuarioController@update')->where('documento', '[0-9]+');
	    Route::post('api/usuarios/upload','Admin\UsuarioController@upload');
	    Route::get('usuarios/planilha-base','Admin\UsuarioController@download')->name('admin.registrodiplomas.download');
	    Route::post('api/usuarios/upload/meta','Admin\UsuarioController@uploadMeta');
	    Route::post('api/usuarios/upload/classificacao','Admin\UsuarioController@uploadClassificacao');
	    Route::post('api/usuarios/upload/saldo-vpc','Admin\UsuarioController@uploadSaldoVpc');

	    Route::get('api/usuarios/perfis','Admin\UsuarioController@perfis');
	    Route::get('api/usuarios/perfis/{id}/niveis','Admin\UsuarioController@niveis')->where('id', '[0-9]+');
		Route::get('api/usuarios/niveis/all', 'Admin\UsuarioController@all');
		Route::get('api/usuarios/niveis/{id}', 'Admin\UsuarioController@niveisById');
		Route::get('api/usuarios/niveis-by-perfis/{string_ids}', 'Admin\UsuarioController@niveisByPerfis');

	    //CONTEUDOS
	    Route::get('conteudos','Admin\ConteudoController@index')->name('admin.conteudos');
		Route::get('conteudos/adicionar', 'Admin\ConteudoController@create')->name('admin.conteudos.adicionar');
	    Route::get('conteudos/editar/{id}', 'Admin\ConteudoController@edit')->name('admin.conteudos.editar')->where('id','[0-9]+');
	    Route::get('api/conteudos','Admin\ConteudoController@load')->name('admin.api.conteudos');
	    Route::get('api/conteudos/{id}', 'Admin\ConteudoController@show')->where('id','[0-9]+');
	    Route::post('api/conteudos', 'Admin\ConteudoController@store');
	    Route::put('api/conteudos/{id}', 'Admin\ConteudoController@update')->where('id','[0-9]+');
	    Route::post('api/conteudos/{id}/save-files', 'Admin\ConteudoController@saveFiles')->where('id','[0-9]+');
		Route::delete('api/conteudos/{id}', 'Admin\ConteudoController@destroy')->where('id','[0-9]+');
		Route::delete('api/conteudos-documentos/{id}', 'Admin\ConteudoController@deleteDocumento')->where('id','[0-9]+');
		Route::post("api/conteudos/check-url/{id?}","Admin\ConteudoController@checkUrl");
		Route::get('api/conteudos/all', 'Admin\ConteudoController@all');
		Route::get('api/conteudos/only/{id?}', 'Admin\ConteudoController@only');
		Route::get('api/conteudos/select/{id?}', 'Admin\ConteudoController@conteudosSelect');
		Route::get('api/medias/all/{id?}', 'Admin\ConteudoController@allMedias');
		Route::get('api/conteudos/ativo/{id}', 'Admin\ConteudoController@ativo')->where('id','[0-9]+');
	    //EVENTOS
	    Route::get('eventos','Admin\EventoController@index')->name('admin.eventos');
		Route::get('eventos/adicionar', 'Admin\EventoController@create')->name('admin.eventos.adicionar');
	    Route::get('eventos/editar/{id}', 'Admin\EventoController@edit')->name('admin.eventos.editar')->where('id','[0-9]+');
	    Route::get('api/eventos','Admin\EventoController@load')->name('admin.api.eventos');
	    Route::get('api/eventos/{id}', 'Admin\EventoController@show')->where('id','[0-9]+');
		Route::delete('api/eventos/{id}', 'Admin\EventoController@destroy')->where('id','[0-9]+');
	    Route::post('api/eventos', 'Admin\EventoController@store');
	    Route::put('api/eventos/{id}', 'Admin\EventoController@update')->where('id','[0-9]+');
		Route::get('api/eventos/only/{id?}', 'Admin\EventoController@all');
	    Route::get('api/eventos/ativo/{id}', 'Admin\EventoController@ativo')->where('id','[0-9]+');
		//CATEGORIAS
		Route::get('api/categorias/all', 'Admin\CategoriaController@all');
		Route::get('categorias', 'Admin\CategoriaController@index')->name('admin.categorias');
		Route::get('api/categorias', 'Admin\CategoriaController@load')->name('admin.api.categorias');
		Route::get('categorias/adicionar', 'Admin\CategoriaController@create')->name('admin.categorias.adicionar');
		Route::get('categorias/editar/{id}', 'Admin\CategoriaController@edit')->name('admin.categorias.editar');
		Route::get('api/categorias/{id}', 'Admin\CategoriaController@show');
		Route::post('categorias', 'Admin\CategoriaController@store')->name('admin.categorias.salvar');
		Route::put('categorias/{id}', 'Admin\CategoriaController@update')->name('admin.categorias.atualizar');
		Route::delete('categorias/{id}', 'Admin\CategoriaController@destroy')->name('admin.categorias.deletar');
		Route::post("api/categorias/check-url/{id?}","Admin\CategoriaController@checkUrl");

	    //LOCALIZACAO
	    Route::get("api/estados", "Admin\LocalizacaoController@estados");
	    Route::get("api/estados/{id}/cidades", "Admin\LocalizacaoController@cidades");

	    //FAQS
	    Route::get('faqs','Admin\FaqController@index')->name('admin.faqs');
	    Route::get('faqs/adicionar', 'Admin\FaqController@create')->name('admin.faqs.adicionar');
	    Route::get('faqs/editar/{id}', 'Admin\FaqController@edit')->name('admin.faqs.editar')->where('id','[0-9]+');
	    Route::get('api/faqs','Admin\FaqController@load')->name('admin.api.faqs');
	    Route::get('api/faqs/{id}', 'Admin\FaqController@show')->where('id','[0-9]+');
	    Route::delete('api/faqs/{id}', 'Admin\FaqController@destroy')->where('id','[0-9]+');
	    Route::post('api/faqs', 'Admin\FaqController@store');
	    Route::put('api/faqs/{id}', 'Admin\FaqController@update')->where('id','[0-9]+');

	    //LEADS
	    Route::get('api/total/leads', 'Admin\LeadController@leads');
		Route::get('leads', 'Admin\LeadController@index')->name('admin.leads');
		Route::get('api/leads', 'Admin\LeadController@load')->name('admin.loadLeads');
		Route::get('leads/export', 'Admin\LeadController@export');
		Route::delete('leads/delete/{id}', 'Admin\LeadController@destroy');

		//RELATÓRIOS
	    Route::get('relatorios/usuarios', 'Admin\UsuarioController@export');

	    //VPCS
	    Route::get('vpc','Admin\VPCController@index')->name('admin.vpc');
	    Route::get('vpc/editar/{id}', 'Admin\VPCController@edit')->name('admin.vpc.editar')->where('id','[0-9]+');
	    Route::get('api/vpc','Admin\VPCController@load')->name('admin.api.faqs');
	    Route::get('api/vpc/{id}', 'Admin\VPCController@show')->where('id','[0-9]+');
	    Route::put('api/vpc/{id}', 'Admin\VPCController@update')->where('id','[0-9]+');
	    Route::post('api/vpc/{id}/upload', 'Admin\VPCController@uploadAnexo')->where('id','[0-9]+');
	    Route::delete('api/vpc/anexo-admin/{id}', 'Admin\VPCController@deleteAnexo')->where('id','[0-9]+');
    });
});
