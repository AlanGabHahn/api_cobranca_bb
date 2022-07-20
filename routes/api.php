<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\APIController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/token', [APIController::class, 'token'])->name('token');
Route::get('/registrar', [APIController::class, 'registrar'])->name('registrar');
Route::get('/listar', [APIController::class, 'listar'])->name('listar');
Route::get('/consultar', [APIController::class, 'consultar'])->name('consultar');
Route::get('/baixar', [APIController::class, 'baixar'])->name('baixar');
Route::get('/atualizar', [APIController::class, 'atualizar'])->name('atualizar');

