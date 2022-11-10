<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Proveedor;
//jwt
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

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
            //Access token from the request 
            $token = JWTAuth::parseToken();
            //Try authenticating user       
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
