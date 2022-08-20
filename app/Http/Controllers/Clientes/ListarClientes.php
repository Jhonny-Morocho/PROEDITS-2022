<?php

namespace App\Http\Controllers\Clientes;

use App\Models\Cliente;
use App\Models\Genero;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class ListarClientes {

    public static function listasClientesPanel($idAdmin){
           try {
            $idDesencriptado=Crypt::decrypt($idAdmin);
            $esAdmin=Proveedor::where("id",$idDesencriptado)
                                ->where("estado",1)
                                ->where("tipo_usuario",'Admin')
                                ->first();
            if(!$esAdmin){
                return response()->json(["sms"=>'El usuario '.$esAdmin.' no tiene permisos para visualizar los proveedores',"Siglas"=>"NTP",'res'=>null]);
            }
            $clientes=Cliente::orderBy('id', 'DESC')->get();
            $auxClientes=[];
            foreach ($clientes as $key => $value) {
                $auxClientes[$key]['id']=Crypt::encrypt($value['id']);
                $auxClientes[$key]['nombre']=$value['nombre'];
                $auxClientes[$key]['apellido']=$value['apellido'];
                $auxClientes[$key]['correo']=$value['correo'];
                $auxClientes[$key]['tipo_usuario']=$value['tipo_usuario'];
                $auxClientes[$key]['saldo']=$value['saldo'];
                $auxClientes[$key]['estado']=$value['estado'];
                $auxClientes[$key]['proveedor']=$value['proveedor'];
                $auxClientes[$key]['created_at']=$value['created_at'];
                $auxClientes[$key]['updated_at']=$value['updated_at'];
            }
            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$auxClientes]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ONE",'res'=>null]);
        }
    }

    public static function saldoActualCliente($idCliente){
        try {
         $idDesencriptado=Crypt::decrypt($idCliente);
         $saldoCliente=Cliente::where("id",$idDesencriptado)
                            ->select("saldo")
                             ->first();
         return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$saldoCliente]);

     } catch (\Throwable $th) {
         return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ONE",'res'=>null]);
     }
 }


}
