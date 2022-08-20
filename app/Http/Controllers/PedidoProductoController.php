<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PedidoProductoController extends Controller{

    public function listarPedidoClienteUnidadPanelCliente(Request $request,$idCliente){
        try {
            $this->validate($request, [
                'id' => 'required|numeric',
            ]);
            return PedidoProducto\ListasPedidoProducto::listarPedidoClienteUnidadPanelCliente($request,$idCliente);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$request->json()->all()]);
        }
    }
    //falta terminar
    public function listarPedidoClienteUnidadPanelAdmin(Request $request,$idAdmin){
        try {
            //
            $this->validate($request, [
                'id' => 'required|numeric',
            ]);
            $idPedido=$request['id'];
            return PedidoProducto\ListasPedidoProducto::listarPedidoClienteUnidadPanelAdmin($idPedido,$idAdmin);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
    public function resultadoCompraPaypal(Request $request){
        try {
            return PedidoProducto\EditarPedidoProducto::resultadoCompraProductosPaypal($request);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$request->json()->all()]);
        }
    }
    public function resultadoCompraPaymentez(Request $request){
        try {
            return PedidoProducto\EditarPedidoProducto::resultadoCompraProductosPaymentez($request);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$request->json()->all()]);
        }
    }
    public function descargarPedidoProductoPanelCliente(Request $request,$idCliente){
        try {
            $this->validate($request, [
                'id_pedido' => 'required|numeric',
                'id_producto' => 'required|numeric',
            ]);
            return PedidoProducto\DescargarProductosPedido::descargarProductoCliente($request,$idCliente);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$request->json()->all()]);
        }
    }
    public function listarProductosVendidosTop(){
        try {
            return PedidoProducto\ListasPedidoProducto::listarProductosVendidosTop();
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
    public function listarProductosVendidosProveedorIndividual($idProveedor){
        try {
            return PedidoProducto\ListasPedidoProducto::listarProductosVendidosProveedorIndividual($idProveedor);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
    public function productosVendidosReporte($idProveedor){
        try {
            return PedidoProducto\ReportePedidoProducto::ProductosVendidos($idProveedor);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
    public function productoVendidosProveedor($idProveedor){
        try {
            return PedidoProducto\ListasPedidoProducto::productoVendidosProveedor($idProveedor);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
    public function productoVendidosProveedorFiltro(Request $request,$idAdmin){
        try {
            $this->validate($request, [
                'fecha_incio' => 'required|string',
                'fecha_fin' => 'required|string'
            ]);
            return PedidoProducto\ReportePedidoProducto::ProductosVendidosFiltro($request,$idAdmin);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
    //pagar productos al proveedor
    public function pagarProductosProveedor(Request $request,$idAdmin){
        try {
            $this->validate($request, [
                'id_proveedor' => 'required|numeric',
                'fecha_incio' => 'required|string',
                'fecha_fin' => 'required|string'
            ]);
            return PedidoProducto\PagarProductosProveedor::pagarProductosProveedor($request,$idAdmin);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }

}
