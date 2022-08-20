<?php

namespace App\Http\Controllers\Proveedor;

use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class EliminarProveedor {

    public static function eliminarArchivo($nombreArchivo){
        try {
            $archivoUbicacion=getenv('RUTA_CARATULA_PROVEEDOR')."/".$nombreArchivo;
            if(unlink($archivoUbicacion)){
                return true;
            }else{
                return false;
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public static function eliminarProveedor($request,$idAdmin,$idProveedor){
        $datos=$request->json()->all();
        try {
           $idDesencriptado=Crypt::decrypt($idAdmin);
           $esAdmin=Proveedor::where("id",$idDesencriptado)
                               ->where("estado",1)
                               ->where("tipo_usuario",'Admin')
                               ->first();
           if(!$esAdmin){
               return response()->json(["sms"=>'El usuario '.$esAdmin.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
           }
           //eliminar proveedor
            $elimiarProveedor=Proveedor::where("id",$idProveedor)->update(array('estado'=>$datos['estado']));
            if(!$elimiarProveedor){
                return response()->json(["sms"=>"No se puedo eliminar el password","Siglas"=>"ONE",'res'=>$elimiarProveedor]);
            }
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$elimiarProveedor]);
        } catch (\Throwable $th) {
                return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ONE",'res'=>null]);
        }

    }
}
