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
class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['loginProveedor']]);
    }

    public function loginProveedor(Request $request)
    {

        try {

            $request->validate([
                'correo' => 'required|string|email',
                'password' => 'required|string',
            ]);
            //Send failed response if request is not valid
            $credentials = $request->only('correo', 'password');
            //$token = JWTAuth::attempt($credentials );//creat token
            $token = JWTAuth::attempt($credentials);
            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }
            $user = Auth::user();
            $proveedorRol= Proveedor::with('roles')->where('id',$user->id)->first();
            $payload = JWTFactory::proveedorRol($proveedorRol)->make();
            $token = JWTAuth::encode($payload);

            //envio un objeto usuario temp
            $auxProveedor=new Proveedor();
            $auxProveedor->nombre=$user->nombre;
            $auxProveedor->apellido=$user->apellido;
            $auxProveedor->apodo=$user->apodo;
            $auxProveedor->img=$user->img;
            $auxProveedor->correo=$user->correo;
            return response()->json([
                    'status' => 'success',
                    'user' => $auxProveedor,
                    'authorisation' => [
                        'token' => $token->get(),
                        'type' => 'bearer',
                    ]
            ],201);


        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>null],400);
        }
        catch (JWTException $th) {
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
