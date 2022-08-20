<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class PedidoController extends Controller{

    public function listarPedidoPanelCliente($idCliente){
        return Pedido\ListarPedido::listarPedidoPanelCliente($idCliente);
    }
    public function listarPedidoPanelAdmin($idAdmin){
        return Pedido\ListarPedido::listarPedidoPanelAdmin($idAdmin);
    }
    public function crearPedidoPaypal(Request $request,$idCliente){
        return Pedido\CrearPedido::crearPedidoPaypal( $request,$idCliente);
    }
    public function crearPedidoMonedero(Request $request,$idCliente){
        return Pedido\CrearPedido::crearPedidoMonedero( $request,$idCliente);
    }
    public function crearPedidoMembresia(Request $request,$idCliente){
        return Pedido\CrearPedido::crearPedidoMembresia( $request,$idCliente);
    }
    public function crearPedidoPaymentez(Request $request,$idCliente){
        return Pedido\CrearPedido::crearPedidoPaymentez( $request,$idCliente);
    }

}
