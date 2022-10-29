<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\GeneroController;
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



Route::get('usuarios',[UsuarioController::class,'index']);
Route::get('usuarios/{id}',[UsuarioController::class,'show']);
Route::put('usuarios/{id}',[UsuarioController::class,'update']);
Route::post('usuarios',[UsuarioController::class,'store']);



Route::post('proveedores',[ProveedorController::class,'store']);

Route::post('generos',[GeneroController::class,'store']);
/* Route::get('proveedores',[ProveedorController::class,'index']);
Route::post('proveedores',[ProveedorController::class,'store']); */
