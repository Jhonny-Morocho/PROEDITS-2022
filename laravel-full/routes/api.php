<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\GeneroController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\RolePermisoController;
use App\Http\Controllers\ProveedorRolController;
use App\Http\Controllers\ProveedorAuth;
use App\Http\Controllers\AuthController;
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

//generos
Route::get('generos',[GeneroController::class,'index'])->middleware('can:super-admin,moderador,escritor');
Route::post('generos',[GeneroController::class,'store'])->middleware('can:super-admin');
Route::get('generos/{id}',[GeneroController::class,'show'])->middleware('can:super-admin,moderador,escritor');
Route::delete('generos/{id}',[GeneroController::class,'destroy'])->middleware('can:super-admin');
Route::put('generos/{id}',[GeneroController::class,'update'])->middleware('can:super-admin');


//Roles
Route::get('roles',[RolController::class,'index'])->middleware('can:super-admin');
Route::post('roles',[RolController::class,'store'])->middleware('can:super-admin');
Route::get('roles/{id}',[RolController::class,'show'])->middleware('can:super-admin');
Route::put('roles/{id}',[RolController::class,'update'])->middleware('can:super-admin');
Route::delete('roles/{id}',[RolController::class,'destroy'])->middleware('can:super-admin');
//Permisos
Route::get('permisos',[PermisoController::class,'index'])->middleware('can:super-admin');

//RolesPermisos
Route::get('rolespermisos',[RolePermisoController::class,'index'])->middleware('can:super-admin');

//ProveedorRol
Route::get('proveedoresRoles',[ProveedorRolController::class,'index'])->middleware('can:super-admin');
Route::post('proveedoresRoles',[ProveedorRolController::class,'store'])->middleware('can:super-admin');
//Route::put('proveedoresRoles/{id}',[ProveedorRolController::class,'update']);


//Proveedor 
Route::post('proveedores',[ProveedorController::class,'store'])->middleware('can:super-admin');


//AUHT
Route::post('auth/loginProveedor',[AuthController::class,'loginProveedor']);
Route::post('auth/loginCliente',[AuthController::class,'loginCliente']);
Route::post('auth/loginClienteApiSocial',[AuthController::class,'loginClienteApiSocial']);
Route::post('auth/registrarCliente',[AuthController::class,'registrarCliente']);