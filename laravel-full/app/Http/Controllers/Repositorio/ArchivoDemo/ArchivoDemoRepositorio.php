<?php

namespace App\Http\Controllers\Repositorio\ArchivoDemo;

use App\Models\Producto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use wapmorgan\Mp3Info\Mp3Info;
class ArchivoDemoRepositorio {
    
    public static function subirArchivoDemoSevidorProedit($request){
        $data=null;
        $archivoSubido=false;
        try {

            $file = $request->file('archivo_demo');

            $nombre =$file->getClientOriginalName();
            $archivo_demo=$nombre;
            $archivoRepetido=file_exists(getenv('RUTA_DEMO')."/".$archivo_demo);
    /*         if($archivoRepetido){
                $data=array('nombre'=>$nombre,
                            'archivo_demo'=>$archivo_demo,
                            'archivo_repetido'=>$archivoRepetido,
                            'archivo_subido'=>$archivoSubido);
                return response()->json(["message"=>"Existe un archivo de Tipo DEMO  con el nombre de  ".$archivo_demo." ",'data'=>$data],200);
            } */
            //si no existe el archivo si no esta repetido entonces lo puedo subir
            if($file->move(getenv('RUTA_DEMO'), $archivo_demo)){
                $archivoSubido=true;
            }
            $data=array('nombre'=>$nombre,
                        'archivo_demo'=>$archivo_demo,
                        'archivo_repetido'=>$archivoRepetido,
                        'archivo_subido'=>$archivoSubido);
            return response()->json(["message"=>'success','data'=>$data],201);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$data],400);
        }
    }
    public static function actualizarArchivoDemoSevidorProedit($request){
        $data=null;
        $archivoSubido=false;
        $archivoEliminado=false;
        try {
            //eliminar archivo antiguo
            $tipoArchivo=Producto::where('id',$request->id)->first();
            //necesito borrar el producto del directorio
            $archivoUbicacion=getenv('RUTA_DEMO')."/".$tipoArchivo->archivo_demo;
    
            $existeArchivo=file_exists(getenv('RUTA_DEMO')."/".$tipoArchivo->archivo_demo);
            if($existeArchivo){
                unlink($archivoUbicacion);
                $archivoEliminado=true;
            }
            //subir el nuevo archivo
            $file = $request->file('archivo_demo');
            $nombre =$file->getClientOriginalName();
            $archivo_demo='demo - '.time().' - '.$nombre;
            //verificar si el archivo existe o esta reperitido
            $archivoRepetido=file_exists(getenv('RUTA_DEMO')."/".$archivo_demo);
            if($archivoRepetido){
                $data=array('nombre'=>$nombre,
                            'archivo_demo'=>$archivo_demo,
                            'archivo_eliminado'=>$archivoEliminado,
                            'archivo_repetido'=>$archivoRepetido,
                            'archivo_subido'=>$archivoSubido);
                return response()->json(["message"=>"Existe un archivo de Tipo DEMO  con el nombre de  ".$archivo_demo." ",'data'=>$data],200);
            }
            //si no existe el archivo si no esta repetido entonces lo puedo subir
            if($file->move(getenv('RUTA_DEMO'), $archivo_demo)){
                $archivoSubido=true;
            }
            $data=array('nombre'=>$nombre,
                        'archivo_demo'=>$archivo_demo,
                        'archivo_eliminado'=>$archivoEliminado,
                        'archivo_repetido'=>$archivoRepetido,
                        'archivo_subido'=>$archivoSubido);
            return response()->json(["message"=>'success','data'=>$data],201);
  
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$data],400);
        }
    }


}
