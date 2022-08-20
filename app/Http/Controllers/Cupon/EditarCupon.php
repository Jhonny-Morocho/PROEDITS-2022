<?php

namespace App\Http\Controllers\Cupon;

use Illuminate\Support\Str;
use App\Models\Cupon;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class EditarCupon {


    public static  function editarCuponPanel($request,$idAdmin){
        try {
            $datos=$request->json()->all();
            $idDesencriptado=Crypt::decrypt($idAdmin);
            $esAdmin=Proveedor::where("id",$idDesencriptado)
                                ->where("estado",1)
                                ->where("tipo_usuario",'Admin')
                                ->first();
            if(!$esAdmin){
                return response()->json(["sms"=>'El usuario '.$esAdmin.' no tiene permisos para visualizar los proveedores',"Siglas"=>"NTP",'res'=>null]);
            }
            $editarCupon=Cupon::where("id",$datos['id'])
            ->update(array
                        ('descuento'=>$datos['descuento'],
                          'monto'=>$datos['monto'],
                          'sms_promocion'=>$datos['sms_promocion'],
                          'inicio'=>$datos['inicio'],
                          'expira'=>$datos['expira'],
                          'estado'=>$datos['estado'],
                        )
            );
            if($editarCupon){
                return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$editarCupon]);
            }
            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"ONE",'res'=>$editarCupon]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
}
