<?php

namespace App\Http\Controllers\ClienteMembresia;

use Illuminate\Support\Str;
use App\Models\Cliente;
use App\Models\ClienteMembresia;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class ListarClienteMembresia {

    public static function listarClienteMembresiaPanelCliente($request,$idCliente){
        try {
            $idClienteDesencriptado=Crypt::decrypt($idCliente);
            //1.Preguntamos si existe el usuario
            $existeUsuario=Cliente::where('id',$idClienteDesencriptado)->first();

            if(!$existeUsuario){
                return response()->json(["sms"=>"El cliente  ".$idCliente." no tiene permisos","Siglas"=>"UNE"]);
            }
            $pedidoProducto= ClienteMembresia::join("membresia","membresia.id","cliente_membresia.id_membresia")
                                                ->join("cliente","cliente.id","cliente_membresia.id_cliente")
                                                ->where("cliente_membresia.id_cliente",$idClienteDesencriptado)
                                                ->select("cliente_membresia.id",
                                                "cliente_membresia.fecha_inicio",
                                                "cliente_membresia.fecha_culminacion",
                                                "cliente_membresia.descargas_sobrantes",
                                                "cliente_membresia.precio_venta",
                                                "cliente_membresia.metodo_compra",
                                                "cliente_membresia.estado",
                                                "cliente_membresia.descargas_total",
                                                "cliente_membresia.precio_unidad",
                                                "membresia.nombre")
                                                ->orderBy('cliente_membresia.id', 'DESC')
                                                ->get();
            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$pedidoProducto]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }

    public static function listarClienteMembresiaPanelAdmin($idAdmin){
        try {

            $idAdminDesencriptado=Crypt::decrypt($idAdmin);
            //1.Preguntamos si existe el usuario
            $existeUsuario=Proveedor::where('id',$idAdminDesencriptado)->first();
            if(!$existeUsuario){
                return response()->json(["sms"=>"El proveedor con el   ".$idAdmin." no tiene permisos","Siglas"=>"UNE"]);
            }
            $pedidoProducto= ClienteMembresia::join("membresia","membresia.id","cliente_membresia.id_membresia")
                            ->join("cliente","cliente.id","cliente_membresia.id_cliente")
                            ->select("cliente.correo",
                            "cliente.correo",
                            "cliente_membresia.fecha_inicio",
                            "cliente_membresia.id",
                            "membresia.nombre",
                            "cliente_membresia.fecha_culminacion",
                            "cliente_membresia.precio_venta",
                            "cliente_membresia.precio_real",
                            "cliente_membresia.precio_unidad",
                            "cliente_membresia.descargas_total",
                            "cliente_membresia.descargas_sobrantes",
                            "cliente_membresia.estado",
                            "cliente_membresia.direccion",
                            "cliente_membresia.telefono",
                            "cliente_membresia.aceptar_terminos",
                            "cliente_membresia.documento_identidad",
                            "cliente_membresia.ciudad",
                            "cliente_membresia.pais_compra",
                            "cliente_membresia.ip_cliente",
                            "cliente_membresia.metodo_compra",
                            "cliente_membresia.created_at",
                            "cliente_membresia.updated_at",
                            "cliente_membresia.id_transaccion")

                            ->orderBy('cliente_membresia.id', 'DESC')
                            ->get();
            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$pedidoProducto]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }

}
