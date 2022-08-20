<?php

namespace App\Http\Controllers\ClienteMembresia;

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
use Carbon\Carbon;
use App\Models\clienteMembresiaProducto;
use App\Models\Producto;
use App\Models\Cupon;
use App\Models\Membresia;
use Illuminate\Support\Facades\Crypt;
use App\Traits\PaypalBootstrap;
use App\Traits\UUID;
use App\Traits\TemplateCorreo;
use App\Models\Proveedor;
class CrearClienteMembresia {
    use PaypalBootstrap;
    use UUID;
    use TemplateCorreo;
    public static function crearclienteMembresiaPaypal($request,$idCliente){
        //creamos el clienteMembresia
        try {
            if(!($request->json())){
                return response()->json(["sms"=>"La data no tiene el formato requerido","Siglas"=>"ONE",'res'=>null]);
            }
            //verificar si el id del usuario existe
            $idDesencriptadoCliente=Crypt::decrypt($idCliente);
            $existesUsuario=Cliente::where("id",$idDesencriptadoCliente)->first();
            if(!$existesUsuario){
                return response()->json(["sms"=>"El usuario ".$idCliente." no existe en la base de datos","Siglas"=>"ONE",'res'=>null]);
            }

            $idMembresia=(($request['membresia']))['id'];
            $idMembresiaDesencriptado=Crypt::decrypt($idMembresia);
            $encontrarMembresia=Membresia::where('id',$idMembresiaDesencriptado)
                                        ->where("estado",1)
                                        ->first();

            if(!$encontrarMembresia){
                return response()->json(["sms"=>"La membresia no esta disponible","Siglas"=>"ONE",'res'=>null]);
            }

            //enviamos los datos a la api de paypal
            $ObjPayerCompra=new Payer();
            $ObjPayerCompra->setPaymentMethod('paypal');
            $descripcionProducto="Membresia";
            $arregloProductos=array();
            $sumaTotalCancelar=0;

            ${"articulo$idMembresiaDesencriptado"}=new Item();
            $arregloProductos[]=${"articulo$idMembresiaDesencriptado"};
            ${"articulo$idMembresiaDesencriptado"}->setName($descripcionProducto.' : '.$encontrarMembresia->nombre)//el i lleva el nombre de la cancion
                ->setCurrency('USD')//la moneda a cobrar
                ->setQuantity((int)1)//siempre la cancion va hacer (1)
                ->setSku($encontrarMembresia->id)
                ->setPrice((double)$encontrarMembresia->precio);//precio de la cancion
            $sumaTotalCancelar=(double)$encontrarMembresia->precio;

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
            $arrayFormCliente=array("productos"=>$encontrarMembresia->nombre,
                                    'id_transanccion'=>$ID_registro,
                                    "formCliente"=>$formCliente);

            $comisionPaypal=((5.4/100)*$encontrarMembresia->precio)+0.30;
            $totalRealPedido=($encontrarMembresia->precio-$comisionPaypal);

            $clienteMembresia=new ClienteMembresia();
            $clienteMembresia->id_cliente =$idDesencriptadoCliente;
            $clienteMembresia->id_membresia=$idMembresiaDesencriptado;
            $clienteMembresia->id_transaccion=$ID_registro;
            $clienteMembresia->fecha_inicio=Carbon::now();
            $clienteMembresia->fecha_culminacion=Carbon::now()->addDays((int)$encontrarMembresia->num_dias);
            $clienteMembresia->precio_venta=$encontrarMembresia->precio;
            $clienteMembresia->precio_real=$totalRealPedido;
            $clienteMembresia->estado=0;
            $clienteMembresia->metodo_compra="Paypal";
            $clienteMembresia->form_factura=json_encode($arrayFormCliente);
            $clienteMembresia->pais_compra=$formCliente['pais_compra'];
            $clienteMembresia->ciudad=$formCliente['ciudad'];
            $clienteMembresia->correo=$formCliente['correo'];
            $clienteMembresia->ip_cliente=$formCliente['ip_cliente'];
            $clienteMembresia->telefono=$formCliente['telefono'];
            $clienteMembresia->direccion=$formCliente['direccion'];
            $clienteMembresia->documento_identidad=$formCliente['documento_identidad'];
            $clienteMembresia->nombre=$formCliente['nombre'];
            $clienteMembresia->apellido=$formCliente['apellido'];
            $clienteMembresia->aceptar_terminos=$formCliente['aceptar_terminos'];
            $clienteMembresia->descargas_total=$encontrarMembresia->descargas;
            $clienteMembresia->descargas_sobrantes=$encontrarMembresia->descargas;
            $clienteMembresia->precio_unidad=$totalRealPedido/($encontrarMembresia->descargas);
            $clienteMembresia->save();



             //ruta para realizar el pago
             $rutaPago=new RedirectUrls();
             $rutaDeConfirmacionPago=getenv("APP_URL")."/Backend/public/index.php/clienteMembresia/resultadoCompraPaypal?id_transaccion={$ID_registro}";
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
                                 'productos'=>$encontrarMembresia,
                                 'form_cliente'=>$formCliente,
                                 'clienteMembresia'=>$clienteMembresia,
                                 'total'=>$sumaTotalCancelar);
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$respuesta]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$request->json()->all()]);
        }
    }
    public static function crearclienteMembresiaDeposito($request,$idAdmin){
        //creamos el clienteMembresia
        try {
            if(!($request->json())){
                return response()->json(["sms"=>"La data no tiene el formato requerido","Siglas"=>"ONE",'res'=>null]);
            }
            //verificar si el usuario es proveedor
            $idDesencriptado=Crypt::decrypt($idAdmin);
            $esProveedor=Proveedor::where("id",$idDesencriptado)
                                ->where("estado",1)
                                ->first();
            if(!$esProveedor){
                return response()->json(["sms"=>'El usuario'.$idAdmin.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
            }
            //verificar si el id del usuario cliente existe
            $idDesencriptadoCliente=Crypt::decrypt($request['id_cliente']);
            $existesUsuario=Cliente::where("id",$idDesencriptadoCliente)->first();
            if(!$existesUsuario){
                return response()->json(["sms"=>"El usuario ".$request['id_cliente']." no existe en la base de datos","Siglas"=>"ONE",'res'=>null]);
            }

            $idMembresia=$request['id_membresia'];
            $encontrarMembresia=Membresia::where('id',$idMembresia)
                                        ->where("estado",1)
                                        ->first();

            if(!$encontrarMembresia){
                return response()->json(["sms"=>"La membresia no esta disponible","Siglas"=>"ONE",'res'=>null]);
            }
            //crear factura
            $ID_registro=UUID::v4();
            $formCliente=$request->json()->all();
            $arrayFormCliente=array("productos"=>$encontrarMembresia->nombre,
                                    'id_transanccion'=>$ID_registro,
                                    "formCliente"=>"");
            $clienteMembresia=new ClienteMembresia();
            $clienteMembresia->id_cliente =$idDesencriptadoCliente;
            $clienteMembresia->id_membresia=$idMembresia;
            $clienteMembresia->id_transaccion=$ID_registro;
            $clienteMembresia->fecha_inicio=Carbon::now();
            $clienteMembresia->fecha_culminacion=Carbon::now()->addDays((int)$encontrarMembresia->num_dias);
            $clienteMembresia->precio_venta=$encontrarMembresia->precio;
            $clienteMembresia->precio_real=$encontrarMembresia->precio;
            $clienteMembresia->estado=1;
            $clienteMembresia->metodo_compra="Deposito";
            $clienteMembresia->form_factura=json_encode($arrayFormCliente);
            $defaul="Default";
            $clienteMembresia->pais_compra= $defaul;
            $clienteMembresia->ciudad= $defaul;
            $clienteMembresia->correo=$existesUsuario->correo;
            $clienteMembresia->ip_cliente= $defaul;
            $clienteMembresia->telefono= $defaul;
            $clienteMembresia->direccion= $defaul;
            $clienteMembresia->documento_identidad= $defaul;
            $clienteMembresia->nombre=$existesUsuario->nombre;
            $clienteMembresia->apellido=$existesUsuario->apellido;
            $clienteMembresia->aceptar_terminos=1;
            $clienteMembresia->descargas_total=$encontrarMembresia->descargas;
            $clienteMembresia->descargas_sobrantes=$encontrarMembresia->descargas;
            $clienteMembresia->precio_unidad=$encontrarMembresia->precio/($encontrarMembresia->descargas);
            $clienteMembresia->save();
             $respuesta=array(
                                 'id_transanccion'=>$ID_registro,
                                 'productos'=>$encontrarMembresia,
                                 'form_cliente'=>$formCliente,
                                 'clienteMembresia'=>$clienteMembresia,
                                 'total'=>$encontrarMembresia->precio);
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$respuesta]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$request->json()->all()]);
        }
    }
    public static function crearclienteMembresiaPaymentez($request,$idCliente){
        //creamos el clienteMembresia
        try {
            if(!($request->json())){
                return response()->json(["sms"=>"La data no tiene el formato requerido","Siglas"=>"ONE",'res'=>null]);
            }
            $metodoCompra='Tarjeta';
            //verificar si el id del usuario existe
            $idDesencriptadoCliente=Crypt::decrypt($idCliente);
            $existesUsuario=Cliente::where("id",$idDesencriptadoCliente)->first();
            if(!$existesUsuario){
                return response()->json(["sms"=>"El usuario ".$idCliente." no existe en la base de datos","Siglas"=>"ONE",'res'=>null]);
            }

            $idMembresia=(($request['membresia']))['id'];
            $idMembresiaDesencriptado=Crypt::decrypt($idMembresia);
            $encontrarMembresia=Membresia::where('id',$idMembresiaDesencriptado)
                                        ->where("estado",1)
                                        ->first();

            if(!$encontrarMembresia){
                return response()->json(["sms"=>"La membresia no esta disponible","Siglas"=>"ONE",'res'=>null]);
            }
            $sumaTotalCancelar=0;
            $sumaTotalCancelar=(double)$encontrarMembresia->precio;
            //crear factura
            $ID_registro=UUID::v4();
            $formCliente=($request->json()->all())['formCliente'];
            $arrayFormCliente=array("productos"=>$encontrarMembresia->nombre,
                                    'id_transanccion'=>$ID_registro,
                                    "formCliente"=>$formCliente);
            //precio real paymentez
            //BANCOS
            //1. 4.50%
            $tarifaRetencionBanco=$sumaTotalCancelar*0.045;
            // Retencion sobre la renta 2%
            $retencionRenta=$sumaTotalCancelar*0.002;
            $valorLiquido=$sumaTotalCancelar-$retencionRenta-$tarifaRetencionBanco;

            $clienteMembresia=new ClienteMembresia();
            $clienteMembresia->id_cliente =$idDesencriptadoCliente;
            $clienteMembresia->id_membresia=$idMembresiaDesencriptado;
            $clienteMembresia->id_transaccion=$ID_registro;
            $clienteMembresia->fecha_inicio=Carbon::now();
            $clienteMembresia->fecha_culminacion=Carbon::now()->addDays((int)$encontrarMembresia->num_dias);
            $clienteMembresia->precio_venta=$encontrarMembresia->precio;
            $clienteMembresia->precio_real=$valorLiquido;
            $clienteMembresia->estado=0;
            $clienteMembresia->metodo_compra=$metodoCompra;
            $clienteMembresia->form_factura=json_encode($arrayFormCliente);
            $clienteMembresia->pais_compra=$formCliente['pais_compra'];
            $clienteMembresia->ciudad=$formCliente['ciudad'];
            $clienteMembresia->correo=$formCliente['correo'];
            $clienteMembresia->ip_cliente=$formCliente['ip_cliente'];
            $clienteMembresia->telefono=$formCliente['telefono'];
            $clienteMembresia->direccion=$formCliente['direccion'];
            $clienteMembresia->documento_identidad=$formCliente['documento_identidad'];
            $clienteMembresia->nombre=$formCliente['nombre'];
            $clienteMembresia->apellido=$formCliente['apellido'];
            $clienteMembresia->aceptar_terminos=$formCliente['aceptar_terminos'];
            $clienteMembresia->descargas_total=$encontrarMembresia->descargas;
            $clienteMembresia->descargas_sobrantes=$encontrarMembresia->descargas;
            $clienteMembresia->precio_unidad=$valorLiquido/($encontrarMembresia->descargas);
            $clienteMembresia->save();

            $respuesta=array(
            'id_transanccion'=>$ID_registro,
            'productos'=>$encontrarMembresia,
            'form_cliente'=>$formCliente,
            'clienteMembresia'=>$clienteMembresia,
            'total'=>$sumaTotalCancelar);
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$respuesta]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$request->json()->all()]);
        }
    }


}
