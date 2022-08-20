<?php

namespace App\Http\Controllers\Productos;

use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
use Kunnu\Dropbox\Dropbox;
use App\Traits\DropboxBoostrap;
class DescargarProducto {
    use DropboxBoostrap;
    public static function descargarProductoProveedor($request,$idProveedor){
        try {
            //verificar si es un admin o proveedor para que pueda descargar
            //Preguntamos si existe el usuario
            $idDesencriptado=Crypt::decrypt($idProveedor);
            $esProveedor=Proveedor::where("id",$idDesencriptado)
                                ->where("estado",1)
                                ->first();

            if(!$esProveedor){
                return response()->json(["sms"=>'El usuario'.$idProveedor.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
            }
            //buscamos el producto para validar si existe en la base de datos
            $producto=Producto::where('id',$request['id'])->first();
            if(!$producto){
                return response()->json([
                    "sms"=>"El producto ".$producto['url_descarga']." no existe en la base de datos",
                    "Siglas"=>"PNEBD"]);
            }
            //configurar nombe archivo para subirlo dropbox
            $nombreDropbox="/".$producto->url_descarga;
            //Configure Dropbox service
            $dropbox = new Dropbox(DropboxBoostrap::configDropbox());
            $fileTempLinkDownland = $dropbox->getTemporaryLink($nombreDropbox);
            $existeArchivo=true;
            return response()->json([
                "existeArchivo"=> $existeArchivo,
                "ruta"=>$fileTempLinkDownland->getLink(),
                "sms"=>"Operación exitosa",
                "Siglas"=>"OE"]);
        } catch (\Throwable $th) {
            return response()->json([
                "sms"=>$th->getMessage(),
                "Siglas"=>"ERROR"]);
        }
    }
}
