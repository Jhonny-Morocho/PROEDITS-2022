<?php

namespace App\Http\Controllers\Carrusel;

use App\Models\Carrusel;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;

class EliminarCarrusel {

    public static function eliminarImgCarrusel($request,$idAdmin){
        try {
            $datos=$request->json()->all();
            $idDesencriptado=Crypt::decrypt($idAdmin);
            $esAdmin=Proveedor::where("id",$idDesencriptado)
            ->where("estado",1)
            ->first();
            if(!$esAdmin){
                return response()->json(["sms"=>'El usuario '.$idAdmin.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
            }

            $buscarArchivo=Carrusel::where('id',$datos['id'])->where("estado",1)->first();
            //necesito borrar el producto del directorio
            if($buscarArchivo){
                $archivoUbicacion=getenv('RUTA_CARRUSEL')."/".$buscarArchivo['archivo'];
                if(unlink($archivoUbicacion)){
                    $eliminarCarrusel=Carrusel::where("id",$datos['id'])
                    ->update(array('estado'=>0));
                    if($eliminarCarrusel){
                        return response()->json(["sms"=>'El '.$buscarArchivo['archivo']." ha sido eliminado","Siglas"=>"OE",'res'=>null]);
                    }
                }else{
                    return response()->json(["sms"=>'El '.$buscarArchivo['archivo']." no se pudo eliminar","Siglas"=>"ONE",'res'=>null]);
                }
            }
            // si es un enlace o url entonces es true por que igual se borra
            return response()->json(["sms"=>'El archivo '.$buscarArchivo['archivo']." no existe en la base de datos","Siglas"=>"ONE",'res'=>null]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }

    }
}
