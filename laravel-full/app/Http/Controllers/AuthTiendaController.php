<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Proveedor;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
//jwt
 use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Hash;  
//jwt2
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use JWTFactory;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;
class AuthTiendaController extends Controller
{    
    //use AuthenticatesUsers;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['registrarCliente','loginCliente','loginClienteApiSocial']]);
    }
    
    public function loginCliente(Request $request)
    {
        try {
            return Repositorio\Auth\AuthRepositorio::loginCliente($request);
         }
         catch (\Throwable $th) {
             return response()->json(["message"=>$th->getMessage(),'data'=>null],400);
         }
    }
    public function loginClienteApiSocial(Request $request){
        try {
            return Repositorio\Auth\AuthRepositorio::loginClienteApiSocial($request);
         }
         catch (\Throwable $th) {
             return response()->json(["message"=>$th->getMessage(),'data'=>null],400);
         }
    }
    public function registrarCliente(Request $request){
        try {
           return Repositorio\Auth\AuthRepositorio::registrarCliente($request);
        }
        catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>null],400);
        }

    }
    
/* 
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }
    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    } */
}
