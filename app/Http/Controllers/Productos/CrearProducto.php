<?php
namespace App\Http\Controllers\Productos;

use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
use Intervention\Image\Facades\Image;
use Kunnu\Dropbox\Dropbox;
use App\Traits\DropboxBoostrap;
use Kunnu\Dropbox\Exceptions\DropboxClientException;
class CrearProducto {
  use DropboxBoostrap;
  public static  function subirDemo($request,$idProveedor){
    $archivoSubido=false;
    try {
        //pregunto si existe archivo
        if(!($request->exists('fileDemo'))){

            return response()->json([
                                    "estadoArchivo"=>$archivoSubido,
                                    "sms"=>"No se ha recibido ningún archivo de tipo demo",
                                    "Siglas"=>"NEA"]);
        }
        //Preguntamos si existe el usuario
        $idDesencriptado=Crypt::decrypt($idProveedor);
        $esProveedor=Proveedor::where("id",$idDesencriptado)
                            ->where("estado",1)
                            ->first();
        if(!$esProveedor){
            return response()->json(["sms"=>'El usuario'.$idProveedor.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
        }
        //nombreArchivo
        $file = $request->file('fileDemo');
        $archivoNombre =$file->getClientOriginalName();
        //vertificar si existe un archivo con el mismo nombre
        $archivoRepetido=file_exists(getenv('RUTA_DEMO')."/".$archivoNombre);
        if($archivoRepetido){
            return response()->json([
                                    "nombreArchivo"=> $archivoNombre,
                                    "estadoArchivo"=>$archivoSubido,
                                    "sms"=>"Ya existe un archivo de tipo Demo con el mismo nombre de ".$archivoNombre." ",
                                    "Siglas"=>"AR"]);
        }
        //si no existe el archivo si no esta repetido entonces lo puedo subir
        if($file->move(getenv('RUTA_DEMO'), $archivoNombre)){
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
                                    "Siglas"=>"ONE"]);
    }
  }
  //subir a dropbox
  public static function subirRemix($request,$idProveedor){
    $archivoSubido=false;

    try {
        //pregunto si existe archivo
        if(!($request->exists('fileRemix'))){

            return response()->json([
                                    "estadoArchivo"=>$archivoSubido,
                                    "sms"=>"No se ha recibido ningún archivo de tipo Remix ",
                                    "Siglas"=>"NEA"]);
        }
        
        //Preguntamos si existe el usuario
        $idDesencriptado=Crypt::decrypt($idProveedor);
        $esProveedor=Proveedor::where("id",$idDesencriptado)
                            ->where("estado",1)
                            ->first();

        if(!$esProveedor){
            return response()->json(["sms"=>'El usuario'.$idProveedor.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
        }
        //nombreArchivo
        $file = $request->file('fileRemix');
        $archivoNombre =$file->getClientOriginalName();


    } catch (\Throwable $th) {
        return response()->json([
                                    "archivoSubido"=>$archivoSubido,
                                    "sms"=>$th->getMessage(),
                                    "Siglas"=>"ERROR"]);
    }
    //interactual con dropbox
    $dropbox = new Dropbox(DropboxBoostrap::configDropbox());
    //configurar nombe archivo segun lo q pide dropbox
    $nombreDropbox="/".$archivoNombre;
    try {
        //si da error es q no existe el archivo
        $dropbox->simpleUpload($file,$nombreDropbox, ['autorename' => false]);
        $archivoSubido=true;
        return response()->json([
            "estadoArchivo"=>$archivoSubido,
            "nombreArchivo"=>$archivoNombre,
            "sms"=>'Operación exitosa ',
            "Siglas"=>"OE"]);

    } catch (DropboxClientException $e) {
        return response()->json([   "nombreArchivo"=> $archivoNombre,
                                    "estadoArchivo"=>$archivoSubido,
                                    "sms"=>"Error en dropbox ".$e->getMessage(),
                                    "Siglas"=>"ERROR"]); 
    }
  }
  public static function crearProducto($request,$idProveedor){
    try {

        if(!$request->json()){
            return response()->json(["mensaje"=>"La data no tiene formato deseado","Siglas"=>"DNF",'res'=>null]);
        }
        $datos=$request->json()->all();
        $idDesencriptado=Crypt::decrypt($idProveedor);
        $esProveedor=Proveedor::where("id",$idDesencriptado)
                            ->where("estado",1)
                            ->first();
        if(!$esProveedor){
            return response()->json(["sms"=>'El usuario '.$idProveedor.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
        }
        $ObjProducto=new Producto();
        $ObjProducto->id_genero =$datos['id_genero'];
        $ObjProducto->id_proveedor =$esProveedor->id;
        $ObjProducto->precio=$datos['precio'];
        $ObjProducto->url_descarga=$datos['url_descarga'];
        $ObjProducto->url_directorio=$datos['url_directorio'];
        $ObjProducto->estado=$datos['estado'];
        $ObjProducto->caratula=$datos['caratula'];
        $ObjProducto->tipo_archivo=$datos['tipo_archivo'];
        $ObjProducto->save();
        return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>null]);
    } catch (\Throwable $th) {
        return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$request->json()->all()]);
    }

  }

  public static function subirCaratula($request,$idProveedor){
        try {
            if(!($request->exists('fileCaratula'))){
                return response()->json(["res"=>$request['fileCaratula'],
                                        "sms"=>"No se ha recibido ningún archivo de tipo Caratula ",
                                        "Siglas"=>"NEA"]);
            }
            //Preguntamos si existe el usuario
            $idDesencriptado=Crypt::decrypt($idProveedor);
            $esProveedor=Proveedor::where("id",$idDesencriptado)
                                ->where("estado",1)
                                ->first();

            if(!$esProveedor){
                return response()->json(["sms"=>'El usuario'.$idProveedor.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
            }
            $file = $request->file('fileCaratula');
            //comprimir archivo
            $alto=Image::make($file)->height();
            $ancho=Image::make($file)->width();
            $archivoNombre =$file->getClientOriginalName();
            $nombreArchivoTime=time().$archivoNombre;
            $estaComprimidoImg=false;
            // si es mayor a 500 px entonces q comprima y subirlo
            if($alto>=500 && $ancho >=500 ){
                $estaComprimidoImg=true;
                $img=Image::make($file->getRealPath());
                $img->resize(500, 500, function ($constraint) {
                    $constraint->aspectRatio();
                    //subir archivo
                })->save(getenv('RUTA_CARATULA').'/'.$nombreArchivoTime);
            }


            //verificar si el prodcuto tiene caratula
            //verificar si el prodcuto tiene caratula
            if($request['caratula']==''){
                //subir el archivo con el nombre nuevo
                if(!$estaComprimidoImg){
                    $file->move(getenv('RUTA_CARATULA'), $nombreArchivoTime);
                }
                $caratulaProducto=Producto::where('id',$request['id'])->update(array('caratula'=>$nombreArchivoTime));
                return response()->json(["res"=>$caratulaProducto,
                                        "sms"=>"Operación exitosa",
                                        "Siglas"=>"OE"]);
            }

            //el producto tiene caratula anterior
            //el producto tiene caratula anterior
            if($request['caratula']!==''){
                //eliminar archivo anterior

                $archivoUbicacion=getenv('RUTA_CARATULA')."/".$request['caratula'];
                if(unlink($archivoUbicacion)){
                    if(!$estaComprimidoImg){
                        $file->move(getenv('RUTA_CARATULA'), $nombreArchivoTime);
                    }
                    $caratulaProducto=Producto::where('id',$request['id'])->update(array('caratula'=>$nombreArchivoTime));
                    return response()->json(["res"=>$caratulaProducto,
                                            "sms"=>"Operación exitosa",
                                            "Siglas"=>"OE"]);
                }else{
                    $caratulaProducto=Producto::where('id',$request['id'])->update(array('caratula'=>$nombreArchivoTime));
                    return response()->json(["res"=>$caratulaProducto,
                                            "sms"=>'La caratula anterior '.$archivoNombre." no ha sido eliminado, pero si se pudo actulizar la nueva caratula",
                                            "Siglas"=>"CANL"]);
                }
            }
        } catch (\Throwable $th) {
            return response()->json(["res"=>$request->file('fileCaratula'),"sms"=>$th->getMessage(),"Siglas"=>"ERROR"]);
        }
    }
}
