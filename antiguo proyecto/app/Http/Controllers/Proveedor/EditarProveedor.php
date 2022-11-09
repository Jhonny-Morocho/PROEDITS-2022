<?php

namespace App\Http\Controllers\Proveedor;

use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class EditarProveedor {

    public static function editarCaratula($request,$idAdmin){

        $archivoSubido=FALSE;
        $archivoAntiguoEliminado=FALSE;
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
            $fileNuevo = $request->file('fileCaratula');
            $archivoAntiguoEliminado=EliminarProveedor::eliminarArchivo($request['fileAntiguo']);
            $nomArchivoNuevo=time().$fileNuevo->getClientOriginalName();
            //si no existe el archivo si no esta repetido entonces lo puedo subir
            if($fileNuevo->move(getenv('RUTA_CARATULA_PROVEEDOR'), $nomArchivoNuevo)){
                 $archivoSubido=true;
            }
            return response()->json([   "nombreArchivo"=> $nomArchivoNuevo,
                                        "archivoNuevoSubido"=>$archivoSubido,
                                        "archivoAntiguoEliminado"=>$archivoAntiguoEliminado,
                                        "sms"=>"Operación exitosa",
                                        "Siglas"=>"OE"]);
        } catch (\Throwable $th) {
            return response()->json([
                                        "archivoSubido"=>$archivoSubido,
                                        "archivoAntiguoEliminado"=>$archivoAntiguoEliminado,
                                        "sms"=>$th->getMessage(),
                                        "Siglas"=>"ONE"]);
        }
    }
    public static function editarProveedor($request,$idAdmin,$idProveedor){
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

           //actuliza texto plano sin contraseña
           if($datos["password"]!==""){
                $opciones=array('cost'=>12);
                $passwordProveedor=$datos["password"];
                $password_hashed=password_hash($passwordProveedor,PASSWORD_BCRYPT,$opciones);
                $editarProveedorNoPassword=Proveedor::where("id",$idProveedor)
                ->where("estado",1)
                ->update(array
                            ('nombre'=>$datos['nombre'],
                              'apellido'=>$datos['apellido'],
                              'img'=>$datos['img'],
                              'password'=>$password_hashed
                            )
                );
                if($editarProveedorNoPassword){
                    return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$editarProveedorNoPassword]);
                }
                return response()->json(["sms"=>"No se puedo actualizar el password","Siglas"=>"ONE",'res'=>$editarProveedorNoPassword]);
           }else{
               $editarProveedorNoPassword=Proveedor::where("id",$idProveedor)
               ->where("estado",1)
               ->update(array
                           ('nombre'=>$datos['nombre'],
                             'apellido'=>$datos['apellido'],
                             'img'=>$datos['img']
                           )
               );
               if(!$editarProveedorNoPassword){
                   return response()->json(["sms"=>"No se puedo actualizar el password","Siglas"=>"ONE",'res'=>$editarProveedorNoPassword]);
                }
                return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$editarProveedorNoPassword]);

           }
       } catch (\Throwable $th) {
           return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
       }
    }
}
