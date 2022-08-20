<?php

namespace App\Http\Controllers\Pedido;

use Illuminate\Support\Str;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class ListarPedido {

    public static function listarPedidoPanelCliente($idCliente){
        try {
            $idClienteDesencriptado=Crypt::decrypt($idCliente);
            //1.Preguntamos si existe el usuario
            $existeUsuario=Cliente::where('id',$idClienteDesencriptado)->first();

            if(!$existeUsuario){
                return response()->json(["sms"=>"El cliente  ".$idCliente." no tiene permisos","Siglas"=>"UNE",'res'=>null]);
            }
            $pedido=Pedido::where("pedido.id_cliente",$idClienteDesencriptado)
                                                            ->orderBy('pedido.id', 'DESC')
                                                            ->get();

            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$pedido]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
    public static function listarPedidoPanelAdmin($idAdmin){
        try {
            $idClienteDesencriptado=Crypt::decrypt($idAdmin);
            //1.Preguntamos si existe el usuario
            $existeUsuario=Proveedor::where('id',$idClienteDesencriptado)
                        ->where("tipo_usuario","Admin")
                        ->first();

            if(!$existeUsuario){
                return response()->json(["sms"=>"El cliente  ".$idAdmin." no tiene permisos","Siglas"=>"UNE",'res'=>null]);
            }
            $pedido=Pedido::orderBy('pedido.id', 'DESC')
            ->join("cliente","cliente.id","pedido.id_cliente")
            ->select("pedido.id",
            "pedido.total_real",
            "cliente.correo AS cliente_correo",
            "pedido.total_venta",
            "pedido.nombre",
            "pedido.documento_identidad",
            "pedido.apellido",
            "pedido.correo",
            "pedido.metodo_compra",
            "pedido.id_transaccion",
            "pedido.pais_compra",
            "pedido.aceptar_terminos",
            "pedido.telefono",
            "pedido.direccion",
            "pedido.ip_cliente",
            "pedido.id_transaccion",
            "pedido.estado",
            "pedido.ciudad",
            "pedido.created_at",
            "pedido.updated_at"
            )
            ->get();
            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$pedido]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }

}
