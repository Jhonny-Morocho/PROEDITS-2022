<?php

namespace App\Http\Controllers;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }
    public function registrarProveedor(){

    }
    
    public function register(Request $request)
    {

        //validate incoming request 
        $validacion=$this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string']
        );

        try 
        {
            $user = new Client();
            $user->correo= $request->input('username');
            $user->password = app('hash')->make($request->input('password'));
            $user->save();

            return response()->json( [
                        'entity' => 'users', 
                        'action' => 'create', 
                        'result' => 'success'
            ], 201);

        } 
        catch (\Exception $e) 
        {
            return response()->json( [
                       'entity' => 'users', 
                       'action' => 'create', 
                       'result' => 'failed'
            ], 409);
        }
    }
	
     /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */	 
    public function login(Request $request)
    {
          //validate incoming request 
        $this->validate($request, [
            'correo' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['correo', 'password']);
        if (! $token = Auth::attempt($credentials)) {			
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }
	
     /**
     * Get user details.
     *
     * @param  Request  $request
     * @return Response
     */	 	
    public function me()
    {
        return response()->json(auth()->user());
    }
}
