<?php

namespace App\Http\Controllers\Pedido;

use App\Http\Controllers\Cupon\ListarCupon;
use Illuminate\Support\Str;
use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use App\Models\Cliente;
use App\Models\ClienteMembresia;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use App\Models\Producto;
use App\Models\Cupon;
use Illuminate\Support\Facades\Crypt;
use App\Traits\PaypalBootstrap;
use App\Traits\UUID;
use Carbon\Carbon;
use App\Traits\TemplateCorreo;
class CrearPedido {
    use PaypalBootstrap;
    use UUID;
    use TemplateCorreo;

    public static function crearPedidoPaypal($request,$idCliente){
        //creamos el pedido
        try {
            if(!($request->json())){
                return response()->json(["sms"=>"La data no tiene el formato requerido","Siglas"=>"ONE",'res'=>null]);
            }
            //verificar si el id del usuario existe
            $idDesencriptado=Crypt::decrypt($idCliente);
            $existesUsuario=Cliente::where("id",$idDesencriptado)->first();
            if(!$existesUsuario){
                return response()->json(["sms"=>"El usuario ".$idCliente." no existe en la base de datos","Siglas"=>"ONE",'res'=>null]);
            }
            $productos=($request->json()->all())['productos'];
            $auxProductos=[];
            //verificar id de los productos y tambien el precio
            $aplicaPromocion=FALSE;
            foreach ($productos as $key => $value) {
                //verificar si el id del producto existe
                $bdProducto=Producto::where('id',$value['id'])->first();
                if(!$bdProducto){
                    return response()->json(["sms"=>"El producto ".$value['url_directorio']." con el identificador ".$productos[$key]['id']." no ha sido encontrado","Siglas"=>"ONE",'res'=>null]);
                }
                //desencripto los productos
                $auxProductos[$key]['id']=$bdProducto->id;
                $auxProductos[$key]['url_directorio']=$bdProducto->url_directorio;

                //aplicar promocion del productos
                $cupon=Cupon::where("estado",1)->orderBy('id', 'ASC')->first();
                if($cupon && count($productos)>=($cupon->monto)){
                    $aplicaPromocion=TRUE;
                    $auxProductos[$key]['precio']=sprintf('%0.2f',(($bdProducto->precio)-($bdProducto->precio)*($cupon->descuento)/100));
                }else{
                    $auxProductos[$key]['precio']=$bdProducto->precio;
                }

            }

            //enviamos los datos a la api de paypal
            $ObjPayerCompra=new Payer();
            $ObjPayerCompra->setPaymentMethod('paypal');
            $arregloProductos=array();
            $descripcionProducto="";
            $sumaTotalCancelar=0;

            foreach ($auxProductos as  $key => $value) {
                ${"articulo$key"}=new Item();
                $arregloProductos[]=${"articulo$key"};
                ${"articulo$key"}->setName($descripcionProducto.''.$auxProductos[$key]['url_directorio'])//el i lleva el nombre de la cancion
                                ->setCurrency('USD')//la moneda a cobrar
                                ->setQuantity((int)1)//siempre la cancion va hacer (1)
                                ->setSku($auxProductos[$key]['id'])
                                ->setPrice((double)$auxProductos[$key]['precio'] );//precio de la cancion

                            $sumaTotalCancelar=(double)$auxProductos[$key]['precio']+$sumaTotalCancelar;
            }

            $listaArticulos=new ItemList();
            $listaArticulos->setItems($arregloProductos);

            $cantidad=new Amount();
            $cantidad->setCurrency('USD')
                    ->setTotal((double)$sumaTotalCancelar);//total a pagar con 3 producto(2 cancio9n y un boton)


             //=================caractersiticas de la transaccion=============
             $transaccion= new Transaction();
             $transaccion->setAmount($cantidad)
                         ->setItemList($listaArticulos)
                         ->setDescription('Proeditsclub.com')
                         ->setInvoiceNumber(uniqid()); //registro numero unico de esa trasaccion
             $ID_registro=$transaccion->getInvoiceNumber();

            //crear factura
            $formCliente=($request->json()->all())['formCliente'];
            $arrayFormCliente=array("productos"=>$auxProductos,
                                    'id_transanccion'=>$ID_registro,
                                    'aplicar_promocion'=>$aplicaPromocion,
                                    "formCliente"=>$formCliente);
            //return $formCliente[0];
            //formula de paypal :: 5,4% mas 0,30 centavos por transaccion
            $comisionPaypalFactura=((5.4/100)*$sumaTotalCancelar)+0.30;
            $pedido=new Pedido();
            $pedido->total_venta=$sumaTotalCancelar;
            $pedido->total_real=(double)($sumaTotalCancelar-$comisionPaypalFactura);
            $pedido->id_cliente =$idDesencriptado;
            $pedido->estado=0;
            $pedido->metodo_compra="Paypal";
            $pedido->form_factura=json_encode($arrayFormCliente);
            $pedido->id_transaccion=$ID_registro;
            $pedido->pais_compra=$formCliente['pais_compra'];
            $pedido->ciudad=$formCliente['ciudad'];
            $pedido->correo=$formCliente['correo'];
            $pedido->ip_cliente=$formCliente['ip_cliente'];
            $pedido->telefono=$formCliente['telefono'];
            $pedido->direccion=$formCliente['direccion'];
            $pedido->documento_identidad=$formCliente['documento_identidad'];
            $pedido->nombre=$formCliente['nombre'];
            $pedido->apellido=$formCliente['apellido'];
            $pedido->aceptar_terminos=$formCliente['aceptar_terminos'];
            $pedido->save();

            //pedido producto
            $numProductosPedido=$comisionPaypalFactura/count($auxProductos);
            foreach ($auxProductos as $key => $value) {
                $pedidoProducto=new PedidoProducto();
                $pedidoProducto->id_cliente=$idDesencriptado;
                $pedidoProducto->id_producto =$value['id'];
                $pedidoProducto->id_pedido=$pedido->id;
                $pedidoProducto->metodo_compra="Paypal";
                $pedidoProducto->precio_real=(double)(($value['precio'])-($numProductosPedido));
                $pedidoProducto->precio_venta=$value['precio'];
                $pedidoProducto->estado=1;
                $pedidoProducto->estado_pago_proveedor=0;
                $pedidoProducto->save();
            }


             //ruta para realizar el pago
             $rutaPago=new RedirectUrls();
             $rutaDeConfirmacionPago=getenv("APP_URL")."/Backend/public/index.php/pedidoProducto/resultadoCompraPaypal?id_transaccion={$ID_registro}";
             $rutaPago->setReturnUrl($rutaDeConfirmacionPago)//pago exitoso
                                   ->setCancelUrl(getenv("APP_URL")."/#/resultado-compra?estado=FALSE&idpago{$ID_registro}");
             //redireccionar a la pagina de paypal
             $pago=new Payment();
             $pago->setIntent("sale")
                 ->setPayer($ObjPayerCompra)
                 ->setRedirectUrls($rutaPago)
                 ->setTransactions(array($transaccion));
             $pago->create(PaypalBootstrap::configPaypal());
             $aprobado=$pago->getApprovalLink();
             $respuesta=array('url'=>$aprobado,
                                 'id_transanccion'=>$ID_registro,
                                 'aplicar_promocion'=>$aplicaPromocion,
                                 'productos'=>$auxProductos,
                                 'form_cliente'=>$formCliente,
                                 'pedido'=>$pedido,
                                 'total'=>$sumaTotalCancelar);
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$respuesta]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }

