<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClienteMembresiaController extends Controller{
 

    public function crearPedidoPaypalMembresia(Request $request,$idCliente){
        try {
            return ClienteMembresia\CrearClienteMembresia::crearclienteMembresiaPaypal( $request,$idCliente);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$request->json()->all()]);
        }
    }
    public function crearPedidoPaymentezMembresia(Request $request,$idCliente){
        try {
            return ClienteMembresia\CrearClienteMembresia::crearclienteMembresiaPaymentez( $request,$idCliente);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$request->json()->all()]);
        }
    }
    public function crearPedidoDepositoMembresia(Request $request,$idCliente){
        try {

            return ClienteMembresia\CrearClienteMembresia::crearclienteMembresiaDeposito( $request,$idCliente);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$request->json()->all()]);
        }
    }
    public function resultadoCompraPaypal(Request $request){
        try {
            return ClienteMembresia\EditarMembresiaCliente::resultadoCompraProductosPaypal( $request);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$request->json()->all()]);
        }
    }
    public function resultadoCompraPaymentez(Request $request){
        try {
            return ClienteMembresia\EditarMembresiaCliente::resultadoCompraProductosPaymentez( $request);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$request->json()->all()]);
        }
    }
    public function listarMembresiaClientePanelCliente(Request $request,$idCliente){
        try {
            return ClienteMembresia\ListarClienteMembresia::listarClienteMembresiaPanelCliente( $request,$idCliente);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$request->json()->all()]);
        }
    }
    public function listarClienteMembresiaPanelAdmin($idAdmin){
        try {
            return ClienteMembresia\ListarClienteMembresia::listarClienteMembresiaPanelAdmin($idAdmin);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
}
