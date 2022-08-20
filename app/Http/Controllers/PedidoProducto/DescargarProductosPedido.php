<?php

namespace App\Http\Controllers\PedidoProducto;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
use App\Traits\DropboxBoostrap;
use Kunnu\Dropbox\Dropbox;
class DescargarProductosPedido {
    use DropboxBoostrap;
    public static function descargarProductoCliente($request,$idCliente){
        try {
            $idDesencriptado=Crypt::decrypt($idCliente);
            $esCliente=Pedido::where("id_cliente",$idDesencriptado)
                                ->where("id",$request['id_pedido'])
                                ->first();

            if(!$esCliente){
                return response()->json(["sms"=>'El usuario'.$idCliente.' no tien permisos sobre este pedido',"Siglas"=>"NTP",'res'=>null]);
            }
            if($esCliente->estado==0){
                return response()->json(["sms"=>'No puede descargar este producto por que su pedido no esta completado',"Siglas"=>"PNC",'res'=>null]);
            }

            //verificar que el producto exista en el pedido-producto
            $existeProductoPedido=PedidoProducto::where('id_producto',$request['id_producto'])
                                                ->where('id_cliente',$idDesencriptado)
                                                ->where('id_pedido',$request['id_pedido'])
                                                ->first();
            if(!$existeProductoPedido){
                return response()->json(["sms"=>'Este producto no pertence al producto pedido de su pedido',"Siglas"=>"PNPPP",'res'=>null]);
            }
            //buscamos el producto para validar si existe en la base de datos
            $producto=Producto::where('id',$request['id_producto'])->first();
            if(!$producto){
                return response()->json([
                    "sms"=>"El producto ".$producto['url_directorio']." no existe en la base de datos",
                    "Siglas"=>"PNEBD"]);
            }

            //SI ES CON LINK PARA DIRECTO A DESCARGAR
            if($producto['tipo_archivo']=='0'){
                return response()->json([
                    "existeArchivo"=> FALSE,
                    "tipo_archivo"=>$producto['tipo_archivo'],
                    "ruta"=>$producto['url_descarga'],
                    "producto"=>$producto['url_descarga'],
                    "sms"=>"Operación exitosa",
                    "Siglas"=>"OE"]);
            }

            //productos en archivo
            if($producto['tipo_archivo']=='1'){
                //configurar nombe archivo para subirlo dropbox
                $nombreDropbox="/".$producto->url_descarga;
                //Configure Dropbox service
                $dropbox = new Dropbox(DropboxBoostrap::configDropbox());
                $fileTempLinkDownland = $dropbox->getTemporaryLink($nombreDropbox);
                $existeArchivo=true;
                return response()->json([
                    "existeArchivo"=> $existeArchivo,
                    "ruta"=>$fileTempLinkDownland->getLink(),
                    "tipo_archivo"=>$producto['tipo_archivo'],
                    "producto"=>$producto->url_descarga,
                    "sms"=>"Operación exitosa",
                    "Siglas"=>"OE"]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "sms"=>$th->getMessage(),
                "Siglas"=>"ERROR"]);
        }
    }
}
