<?php

namespace App\Http\Controllers\PedidoProducto;

use Illuminate\Support\Str;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
class ReportePedidoProducto {

    public static function ProductosVendidos($idAdmin){
        try {
            $idClienteDesencriptado=Crypt::decrypt($idAdmin);
            //1.Preguntamos si existe el usuario
            $existeUsuario=Proveedor::where('id',$idClienteDesencriptado)
                        ->where('tipo_usuario','Admin')
                        ->first();
            if(!$existeUsuario){
                return response()->json(["sms"=>"El Proveedor  ".$idAdmin." no tiene permisos","Siglas"=>"UNE"]);
            }

            $pedidoProducto= PedidoProducto::join("cliente","cliente.id","pedido_producto.id_cliente")
                                                ->join("pedido","pedido.id","pedido_producto.id_pedido")
                                                ->join("productos","productos.id","pedido_producto.id_producto")
                                                ->join("proveedor","proveedor.id","productos.id_proveedor")
                                                ->select('pedido_producto.*',
                                                'cliente.correo',
                                                'pedido.estado as estado_pedido',
                                                'productos.url_directorio')
                                                ->where("pedido.estado",1)
                                                ->orderBy('id', 'DESC')
                                                ->get();
            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$pedidoProducto]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
    public static function ProductosVendidosFiltro($request,$idAdmin){
        try {
            $idClienteDesencriptado=Crypt::decrypt($idAdmin);
            //1.Preguntamos si existe el usuario
            $existeUsuario=Proveedor::where('id',$idClienteDesencriptado)
                        ->where('tipo_usuario','Admin')
                        ->first();
            if(!$existeUsuario){
                return response()->json(["sms"=>"El Proveedor  ".$idAdmin." no tiene permisos","Siglas"=>"UNE"]);
            }

            $pedidoProducto= PedidoProducto::join("cliente","cliente.id","pedido_producto.id_cliente")
                                                ->join("pedido","pedido.id","pedido_producto.id_pedido")
                                                ->join("productos","productos.id","pedido_producto.id_producto")
                                                ->join("proveedor","proveedor.id","productos.id_proveedor")
                                                ->select('pedido_producto.*',
                                                'cliente.correo',
                                                'pedido.estado as estado_pedido',

                                                'productos.url_directorio')
                                                ->whereBetween('pedido_producto.created_at', [$request['fecha_incio'], $request['fecha_fin']])
                                                ->where("pedido.estado",1)
                                                ->orderBy('id', 'DESC')
                                                ->get();

            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$pedidoProducto]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }


}
