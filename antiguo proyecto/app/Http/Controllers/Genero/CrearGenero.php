<?php

namespace App\Http\Controllers\Genero;

use App\Models\Genero;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class CrearGenero {

    public static function registrarGeneroPanel($request,$idAdmin){
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
            $objGenero=new Genero();
            $objGenero->genero =$datos['genero'];
            $objGenero->estado =$datos['estado'];
            $objGenero->save();
            if(!$objGenero){
                return response()->json(["sms"=>"Genero no registrado","Siglas"=>"GNR",'res'=>$objGenero]);
            }
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$objGenero]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
}