    public static  function crearPedidoMonedero($request,$idCliente){
        try {

            if(!($request->json())){
                return response()->json(["sms"=>"La data no tiene el formato requerido","Siglas"=>"ONE",'res'=>null]);
            }
            //verificar si el id del usuario existe
            $idDesencriptado=Crypt::decrypt($idCliente);
            $existesUsuario=Cliente::where("id",$idDesencriptado)->first();
            if(!$existesUsuario){
                return response()->json(["sms"=>"El usuario ".$idCliente." no existe en la base de datos","Siglas"=>"ONE",'res'=>null]);
            }
            $productos=($request->json()->all())['productos'];
            $auxProductos=[];
            //verificar id de los productos y tambien el precio
            $aplicaPromocion=FALSE;
            $sumaTotalCancelar=0;
            foreach ($productos as $key => $value) {
                //verificar si el id del producto existe
                $bdProducto=Producto::where('id',$value['id'])->first();
                if(!$bdProducto){
                    return response()->json(["sms"=>"El producto ".$value['url_directorio']." con el identificador ".$productos[$key]['id']." no ha sido encontrado","Siglas"=>"ONE",'res'=>null]);
                }
                //desencripto los productos
                $auxProductos[$key]['id']=$bdProducto->id;
                $auxProductos[$key]['url_directorio']=$bdProducto->url_directorio;

                //aplicar promocion del productos
                $cupon=Cupon::where("estado",1)->orderBy('id', 'ASC')->first();
                if($cupon && count($productos)>=($cupon->monto)){
                    $aplicaPromocion=TRUE;
                    $descuentoPromocion=sprintf('%0.2f',(($bdProducto->precio)-($bdProducto->precio)*($cupon->descuento)/100));
                    $sumaTotalCancelar=$descuentoPromocion+$sumaTotalCancelar;
                    $auxProductos[$key]['precio']=$descuentoPromocion;
                }else{
                    $sumaTotalCancelar=$bdProducto->precio+$sumaTotalCancelar;
                    $auxProductos[$key]['precio']=$bdProducto->precio;
                }

            }
            //comprobar si tiene dinero disponible para realizar la compra
            if(($existesUsuario->saldo)<$sumaTotalCancelar){
                return response()->json(["sms"=>"Al momento no cuentas con saldo suficiente para realizar tu compra","Siglas"=>"NTS",'res'=>$existesUsuario]);
            }
            //return $formCliente;
            $ID_registro=UUID::v4();;
            $formCliente=($request->json()->all())['formCliente'];
            $arrayFormCliente=array("productos"=>$auxProductos,
                                    'id_transanccion'=>$ID_registro,
                                    'aplicar_promocion'=>$aplicaPromocion,
                                    "formCliente"=>$formCliente);
            $pedido=new Pedido();
            $pedido->total_venta=$sumaTotalCancelar;
            $pedido->total_real=$sumaTotalCancelar;
            $pedido->id_cliente =$idDesencriptado;
            $pedido->estado=1;
            $pedido->metodo_compra="Monedero";
            $pedido->form_factura=json_encode($arrayFormCliente);
            $pedido->id_transaccion=$ID_registro;
            $pedido->pais_compra=$formCliente['pais_compra'];
            $pedido->ciudad=$formCliente['ciudad'];
            $pedido->correo=$formCliente['correo'];
            $pedido->ip_cliente=$formCliente['ip_cliente'];
            $pedido->telefono=$formCliente['telefono'];
            $pedido->direccion=$formCliente['direccion'];
            $pedido->documento_identidad=$formCliente['documento_identidad'];
            $pedido->nombre=$formCliente['nombre'];
            $pedido->apellido=$formCliente['apellido'];
            $pedido->aceptar_terminos=$formCliente['aceptar_terminos'];
            $pedido->save();

            //pedido producto
            foreach ($auxProductos as $key => $value) {
                $pedidoProducto=new PedidoProducto();
                $pedidoProducto->id_cliente=$idDesencriptado;
                $pedidoProducto->id_producto =$value['id'];
                $pedidoProducto->id_pedido=$pedido->id;
                $pedidoProducto->metodo_compra="Monedero";
                $pedidoProducto->precio_real=$value['precio'];
                $pedidoProducto->precio_venta=$value['precio'];
                $pedidoProducto->estado=1;
                $pedidoProducto->estado_pago_proveedor=0;
                $pedidoProducto->save();
            }
            //actulizar el saldo del cliente
            $actulizarSaldoCliente=Cliente::where('id',$idDesencriptado)
                                    ->update(array("saldo"=>$existesUsuario->saldo-$sumaTotalCancelar));

            $respuesta=array('url'=>getenv("APP_URL").'/#/resultado-compra?estado=TRUE',
                                 'id_transanccion'=>$ID_registro,
                                 'aplicar_promocion'=>$aplicaPromocion,
                                 'productos'=>$auxProductos,
                                 'form_cliente'=>$formCliente,
                                 "saldo_anterior"=>$existesUsuario->saldo,
                                 "saldo_actualizado"=>$actulizarSaldoCliente,
                                 'pedido'=>$pedido,
                                 'total'=>$sumaTotalCancelar);

            $facturaCorreo=TemplateCorreo::templateFacturaPaypalProductos($ID_registro);
            //enviar al cliente
            TemplateCorreo::enviarCorreo($facturaCorreo,$formCliente['correo'],"CONFIRMACIÓN DE COMPRA PROEDITSCLUB.COM");
            //enviar al administrador
            TemplateCorreo::enviarCorreo($facturaCorreo,getenv("CORREO_ADMIN"),"CONFIRMACIÓN DE COMPRA PROEDITSCLUB.COM");
            TemplateCorreo::enviarCorreo($facturaCorreo,getenv("CORREO"),"CONFIRMACIÓN DE COMPRA PROEDITSCLUB.COM");
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$respuesta]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }

