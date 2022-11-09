<?php

namespace App\Http\Controllers\PedidoProducto;

use Illuminate\Support\Str;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class ListasPedidoProducto {

    public static function listarPedidoClienteUnidadPanelCliente($request,$idCliente){
        try {
            $idClienteDesencriptado=Crypt::decrypt($idCliente);
            //1.Preguntamos si existe el usuario
            $existeUsuario=Cliente::where('id',$idClienteDesencriptado)->first();

            if(!$existeUsuario){
                return response()->json(["sms"=>"El cliente  ".$idCliente." no tiene permisos","Siglas"=>"UNE"]);
            }
            $pedidoProducto= PedidoProducto::join("cliente","cliente.id","pedido_producto.id_cliente")
                                                ->join("pedido","pedido.id","pedido_producto.id_pedido")
                                                ->join("productos","productos.id","pedido_producto.id_producto")
                                                ->join("proveedor","proveedor.id","productos.id_proveedor")
                                                ->where("pedido.id",$request['id'])
                                                ->where("pedido.id_cliente",$idClienteDesencriptado)
                                                ->get();
            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$pedidoProducto]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }


    public static function listarPedidoClienteUnidadPanelAdmin($idPedido,$idAdmin){
        try {
            $idClienteDesencriptado=Crypt::decrypt($idAdmin);
            //1.Preguntamos si existe el usuario
            $existeUsuario=Proveedor::where('id',$idClienteDesencriptado)
                        ->where('tipo_usuario','Admin')
                        ->first();

            if(!$existeUsuario){
                return response()->json(["sms"=>"El cliente  ".$idAdmin." no tiene permisos","Siglas"=>"UNE"]);
            }

            $pedidoProducto= PedidoProducto::join("cliente","cliente.id","pedido_producto.id_cliente")
                                                ->join("pedido","pedido.id","pedido_producto.id_pedido")
                                                ->join("productos","productos.id","pedido_producto.id_producto")
                                                ->join("proveedor","proveedor.id","productos.id_proveedor")
                                                ->where("pedido.id",$idPedido)
                                                ->select('pedido_producto.*',
                                                'productos.url_directorio')
                                                ->get();
            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$pedidoProducto]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }

    //listar top productos vendidos
    public static function listarProductosVendidosTop(){
        try {
        $productos=PedidoProducto::join("productos","productos.id",
                                        "pedido_producto.id_producto")
                    ->join('pedido','pedido.id','pedido_producto.id_pedido')
                    ->join('proveedor','proveedor.id','productos.id_proveedor')
                    ->orderBy('pedido_producto.id', 'DESC')
                    ->select('pedido_producto.id',
                    'pedido_producto.id_producto',
                    'productos.precio',
                    'productos.url_directorio',
                    'productos.caratula',
                    'proveedor.img'
                    )
                    ->where("pedido.estado",1)
                    ->paginate(20);
        return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$productos]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
    public static function listarProductosVendidosProveedorIndividual($idProveedor){
        try {
        $idClienteDesencriptado=Crypt::decrypt($idProveedor);
        $ObjeProductosVendidos=new ListasPedidoProducto();
        $productos=$ObjeProductosVendidos->productosVendidosProveedor($idClienteDesencriptado);
        return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$productos]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }

    public static function productoVendidosProveedor($idProveedor){
        try {
        $ObjeProductosVendidos=new ListasPedidoProducto();
        $productos=$ObjeProductosVendidos->productosVendidosProveedor($idProveedor);
        return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$productos]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
    private function productosVendidosProveedor($idProveedor){
        return $productos=PedidoProducto::join("productos","productos.id",
                                        "pedido_producto.id_producto")
                    ->join('pedido','pedido.id','pedido_producto.id_pedido')
                    ->join('cliente','cliente.id','pedido_producto.id_cliente')
                    ->join('proveedor','proveedor.id','productos.id_proveedor')
                    ->orderBy('pedido_producto.id', 'DESC')
                    ->select('pedido_producto.id',
                    'pedido_producto.id_producto',
                    'pedido_producto.precio_real',
                    'productos.url_directorio',
                    'pedido_producto.metodo_compra',
                    'pedido.id AS id_factura',
                    'cliente.correo',
                    'pedido_producto.estado_pago_proveedor',
                    'pedido_producto.created_at',
                    'pedido_producto.updated_at'
                    )
                    ->where("pedido.estado",1)
                    ->where("pedido_producto.estado_pago_proveedor",0)
                    ->where("proveedor.id",$idProveedor)
                    ->where("pedido_producto.estado",1)
                    ->get();
    }
}
