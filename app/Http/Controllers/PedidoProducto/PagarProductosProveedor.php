<?php

namespace App\Http\Controllers\PedidoProducto;

use Illuminate\Support\Str;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class PagarProductosProveedor {

    public static function pagarProductosProveedor($request,$idAdmin){
        try {
            $idAdminDesencriptado=Crypt::decrypt($idAdmin);
            //1.Preguntamos si existe el usuario
            $existeUsuario=Proveedor::where('id',$idAdminDesencriptado)->where('tipo_usuario','Admin')->first();

            if(!$existeUsuario){
                return response()->json(["sms"=>"El cliente  ".$idAdmin." no tiene permisos","Siglas"=>"UNE"]);
            }

            $pagarProveedor=PedidoProducto::join("productos","productos.id",
                            "pedido_producto.id_producto")
                ->join('pedido','pedido.id','pedido_producto.id_pedido')
                ->join('cliente','cliente.id','pedido_producto.id_cliente')
                ->join('proveedor','proveedor.id','productos.id_proveedor')
                ->select('pedido_producto.id',
                'pedido_producto.id_producto',
                'pedido_producto.precio_real',
                'productos.id',
                'pedido_producto.metodo_compra',
                'pedido.id AS id_factura',
                'cliente.correo',
                'pedido_producto.estado_pago_proveedor'
                )
                ->where("pedido.estado",1)
                ->whereBetween('pedido_producto.created_at', [$request['fecha_incio'], $request['fecha_fin']])
                ->where("pedido_producto.estado_pago_proveedor",0)
                ->where("proveedor.id",$request['id_proveedor'])
                ->where("pedido_producto.estado",1)
                ->update(array("estado_pago_proveedor"=>1));
            if(!$pagarProveedor){
                return response()->json(["sms"=>"No se puede realizar la operacion de pagar a los proveedores","Siglas"=>"ONE",'res'=>$pagarProveedor]);
            }
            //devuelve el numero de productos actulizados
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$pagarProveedor]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }

}
