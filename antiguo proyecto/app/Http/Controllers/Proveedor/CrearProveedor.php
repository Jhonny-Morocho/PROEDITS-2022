<?php

namespace App\Http\Controllers\Proveedor;

use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class CrearProveedor {

    public static function subirCaratula($request,$idAdmin){
        $archivoSubido=FALSE;
        try {
            //pregunto si existe archivo
            if(!($request->exists('fileCaratula'))){

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
            $file = $request->file('fileCaratula');
            $archivoNombre =$file->getClientOriginalName();
            //vertificar si existe un archivo con el mismo nombre
            $archivoRepetido=file_exists(getenv('RUTA_CARATULA_PROVEEDOR')."/".$archivoNombre);
            if($archivoRepetido){
                return response()->json([
                                        "nombreArchivo"=> $archivoNombre,
                                        "estadoArchivo"=>$archivoSubido,
                                        "sms"=>"Ya existe el archivo ".$archivoNombre." ",
                                        "Siglas"=>"AR"]);
            }
            //si no existe el archivo si no esta repetido entonces lo puedo subir
            if($file->move(getenv('RUTA_CARATULA_PROVEEDOR'), $archivoNombre)){
                 $archivoSubido=true;
            }
            return response()->json([   "nombreArchivo"=> $archivoNombre,
                                        "estadoArchivo"=>$archivoSubido,
                                        "sms"=>"Operación exitosa",
                                        "Siglas"=>"OE"]);
        } catch (\Throwable $th) {
            return response()->json([
                                        "archivoSubido"=>$archivoSubido,
                                        "sms"=>$th->getMessage(),
                                        "Siglas"=>"ERROR"]);
        }

    }

    public static function registrarProveedor($request,$idAdmin){
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
            $correoRepetido=Proveedor::where("correo",$datos['correo'])->first();
            if($correoRepetido){
            return response()->json(["sms"=>'El usuario '.$datos['correo'].' ya existe ',"Siglas"=>"UR",'res'=>$correoRepetido]);
            }
            $opciones=array('cost'=>12);
            $password_hashed=password_hash($datos['password'],PASSWORD_BCRYPT,$opciones);

            $ObjProducto=new Proveedor();
            $ObjProducto->nombre =$datos['nombre'];
            $ObjProducto->apellido =$datos['apellido'];
            $ObjProducto->apodo=$datos['apodo'];
            $ObjProducto->correo=$datos['correo'];
            $ObjProducto->tipo_usuario=$datos['tipo_usuario'];
            $ObjProducto->password=$password_hashed;
            $ObjProducto->img=$datos['img'];
            $ObjProducto->estado=$datos['estado'];
            $ObjProducto->save();
            if(!$ObjProducto){
                return response()->json(["sms"=>"Proveedor no registrado","Siglas"=>"PNR",'res'=>$ObjProducto]);
            }
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>null]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ONE",'res'=>null]);
        }
    }
}
