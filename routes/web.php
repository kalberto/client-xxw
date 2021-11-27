<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

// Endereçar para uma controler, a fim de receber variaveis do Laravel
Route::get('/{slug?}/{other_slug?}', function () {
    return view('spa.web');
})->where('slug', '.*')->where('other_slug', '.*')->name('web.pagina');
