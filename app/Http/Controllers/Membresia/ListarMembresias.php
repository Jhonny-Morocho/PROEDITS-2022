<?php

namespace App\Http\Controllers\Membresia;

use App\Models\Genero;
use App\Models\Membresia;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class ListarMembresias {

    public static function listarMembresiaPanel($request,$idAdmin){
        try {
            $datos=$request->json()->all();
            $idDesencriptado=Crypt::decrypt($idAdmin);
            $esAdmin=Proveedor::where("id",$idDesencriptado)
                                ->where("estado",1)
                                ->where("tipo_usuario",'Admin')
                                ->first();
            if(!$esAdmin){
                return response()->json(["sms"=>'El usuario '.$esAdmin.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
            }
            $membresias=Membresia::get();
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$membresias]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
    public static function listarMembresiaPublico(){
        try {
            $Membresias=Membresia::orderBy('id', 'DESC')->get();
            $auxMembresias=[];
            foreach ($Membresias as $key => $value) {
                $auxMembresias[$key]['id']=Crypt::encrypt($value['id']);
                $auxMembresias[$key]['nombre']=$value['nombre'];
                $auxMembresias[$key]['precio']=$value['precio'];
                $auxMembresias[$key]['descargas']=$value['descargas'];
                $auxMembresias[$key]['num_dias']=$value['num_dias'];
                $auxMembresias[$key]['estado']=$value['estado'];
                $auxMembresias[$key]['created_at']=$value['created_at'];
                $auxMembresias[$key]['updated_at']=$value['updated_at'];
            }
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$auxMembresias]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
}
