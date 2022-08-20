<?php

namespace App\Http\Controllers\PedidoProducto;

use Illuminate\Support\Str;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use Traits\configPaypalTrains;
use App\Traits\PaypalBootstrap;
use App\Traits\TemplateCorreo;
use App\Traits\TemplateCorreoPaymentez;
use PHPUnit\Framework\Constraint\Count;

class EditarPedidoProducto {

    use PaypalBootstrap;
    use TemplateCorreo;
    use TemplateCorreoPaymentez;

    public static  function resultadoCompraProductosPaypal($request){

        if(!isset($request['paymentId']) && !isset($request['PayerID']) ){
            $res="No existe las variables paymentId & PayerID";
            return redirect(getenv("APP_URL").'/#/resultado-compra?estado=FALSE&sms='.$res);
        }
        $paymentId = $request['paymentId'];
        $payment = Payment::get($paymentId,PaypalBootstrap::configPaypal());
        $payerId = $request['PayerID'];
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);
        try {

            // aqui completas la transaccion
            // $payment->execute consulta en segundo plano si la transaccion fue exitosa
            // Si fue exitosa retorna un HTTP 200 y devuelve un objeto
            // que se almacena el $result
            // Si el procedo no fue completado con exito retorna un HTTP 4XX y un objeto
            // con los posibles motivos del error
            // aqui tienes un ejemplo https://paypal.github.io/PayPal-PHP-SDK/sample/doc/payments/ExecutePayment.html
            $result = $payment->execute($execution, PaypalBootstrap::configPaypal());

            // haces un dump del objeto para que veras toda la
            // info que proporciona
            //pago no aprobado

            if($result->state != "approved") {
                return redirect(getenv("APP_URL").'/#/resultado-compra?estado=FALSE&sms='.$result->state);
            }


            //activamos el estado de la factura para que esten activos
            $objPedido=Pedido::where("id_transaccion",$request['id_transaccion'])->first();

            //actulizar el pedido
            $pedido=Pedido::where("id_transaccion",$request['id_transaccion'])
                            ->update(array("estado"=>1));
            //actuliza el pedido_producto
            $pedidoProductos=PedidoProducto::where('id_pedido',$objPedido->id)->get();
            foreach ($pedidoProductos as $key => $value) {
                PedidoProducto::where("id_pedido",$objPedido->id)
                                ->where('id_producto',$value['id_producto'])
                                ->update(array("estado"=>1));
            }
            //enviar notificacion de compra exitosa al correo del cliente
            if(!$pedido){
                $sms="NO SE PUDO ACTULIZAR EL ESTADO DE SU FACTURA, PARA MAS INFORMACIÓN CONTACTESE CON PROEDITSCLUB";
                return redirect(getenv("APP_URL").'/#/resultado-compra?estado=FALSE&sms='.$sms);
            }
            $cliente=Pedido::where("id_transaccion",$request['id_transaccion'])->first();
            $usuarioCliente=Cliente::where('id',$cliente->id_cliente)->first();
            //prepara la factura
            $facturaCorreo=TemplateCorreo::templateFacturaPaypalProductos($request['id_transaccion']);
            //enviar al cliente
            TemplateCorreo::enviarCorreo($facturaCorreo,$usuarioCliente->correo,"CONFIRMACIÓN DE COMPRA PROEDITSCLUB.COM");
            //enviar al administrador
            TemplateCorreo::enviarCorreo($facturaCorreo,getenv("CORREO_ADMIN"),"CONFIRMACIÓN DE COMPRA PROEDITSCLUB.COM");
            TemplateCorreo::enviarCorreo($facturaCorreo,getenv("CORREO"),"CONFIRMACIÓN DE COMPRA PROEDITSCLUB.COM");
            return redirect(getenv("APP_URL").'/#/resultado-compra?estado=TRUE');


        } catch (\Throwable $th) {

            return redirect(getenv("APP_URL").'/#/resultado-compra?estado=FALSE&sms='.$th->getMessage());
        }
    }
    public static  function resultadoCompraProductosPaymentez($request){
        try {

            if(!isset($request['transaction']['current_status']) && !isset($request['transaction']['dev_reference']) ){
                $res="No existe las variables current_status & dev_reference";
                return response()->json(["sms"=>$res,"Siglas"=>"ONE",'res'=>getenv("APP_URL").'/#/resultado-compra?estado=FALSE&sms='.$res]);

            }
            if($request['transaction']['current_status']!='APPROVED'){
                return response()->json(["sms"=>$request['transaction']['current_status'],"Siglas"=>"ONE",'res'=>getenv("APP_URL").'/#/resultado-compra?estado=FALSE&sms='.$request['transaction']['current_status']]);
            }
            //activamos el estado de la factura para que esten activos
            $objPedido=Pedido::where("id_transaccion",$request['transaction']['dev_reference'])->first();
            //actulizar el pedido
            $auxForm=array(json_decode($objPedido->form_factura));
            $resPaymentez=array('transaction'=>$request['transaction'],'card'=>$request['card']);
            array_push($auxForm,$resPaymentez);
            $pedido=Pedido::where("id_transaccion",$request['transaction']['dev_reference'])->update(array("estado"=>1,"form_factura"=>$auxForm));
            //enviar notificacion de compra exitosa al correo del cliente
            if(!$pedido){
                $sms="NO SE PUDO ACTULIZAR EL ESTADO DE SU FACTURA, PARA MAS INFORMACIÓN CONTACTESE CON PROEDITSCLUB";
                return redirect(getenv("APP_URL").'/#/resultado-compra?estado=FALSE&sms='.$sms);
            }
            $cliente=Pedido::where("id_transaccion",$request['transaction']['dev_reference'])->first();
            $usuarioCliente=Cliente::where('id',$cliente->id_cliente)->first();
            //prepara la factura
            $idTranPaymentez=$request['transaction']['id'];
            $codifoAutorizacinPaymentez=$request['transaction']['authorization_code'];
            $facturaCorreo=TemplateCorreoPaymentez::templateFacturaPaymentezProductos($request['transaction']['dev_reference'],$idTranPaymentez,$codifoAutorizacinPaymentez);
            TemplateCorreo::enviarCorreo($facturaCorreo,$usuarioCliente->correo,"CONFIRMACIÓN DE COMPRA PROEDITSCLUB.COM");
            //enviar al administrador
            TemplateCorreo::enviarCorreo($facturaCorreo,getenv("CORREO_ADMIN"),"CONFIRMACIÓN DE COMPRA PROEDITSCLUB.COM");
            TemplateCorreo::enviarCorreo($facturaCorreo,getenv("CORREO"),"CONFIRMACIÓN DE COMPRA PROEDITSCLUB.COM");
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>getenv("APP_URL").'/#/resultado-compra?estado=TRUE']);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>getenv("APP_URL").'/#/resultado-compra?estado=FALSE&sms='.$th->getMessage()]);
        }
    }


}
