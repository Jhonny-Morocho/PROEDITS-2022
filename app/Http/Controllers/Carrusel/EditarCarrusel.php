<?php

namespace App\Http\Controllers\Carrusel;

use App\Models\Carrusel;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;

class EditarCarrusel {

    public static function editarImgCarrusel($request,$idAdmin){
        try {
            $archivoSubido=FALSE;
            $eliminarArchivo=FALSE;
            //verificar que venga un archivo
            if(!($request->exists('archivo'))){
                return response()->json([
                    "estadoArchivo"=>$archivoSubido,
                    "sms"=>"No se ha recibido ningún archivo de tipo caratula de proveedor",
                    "Siglas"=>"NEA"]);
            }
            $datos=$request->json()->all();
            //verificar si tiene permisos
            $idDesencriptado=Crypt::decrypt($idAdmin);
            $esAdmin=Proveedor::where("id",$idDesencriptado)
            ->where("estado",1)
            ->first();
            if(!$esAdmin){
                return response()->json(["sms"=>'El usuario '.$idAdmin.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
            }

            //buscar archivo para eliminarlo
            $buscarArchivo=Carrusel::where('id',$request['id'])->where("estado",1)->first();
            //necesito borrar el producto del directorio
            if($buscarArchivo){
                $archivoUbicacion=getenv('RUTA_CARRUSEL')."/".$buscarArchivo['archivo'];
                if(unlink($archivoUbicacion)){
                    $eliminarArchivo=true;
                }
            }
             //nombreArchivo
            $file = $request->file('archivo');
            $archivoNombre =$file->getClientOriginalName();
            //subir el archivo al servidor
            if($file->move(getenv('RUTA_CARRUSEL'), $archivoNombre)){
                 $archivoSubido=true;
            }
            if($archivoSubido){
                $actulizarCarrusel=Carrusel::where("id",$request['id'])
                ->update(array('archivo'=>$archivoNombre));
                if(!$actulizarCarrusel){
                    return response()->json(["archivo"=> $archivoNombre,
                                            "archivoNuevo"=>$archivoSubido,
                                            "archivoAnterior"=>$eliminarArchivo,
                                            "actulizado"=>$actulizarCarrusel,
                                            "sms"=>"Operación no exitosa",
                                            "Siglas"=>"ONE"]);
                }
                return response()->json(["archivo"=> $archivoNombre,
                                            "archivoNuevo"=>$archivoSubido,
                                            "archivoAnterior"=>$eliminarArchivo,
                                            "actulizado"=>$actulizarCarrusel,
                                            "sms"=>"Operación exitosa",
                                            "Siglas"=>"OE"]);
            }
            return response()->json(["archivo"=> $archivoNombre,
                                    "archivoNuevo"=>$archivoSubido,
                                    "archivoAnterior"=>$eliminarArchivo,
                                    "sms"=>"Archivo no se pudo subir ",
                                    "Siglas"=>"ANS"]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }

    }
}