    public static  function crearPedidoMembresia($request,$idCliente){
        try {
            if(!($request->json())){
                return response()->json(["sms"=>"La data no tiene el formato requerido","Siglas"=>"ONE",'res'=>null]);
            }
            //verificar si el id del usuario existe
            $idDesencriptado=Crypt::decrypt($idCliente);
            $existesUsuario=Cliente::where("id",$idDesencriptado)->first();
            if(!$existesUsuario){
                return response()->json(["sms"=>"El usuario ".$idCliente." no existe en la base de datos","Siglas"=>"ONE",'res'=>null]);
            }
            $productos=($request->json()->all())['productos'];
            $auxProductos=[];
            //verificar id de los productos y tambien el precio
            $aplicaPromocion=FALSE;


            foreach ($productos as $key => $value) {
                //verificar si el id del producto existe
                $bdProducto=Producto::where('id',$value['id'])->first();
                if(!$bdProducto){
                    return response()->json(["sms"=>"El producto ".$value['url_directorio']." con el identificador ".$productos[$key]['id']." no ha sido encontrado","Siglas"=>"ONE",'res'=>null]);
                }
                //desencripto los productos
                $auxProductos[$key]['id']=$bdProducto->id;
                $auxProductos[$key]['url_directorio']=$bdProducto->url_directorio;
                $auxProductos[$key]['precio']=$bdProducto->precio;
            }

            //verificar si tiene membresia activa para que pueda comprar
            $tieneMembresia=ClienteMembresia::where('id_cliente',$idDesencriptado)
                            ->where("estado",1)
                            ->whereDate("fecha_culminacion",">=",Carbon::now())
                            ->get();
            //comprobar si membresia disponible para realizar la compra
            if(count($tieneMembresia)==0){
                return response()->json(["sms"=>"No puede realizar tu pedido, no cuenta con membresia disponible","Siglas"=>"NTM",'res'=>$tieneMembresia]);
            }

            //validar si el numero de productos de quiere comprar le permite con la membresia
            $membresiaOcupada=null;
            $actulizarNumDescargas=0;
            foreach ($tieneMembresia as $key => $value) {

                if($value['descargas_sobrantes']>= count($productos)){
                    $membresiaOcupada=$value;
                    $actulizarNumDescargas=ClienteMembresia::where("id",$value['id'])
                    ->update(
                                array('descargas_sobrantes'=>(
                                ($value['descargas_sobrantes'])-count($auxProductos)))
                            );
                    break;
                }
            }
            //suma total de los productos
            $sumaTotalCancelar=count($auxProductos)*$membresiaOcupada['precio_unidad'];
            //form Cliente
            $ID_registro=UUID::v4();;
            $formCliente=($request->json()->all())['formCliente'];
            $arrayFormCliente=array("productos"=>$auxProductos,
                                    'id_transanccion'=>$ID_registro,
                                    'aplicar_promocion'=>$aplicaPromocion,
                                    "formCliente"=>$formCliente);
            $pedido=new Pedido();
            $pedido->total_venta=$sumaTotalCancelar;
            $pedido->total_real=$sumaTotalCancelar;
            $pedido->id_cliente =$idDesencriptado;
            $pedido->estado=1;
            $pedido->metodo_compra="Membresia";
            $pedido->form_factura=json_encode($arrayFormCliente);
            $pedido->id_transaccion=$ID_registro;
            $pedido->pais_compra=$formCliente['pais_compra'];
            $pedido->ciudad=$formCliente['ciudad'];
            $pedido->correo=$formCliente['correo'];
            $pedido->ip_cliente=$formCliente['ip_cliente'];
            $pedido->telefono=$formCliente['telefono'];
            $pedido->direccion=$formCliente['direccion'];
            $pedido->documento_identidad=$formCliente['documento_identidad'];
            $pedido->nombre=$formCliente['nombre'];
            $pedido->apellido=$formCliente['apellido'];
            $pedido->aceptar_terminos=$formCliente['aceptar_terminos'];
            $pedido->save();
            //pedido producto
            foreach ($auxProductos as $key => $value) {
                $pedidoProducto=new PedidoProducto();
                $pedidoProducto->id_cliente=$idDesencriptado;
                $pedidoProducto->id_producto =$value['id'];
                $pedidoProducto->id_pedido=$pedido->id;
                $pedidoProducto->metodo_compra="Membresia";
                $pedidoProducto->precio_real=$membresiaOcupada['precio_unidad'];
                $pedidoProducto->precio_venta=$membresiaOcupada['precio_unidad'];
                $pedidoProducto->estado=1;
                $pedidoProducto->estado_pago_proveedor=0;
                $pedidoProducto->save();
            }
            //actulizar el saldo del cliente
            $actulizarSaldoCliente=Cliente::where('id',$idDesencriptado)
                                    ->update(array("saldo"=>$existesUsuario->saldo-$sumaTotalCancelar));

            $respuesta=array('url'=>getenv("APP_URL").'/#/resultado-compra?estado=TRUE',
                                 'id_transanccion'=>$ID_registro,
                                 'productos'=>$auxProductos,
                                 'form_cliente'=>$formCliente,
                                 "actulizar_numDescargas"=>$actulizarNumDescargas,
                                 "saldo_actualizado"=>$actulizarSaldoCliente,
                                 'pedido'=>$pedido,
                                 'total'=>$sumaTotalCancelar);

            $facturaCorreo=TemplateCorreo::templateFacturaPaypalProductos($ID_registro);
            //enviar al cliente
            TemplateCorreo::enviarCorreo($facturaCorreo,$formCliente['correo'],"CONFIRMACIÓN DE COMPRA PROEDITSCLUB.COM");
            //enviar al administrador
            TemplateCorreo::enviarCorreo($facturaCorreo,getenv("CORREO_ADMIN"),"CONFIRMACIÓN DE COMPRA PROEDITSCLUB.COM");
            TemplateCorreo::enviarCorreo($facturaCorreo,getenv("CORREO"),"CONFIRMACIÓN DE COMPRA PROEDITSCLUB.COM");
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$respuesta]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
    public static function crearPedidoPaymentez($request,$idCliente){
        // First setup your credentials provided by paymentez
        try {
            if(!($request->json())){
                return response()->json(["sms"=>"La data no tiene el formato requerido","Siglas"=>"ONE",'res'=>null]);
            }
            //verificar si el id del usuario existe
            $idDesencriptado=Crypt::decrypt($idCliente);
            $metodoCompra="Tarjeta";
            $existesUsuario=Cliente::where("id",$idDesencriptado)->first();
            if(!$existesUsuario){
                return response()->json(["sms"=>"El usuario ".$idCliente." no existe en la base de datos","Siglas"=>"ONE",'res'=>null]);
            }
            $productos=($request->json()->all())['productos'];
            $auxProductos=[];
            //verificar id de los productos y tambien el precio
            $aplicaPromocion=FALSE;
            $sumaTotalCancelar=0;
            foreach ($productos as $key => $value) {
                //verificar si el id del producto existe
                $bdProducto=Producto::where('id',$value['id'])->first();
                if(!$bdProducto){
                    return response()->json(["sms"=>"El producto ".$value['url_directorio']." con el identificador ".$productos[$key]['id']." no ha sido encontrado","Siglas"=>"ONE",'res'=>null]);
                }
                //desencripto los productos
                $auxProductos[$key]['id']=$bdProducto->id;
                $auxProductos[$key]['url_directorio']=$bdProducto->url_directorio;

                //aplicar promocion del productos
                $cupon=Cupon::where("estado",1)->orderBy('id', 'ASC')->first();
                if($cupon && count($productos)>=($cupon->monto)){
                    $aplicaPromocion=TRUE;
                    $descuentoPromocion=sprintf('%0.2f',(($bdProducto->precio)-($bdProducto->precio)*($cupon->descuento)/100));
                    $sumaTotalCancelar=$descuentoPromocion+$sumaTotalCancelar;
                    $auxProductos[$key]['precio']=$descuentoPromocion;
                }else{
                    $sumaTotalCancelar=$bdProducto->precio+$sumaTotalCancelar;
                    $auxProductos[$key]['precio']=$bdProducto->precio;
                }

            }

            $ID_registro=UUID::v4();
            $formCliente=($request->json()->all())['formCliente'];
            $arrayFormCliente=array("productos"=>$auxProductos,
                                    'id_transanccion'=>$ID_registro,
                                    'aplicar_promocion'=>$aplicaPromocion,
                                    "formCliente"=>$formCliente);
            $pedido=new Pedido();
            $pedido->total_venta=$sumaTotalCancelar;

            //precio real paymentez
            //BANCOS
            //1. 4.50%
            $tarifaRetencionBanco=$sumaTotalCancelar*0.045;
            // Retencion sobre la renta 2%
            $retencionRenta=$sumaTotalCancelar*0.002;
            $valorLiquido=$sumaTotalCancelar-$retencionRenta-$tarifaRetencionBanco;

            // TARIFAS PAYMENTEZ
            //1. Tarifa paymentez 1.50%
            $tarifaPaymentes=0.015*$sumaTotalCancelar;
            $ivaTarifaPaymentez=$tarifaPaymentes*0.012;
            $totalFacturarPaymentez=$tarifaPaymentes+$ivaTarifaPaymentez;
            $pedido->total_real=$valorLiquido-$totalFacturarPaymentez;
            $pedido->id_cliente =$idDesencriptado;
            $pedido->estado=0;
            $pedido->metodo_compra=$metodoCompra;
            $pedido->form_factura=json_encode($arrayFormCliente);
            $pedido->id_transaccion=$ID_registro;
            $pedido->pais_compra=$formCliente['pais_compra'];
            $pedido->ciudad=$formCliente['ciudad'];
            $pedido->correo=$formCliente['correo'];
            $pedido->ip_cliente=$formCliente['ip_cliente'];
            $pedido->telefono=$formCliente['telefono'];
            $pedido->direccion=$formCliente['direccion'];
            $pedido->documento_identidad=$formCliente['documento_identidad'];
            $pedido->nombre=$formCliente['nombre'];
            $pedido->apellido=$formCliente['apellido'];
            $pedido->aceptar_terminos=$formCliente['aceptar_terminos'];
            $pedido->save();

            //pedido producto
            foreach ($auxProductos as $key => $value) {
                $pedidoProducto=new PedidoProducto();
                $pedidoProducto->id_cliente=$idDesencriptado;
                $pedidoProducto->id_producto =$value['id'];
                $pedidoProducto->id_pedido=$pedido->id;
                $pedidoProducto->metodo_compra=$metodoCompra;
                //precio real paymentez
                //BANCOS
                //1. 4.50%
                $tarifaRetencionBanco=$value['precio']*0.045;
                // Retencion sobre la renta 2%
                $retencionRenta=$value['precio']*0.002;
                $valorLiquido=$value['precio']-$retencionRenta-$tarifaRetencionBanco;

                // TARIFAS PAYMENTEZ
                //1. Tarifa paymentez 1.50%
                $tarifaPaymentes=0.015*$value['precio'];
                $ivaTarifaPaymentez=$tarifaPaymentes*0.012;
                $totalFacturarPaymentez=$tarifaPaymentes+$ivaTarifaPaymentez;

                $pedidoProducto->precio_real=$valorLiquido-$totalFacturarPaymentez;
                $pedidoProducto->precio_venta=$value['precio'];
                $pedidoProducto->estado=1;
                $pedidoProducto->estado_pago_proveedor=0;
                $pedidoProducto->save();
            }
            $respuesta=array('url'=>getenv("APP_URL").'/#/resultado-compra?estado=TRUE',
                                 'id_transanccion'=>$ID_registro,
                                 'aplicar_promocion'=>$aplicaPromocion,
                                 'productos'=>$auxProductos,
                                 'form_cliente'=>$formCliente,
                                 'pedido'=>$pedido,
                                 'total'=>$sumaTotalCancelar);
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$respuesta]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }

}
