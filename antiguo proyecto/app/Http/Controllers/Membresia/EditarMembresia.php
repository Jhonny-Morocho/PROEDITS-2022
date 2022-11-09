<?php

namespace App\Http\Controllers\Membresia;

use App\Models\Genero;
use App\Models\Membresia;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class EditarMembresia {

    public static function editarMembresia($request,$idAdmin){
        try {
            $datos=$request->json()->all();
            $idDesencriptado=Crypt::decrypt($idAdmin);
            $esAdmin=Proveedor::where("id",$idDesencriptado)
                                ->where("estado",1)
                                ->where("tipo_usuario",'Admin')
                                ->first();
            if(!$esAdmin){
                return response()->json(["sms"=>'El usuario '.$esAdmin.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
            }
            $membresiaEditada=Membresia::where("id",$datos['id'])
            ->update(
                array('nombre'=>$datos['nombre'],
                       'precio'=>$datos['precio'],
                       'estado'=>$datos['estado'],
                       'descargas'=>$datos['descargas'],
                       'num_dias'=>$datos['num_dias']
                    )
            );
            if(!$membresiaEditada){
                return response()->json(["sms"=>"Genero no editado","Siglas"=>"GNE",'res'=>$membresiaEditada]);
            }
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$membresiaEditada]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
}
