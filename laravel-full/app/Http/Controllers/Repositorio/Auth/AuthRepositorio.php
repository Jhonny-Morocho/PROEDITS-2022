<?php

namespace App\Http\Controllers\Repositorio\Auth;

use App\Models\Cliente;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
//jwt2
use Carbon\Carbon;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use JWTFactory;


class AuthRepositorio {
    //Auth Laravel 9


    //crear tokens para diferentes sesiones
    //https://medium.com/@niravdchavda/multiple-authentication-guards-for-laravel-restful-apis-jwt-617dfa24368d
    public static function registrarCliente($request){
        try {
            $data = $request->only('nombre', 'apellido', 'correo','password','autenticacion');
            $validator = Validator::make($data, [
                'nombre' => 'required|string|max:50|min:3',
                'apellido' => 'required|string|max:50|min:3',
                'correo' => 'required|string|email',
                'autenticacion'=>'required|string|max:50|min:1',
                'password' => 'required|string|max:50|min:3'
            ]);
            
            if ($validator->fails()) {
                return response()->json(['message' => "Error en validación de información",'data'=>$validator->messages()], 401);
            }
           
            $cliente=Cliente::where('correo',$request['correo'])->first();
            if($cliente!=null){
                $payload = JWTFactory::cliente($cliente)->make();
                $token = JWTAuth::encode($payload);
                echo $token;
                return response()->json(["message"=>"Ya existe un usuario con el mismo correo"],401);
            }
           // $token = JWTAuth::attempt($credentials );//creat token


            return ;
            $createCliente=Cliente::create([
                'nombre' => $request['nombre'],
                'apellido' => $request['apellido'],
                'correo' => $request['correo'],
                'autenticacion'=>$request['autenticacion'],
                'password' => Hash::make($request['password']),
                'estado' => 1,
                'fecha' => Carbon::now()
            ]);
            //cliente por defecto
            $createCliente->assignRole('cliente');
            return response()->json(["message"=>'success','data'=>$createCliente],201);
        }catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>null],400);
        }
        catch (JWTException $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>null],400);
        }
    }
    public static function loginCliente($request){
        $cliente=null;
        try 
        {
            $credenciales = $request->only('correo','password');
            $validator = Validator::make($credenciales, [
                'correo' => 'required|string|email',
                'password' => 'required|string|max:50|min:3'
            ]);
                
            if ($validator->fails()) {
                return response()->json(['message' => "Error en validación de información",'data'=>$validator->messages()], 401);
            }
            $cliente=Cliente::where('correo',$request['correo'])->first();
            if($cliente==null){
                return response()->json(["message"=>"Usuario no existe",'data'=>$cliente],200);
            }

            if(!(password_verify($request['password'],$cliente->password))){
                return response()->json(["message"=>"Password incorrecto",'data'=>null],200);
            }

            if (!$token = auth()->guard('tienda')->attempt($credenciales)) {
                return response()->json([
                    'message' => 'Unauthorized',
                    'data'=>array('message'=>'Error al generar token','authorisation'=>'Unauthorized')
                ], 401);

            }
            //creamos el token
            $clienteRol= auth()->guard('tienda')->user($credenciales);
            $payload = JWTFactory::clienteRol($clienteRol)->make();
            $token = JWTAuth::encode($payload);

            //envio un objeto usuario temp
            $auxCliente=new Cliente();
            $auxCliente->nombre=$clienteRol->nombre;
            $auxCliente->apellido=$clienteRol->apellido;
            $auxCliente->correo=$clienteRol->correo;
            return response()->json([
                'message' => 'success',
                'data' => array(
                    'usuario'=>$auxCliente,  
                    'authorisation' => [
                        'token' => $token->get(),
                        'type' => 'bearer',
                    ]
                ),
              
            ],201);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>null],400);
        }
        catch (JWTException $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>null],400);
        }
    }
    public static function loginClienteApiSocial($request){
        try {
            $credenciales = $request->only('correo','password');
            $validator = Validator::make($credenciales, [
                'correo' => 'required|string|email',
                'password' => 'required|string|max:50|min:1'
            ]);
            
            if ($validator->fails()) {
                return response()->json(['message' => "Error en validación de información",'data'=>$validator->messages()], 401);
            }
        
            $cliente=Cliente::where('correo',$request['correo'])->first();
            if($cliente==null){
                return response()->json(["message"=>"Usuario no existe",'data'=>$cliente],200);
            }
        
            if (!$token = auth()->guard('clienteApi')->attempt($credenciales)) {
                return response()->json([
                    'message' => 'Unauthorized',
                    'data'=>array('message'=>'Error al generar token','authorisation'=>'Unauthorized')
                ], 401);
            }
            //creamos el token
            $clienteRol= auth()->guard('clienteApi')->user($credenciales);
            $payload = JWTFactory::clienteRol($clienteRol)->make();
            $token = JWTAuth::encode($payload);

            //envio un objeto usuario temp
            $auxCliente=new Cliente();
            $auxCliente->nombre=$clienteRol->nombre;
            $auxCliente->apellido=$clienteRol->apellido;
            $auxCliente->correo=$clienteRol->correo;
            return response()->json([
                'message' => 'success',
                'data' => array(
                    'usuario'=>$auxCliente,  
                    'authorisation' => [
                        'token' => $token->get(),
                        'type' => 'bearer',
                    ]
                ),
              
            ],201);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR"]);
        }
    }

}
