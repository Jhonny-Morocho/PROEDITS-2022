<?php

namespace App\Http\Controllers\Genero;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Genero;
use Illuminate\Support\Facades\Crypt;
class EditarGenero {

    public static function editarGeneroPanel($request,$idAdmin){
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

            $generoEditado=Genero::where("id",$datos['id'])
            ->update(array('genero'=>$datos['genero']));
            if(!$generoEditado){
                return response()->json(["sms"=>"Genero no editado","Siglas"=>"GNE",'res'=>$generoEditado]);
            }
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$generoEditado]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }


}
