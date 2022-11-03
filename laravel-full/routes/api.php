<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\GeneroController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\RolePermisoController;
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


//Generos
Route::get('generos',[GeneroController::class,'index']);
Route::post('generos',[GeneroController::class,'store']);
Route::get('generos/{id}',[GeneroController::class,'show']);
Route::put('generos/{id}',[GeneroController::class,'update']);
Route::delete('generos/{id}',[GeneroController::class,'destroy']);

//Roles
Route::get('roles',[RolController::class,'index']);

//Permisos
Route::get('permisos',[PermisoController::class,'index']);

//RolesPermisos
Route::get('rolespermisos',[RolePermisoController::class,'index']);