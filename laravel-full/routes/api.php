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
use App\Http\Controllers\AuthTiendaController;
use App\Http\Controllers\ClienteRolController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ArchivoDemoController;
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

/* ============= PANEL DE ADMISTRCION =================== */

//Route::group(['middleware' => 'auth.role'], function(){
    //generos
    Route::get('generos',[GeneroController::class,'index'])->middleware('can:super-admin,moderador,escritor');
    Route::post('generos',[GeneroController::class,'store'])->middleware('can:super-admin');
    Route::get('generos/{id}',[GeneroController::class,'show'])->middleware('can:super-admin,moderador,escritor');
    Route::delete('generos/{id}',[GeneroController::class,'destroy'])->middleware('can:super-admin');
    Route::put('generos/{id}',[GeneroController::class,'update'])->middleware('can:super-admin');

    //productos
    Route::get('productos',[ProductoController::class,'index'])->middleware('can:super-admin,moderador,escritor');
    Route::post('productos',[ProductoController::class,'store'])->middleware('can:super-admin');
    Route::get('productos/{id}',[ProductoController::class,'show'])->middleware('can:super-admin,moderador,escritor');
    Route::delete('productos/{id}',[ProductoController::class,'destroy'])->middleware('can:super-admin');
    Route::put('productos/{id}',[ProductoController::class,'update'])->middleware('can:super-admin');

    //archivo demo
    Route::post('archivo-demo',[ArchivoDemoController::class,'store'])->middleware('can:super-admin,moderador');

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
    Route::get('proveedores-roles',[ProveedorRolController::class,'index'])->middleware('can:super-admin');
    Route::post('proveedores-roles',[ProveedorRolController::class,'store'])->middleware('can:super-admin');
    //Route::put('proveedoresRoles/{id}',[ProveedorRolController::class,'update']);


    //Proveedor
    Route::post('proveedores',[ProveedorController::class,'store'])->middleware('can:super-admin');

//});


//AUHT
Route::post('auth/login-proveedor',[AuthController::class,'loginProveedor']);



/* ================================ TIENDA ======================= */
//AUTH TIENDA
Route::post('auth/login-cliente',[AuthTiendaController::class,'loginCliente']);
Route::post('auth/loginClienteApiSocial',[AuthTiendaController::class,'loginClienteApiSocial']);
Route::post('auth/registrarCliente',[AuthTiendaController::class,'registrarCliente']);

//Cliente Roles
Route::get('clientes-roles',[ClienteRolController::class,'index'])->middleware('can:super-admin');
Route::post('clientes-roles',[ClienteRolController::class,'store'])->middleware('can:super-admin');
