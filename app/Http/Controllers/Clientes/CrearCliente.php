<?php

namespace App\Http\Controllers\Clientes;

use App\Models\Cliente;
use App\Models\Genero;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class CrearCliente {

    public static function registrarCliente($request){
        try {
            $datos=$request->json()->all();
            $existeCliente=Cliente::where("correo",$request['correo'])->first();
            if($existeCliente){
                return response()->json(["sms"=>'Ya existe un usuario el mismo correo '.$request['correo'].' intente con otro correo',
                                        "Siglas"=>"UR",'res'=>$datos]);
            }
            $opciones=array('cost'=>12);
            $passwordCliente=$datos["password"];
            $password_hashed=password_hash($passwordCliente,PASSWORD_BCRYPT,$opciones);
            $objCliente=new Cliente();
            $objCliente->nombre =$datos['nombre'];
            $objCliente->apellido =$datos['apellido'];
            $objCliente->correo=$datos['correo'];
            $objCliente->proveedor=$datos['proveedor'];
            $objCliente->tipo_usuario='Cliente';
            $objCliente->password=$password_hashed;
            $objCliente->estado=$datos['estado'];
            $objCliente->saldo=0;
            $objCliente->save();

            $respUsuario=array(
                "id"=>Crypt::encrypt($objCliente->id),
                "nombre"=>$objCliente->nombre,
                "apellido"=> $objCliente->apellido,
                "correo"=>$objCliente->correo,
                "tipo_usuario"=>$objCliente->tipo_usuario,
                "editado"=>$objCliente->editado,
                "estado"=>$objCliente->estado
            );
            if(!$objCliente){
                return response()->json(["sms"=>"No se pude registrar al usuario ".$datos['correo'],"Siglas"=>"UNR",'res'=>$objCliente]);
            }
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$respUsuario]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ONE",'res'=>null]);
        }
    }
}
