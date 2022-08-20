<?php

namespace App\Http\Controllers\Productos;

use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Kunnu\Dropbox\Dropbox;
use App\Traits\DropboxBoostrap;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
use Kunnu\Dropbox\Models\FileMetadata;
class EliminarProducto {
    use DropboxBoostrap;
  //listar productos en la tienda
  public static function eliminarArchivoUrlDescarga($request,$idProveedor){
    $archivoEliminado=false;
    try {
        $datos=$request->json()->all();
        $idDesencriptado=Crypt::decrypt($idProveedor);
        $esProveedor=Proveedor::where("id",$idDesencriptado)
        ->where("estado",1)
        ->first();
        if(!$esProveedor){
            return response()->json(["sms"=>'El usuario '.$idProveedor.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
        }
        $tipoArchivo=Producto::where('id',$datos['id'])->first();

    } catch (\Throwable $th) {
        return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
    }

    $dropbox = new Dropbox(DropboxBoostrap::configDropbox());
    //configurar nombe archivo segun lo q pide dropbox
    $nombreDropbox="/".$tipoArchivo->url_descarga;
    try {
        $dropbox->getMetadata($nombreDropbox);
        //necesito borrar el producto del directorio
        if($tipoArchivo->tipo_archivo==1){
            $dropbox->delete($nombreDropbox);
            $archivoEliminado=true;
            return response()->json(["sms"=>'El '.$tipoArchivo['url_descarga']." ha sido eliminado",
            'archivoEliminado'=>$archivoEliminado,
            "Siglas"=>"OE",'res'=>null]);
        }
        return response()->json(["sms"=>'El '.$tipoArchivo['url_descarga']." ha sido eliminado",
        'archivoEliminado'=>$archivoEliminado,
        "Siglas"=>"OE",'res'=>null]);
    } catch (DropboxClientException $e) {
        return response()->json(["sms"=>'El '.$tipoArchivo->url_descarga." ha sido eliminado",
        'archivoEliminado'=>$archivoEliminado,
        "Siglas"=>"OE",'res'=>null]);
    }

 }

 public static function eliminarArchivoUrlDirectorio(Request $request,$idProveedor){
    try {

        $datos=$request->json()->all();
        $archivoEliminado=false;
        $idDesencriptado=Crypt::decrypt($idProveedor);
        $esProveedor=Proveedor::where("id",$idDesencriptado)
        ->where("estado",1)
        ->first();
        if(!$esProveedor){
            return response()->json(["sms"=>'El usuario '.$idProveedor.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
        }
        $tipoArchivo=Producto::where('id',$datos['id'])->first();
        //necesito borrar el producto del directorio
        $archivoUbicacion=getenv('RUTA_DEMO')."/".$tipoArchivo['url_directorio'];
        $existeArchivo=file_exists(getenv('RUTA_DEMO')."/".$tipoArchivo['url_directorio']);
        if($existeArchivo){
            unlink($archivoUbicacion);
            $archivoEliminado=true;
        }
        return response()->json(["sms"=>'El '.$tipoArchivo['url_directorio']." ha sido eliminado",
                                "Siglas"=>"OE",
                                'archivoEliminado'=>$archivoEliminado,
                                'res'=>null]);

    } catch (\Throwable $th) {
        return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR"]);
    }

 }
   //elimino todo aqui// todo el producto
   public static function eliminarProductoLogicamente($request,$idProveedor) {
    try {

        $datos=$request->json()->all();
        //puede editar el proveedor o el admin
        $idDesencriptado=Crypt::decrypt($idProveedor);
        //verificar si es proveedor
        $esAdmin=Proveedor::where("id",$idDesencriptado)
        ->where("estado",1)
        ->where("tipo_usuario",'Admin')
        ->first();
        if(!$esAdmin){
            return response()->json(["sms"=>'El usuario '.$idProveedor.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
        }
        //si edita solo el demo archivo
        $existeProducto=Producto::where("id",$datos['id'])->first();
        if(!$existeProducto){
            return response()->json(["sms"=>"El producto con el idenficador ".$datos['id']." no existe en la base de datos",
            "Siglas"=>"ONE",
            'res'=>$existeProducto]);
        }

        $productoEliminado=Producto::where("id",$datos['id'])
        ->update(array('estado'=>$datos['estado']));

        //eliminar demo
        //vertificar si existe un archivo con el mismo nombre
        $archivoDemoEliminado=FALSE;
        if(!empty($existeProducto['url_directorio'])){
            $archivoDemo=getenv('RUTA_DEMO')."/".$existeProducto['url_directorio'];
            $existeDemo=file_exists($archivoDemo);
            if($existeDemo){
                if(unlink($archivoDemo)){
                    $archivoDemoEliminado=TRUE;
                }
            }
        }

        //eliminar remix
        $archivoRemixEliminado=FALSE;
        if(!empty($existeProducto['url_descarga'])){
            $archivoRemix=getenv('RUTA_REMIX')."/".$existeProducto['url_descarga'];
            $existeRemix=file_exists($archivoRemix);
            if($existeRemix){
                if(unlink($archivoRemix)){
                    $archivoRemixEliminado=TRUE;
                }
            }

        }

        //eliminar caratula//la carautla no debe ser vacio
        $archivoCaratulaEliminado=FALSE;
        if(!empty($existeProducto['caratula'])){
            $archivoCaratula=getenv('RUTA_CARATULA')."/".$existeProducto['caratula'];
            $existeCaratula=file_exists($archivoCaratula);
            if($existeCaratula){
                if(unlink($archivoCaratula)){
                    $archivoCaratulaEliminado=TRUE;
                }
            }
        }

        if(!$productoEliminado){
            return response()->json(["sms"=>"No se puede eliminar el producto ",
                                     "Siglas"=>"ONE",
                                     'remix'=>$archivoRemixEliminado,
                                    'demo'=>$archivoDemoEliminado,
                                    'caratula'=>$archivoCaratulaEliminado,
                                     'res'=>$productoEliminado]);
        }
        return response()->json(["sms"=>"Operación exitosa",
        'remix'=>$archivoRemixEliminado,
        'demo'=>$archivoDemoEliminado,
        'caratula'=>$archivoCaratulaEliminado,
        "Siglas"=>"OE",
        'res'=>$productoEliminado]);
   } catch (\Throwable $th) {
       return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
   }
 }
}
