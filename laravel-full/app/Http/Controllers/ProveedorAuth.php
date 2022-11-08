<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proveedor;
class ProveedorAuth extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $proveedor=null;
        try {
            $proveedor=Proveedor::where('correo',$request['correo'])->where('estado','1')->first();
            if($proveedor==null){
                return response()->json(["message"=>"El usuario no existe"],400);
            }
            if(!(password_verify($request['password'],$proveedor->password))){
                return response()->json(["sms"=>"Contraseña incorrecta","Siglas"=>"PI"]);
            }
            return 'TODO OK';
        /*     $respUsuario=array(
                "id"=>Crypt::encrypt($existeUsuario->id),
                "nombre"=>$existeUsuario->nombre,
                "apellido"=> $existeUsuario->apellido,
                "apodo"=>$existeUsuario->apodo,
                "correo"=>$existeUsuario->correo,
                "tipo_usuario"=>$existeUsuario->tipo_usuario,
                "editado"=>$existeUsuario->editado,
                "estado"=>$existeUsuario->estado,
                "img"=>$existeUsuario->img
            ); */

            return response()->json(["message"=>'success','data'=>$proveedor],201);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$proveedor],400);
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
        //
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
        //
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
