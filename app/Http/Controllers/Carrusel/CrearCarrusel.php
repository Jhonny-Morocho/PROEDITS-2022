<?php

namespace App\Http\Controllers\Carrusel;

use App\Models\Carrusel;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;

class CrearCarrusel {

    public static function subirImgCarrusel($request,$idAdmin){
        $archivoSubido=FALSE;
        try {
            //pregunto si existe archivo
            if(!($request->exists('archivo'))){

                return response()->json([
                                        "estadoArchivo"=>$archivoSubido,
                                        "sms"=>"No se ha recibido ningún archivo de tipo caratula de proveedor",
                                        "Siglas"=>"NEA"]);
            }

            //Preguntamos si existe el usuario
            $idDesencriptado=Crypt::decrypt($idAdmin);
            $esProveedor=Proveedor::where("id",$idDesencriptado)
                                ->where("estado",1)
                                ->first();
            if(!$esProveedor){
                return response()->json(["sms"=>'El usuario'.$idAdmin.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
            }

            //nombreArchivo
            $file = $request->file('archivo');
            $archivoNombre =$file->getClientOriginalName();
            //vertificar si existe un archivo con el mismo nombre
            $archivoRepetido=file_exists(getenv('RUTA_CARRUSEL')."/".$archivoNombre);
            if($archivoRepetido){
                return response()->json([
                                        "archivo"=> $archivoNombre,
                                        "estadoArchivo"=>$archivoSubido,
                                        "sms"=>"Ya existe el archivo ".$archivoNombre." ",
                                        "Siglas"=>"AR"]);

            }
            //si no existe el archivo si no esta repetido entonces lo puedo subir
            if($file->move(getenv('RUTA_CARRUSEL'), $archivoNombre)){
                 $archivoSubido=true;
            }
            if($archivoSubido){
                $ObjCarrusel=new Carrusel();
                $ObjCarrusel->archivo =$archivoNombre;
                $ObjCarrusel->estado =1;
                $ObjCarrusel->save();
                if(!$ObjCarrusel){
                    return response()->json(["archivo"=> $archivoNombre,
                                            "estadoArchivo"=>$archivoSubido,
                                            "registrado"=>$ObjCarrusel,
                                            "sms"=>"Operación no exitosa",
                                            "Siglas"=>"ONE"]);
                }
                return response()->json(["archivo"=> $archivoNombre,
                                            "estadoArchivo"=>$archivoSubido,
                                            "registrado"=>$ObjCarrusel,
                                            "sms"=>"Operación exitosa",
                                            "Siglas"=>"OE"]);
            }
            return response()->json(["archivo"=> $archivoNombre,
                                    "estadoArchivo"=>$archivoSubido,
                                    "sms"=>"Archivo no se pudo subir ",
                                    "Siglas"=>"ANS"]);
        } catch (\Throwable $th) {
            return response()->json([
                                        "archivoSubido"=>$archivoSubido,
                                        "sms"=>$th->getMessage(),
                                        "Siglas"=>"ERROR"]);
        }

    }
}
