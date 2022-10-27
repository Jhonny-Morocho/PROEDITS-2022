<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
 * Display a listing of the resource.
 * Mostramos el listado de los regitros solicitados.
 * @return \Illuminate\Http\Response
 *
 * @OA\Get(
 *     path="/api/enterprises",
 *     tags={"enterprises"},
 *     summary="Mostrar el listado de empresas",
 *     @OA\Response(
 *         response=200,
 *         description="Mostrar todas las empresas."
 *     ),
 *     @OA\Response(
 *         response="default",
 *         description="Ha ocurrido un error."
 *     )
 * ) 
 */
    public function index()
    {
        try{
            $usuarios=User::where('estado','1')->get();
            $message = 'Listar usuarios.';
            $response = [
                'success' => true,
                'data'    => $usuarios,
                'message' => $message,
            ];
            return response()->json($response, 200);
            //return response()->json($response=["message"=>'success','data'=>$usuarios],200);
        }catch(\Throwable $th){
           return response()->json(["message"=>$th->getMessage(),400]);
        } 

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $usuario=null;
            $usuario=User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'estado' => "1",
                'password' => Hash::make($request['password']),
                'api_token' => Str::random(60),
            ]);
            // Asignación del rol
            $usuario->assignRole('Administrator');
            if($usuario==null){
                return response()->json(["message"=>"No se puede crear el usuario",400]);
            }
            return response()->json(["message"=>'success','data'=>$usuario,200]);
        }catch(\Throwable $th){
            return response()->json(["message"=>$th->getMessage(),400]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $usuario=null;
            $usuario=User::where('id',$id)->where('estado','1')->first();
            if($usuario==null){
                return response()->json(["message"=>"Usuario no encontrado",400]);
            }
            return response()->json(["message"=>'success','data'=>$usuario,200]);
        }catch(\Throwable $th){
           return response()->json(["message"=>$th->getMessage(),400]);
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $usuarioExiste=null;
            $usuarioExiste=User::where('id',$id)->first();
            if($usuarioExiste==null){
                return response()->json(["message"=>"Usuario no encontrado",400]);
            }
            //actulizo el usuario
            $usuario=null;
            $usuario=User::where('id',$id)->update(
                array('name'=>$request['name'],
                       'password'=>Hash::make($request['password'])
                    )
            );

            if($usuario==null){
                return response()->json(["message"=>"No se puedo actulizar el usuario",400]);
            }
            return response()->json(["message"=>'success','data'=>$usuario,200]);
        }catch(\Throwable $th){
           return response()->json(["message"=>$th->getMessage(),400]);
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
