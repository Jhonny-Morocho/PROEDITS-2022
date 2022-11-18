<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Proveedor;
use Error;
//jwt
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
//decodificar token
class RoleAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */


    public function handle(Request $request, Closure $next,...$roles)
    {
        try {
            //https://jwt-auth.readthedocs.io/en/develop/
            //Access token from the request 
            //dd($request->headers);
            //controlar q el token que viene de la cabezera sea de un usuario proveedor
            $token = JWTAuth::parseToken();
            $payLoad = JWTAuth::getPayload($token)->toArray();
            //reviar si es un usuario cliente//debe ser admin
            if(empty($payLoad['proveedorRol'])){
                throw new JWTException;
            }
            //todo ok     
            $user = $token->authenticate();
            $proveedorRol= Proveedor::with('roles')->where('id',$user->id)->first();
        } catch (TokenExpiredException $e) {
            //Thrown if token has expired        
            return $this->unauthorized('Su token ha caducado. Por favor, inicie sesión de nuevo.');
        } catch (TokenInvalidException $e) {
            //Thrown if token invalid
            return $this->unauthorized('Su token no es válido. Por favor, inicie sesión de nuevo.');
        }catch (JWTException $e) {
            //Thrown if token was not found in the request.
            return $this->unauthorized('Por favor, adjunte un token de portador a su solicitud');
        }
        //If user was authenticated successfully and user is in one of the acceptable roles, send to next request.
        $rolesUsuario=$proveedorRol->roles;
        $auxRoles=[];
        foreach ($rolesUsuario as $key => $value) {
            $auxRoles=$value->name;
        }

        if ($user && in_array($auxRoles, $roles)) {
            return $next($request);
        }
    
        return $this->unauthorized();
    }
    private function unauthorized($message = null){
        return response()->json([
            'message' => $message ? $message : 'No está autorizada para acceder a este recurso',
            'success' => false
        ], 401);
    }
}
