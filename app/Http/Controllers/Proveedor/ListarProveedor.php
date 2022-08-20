<?php

namespace App\Http\Controllers\Proveedor;

use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
class ListarProveedor {

    public static function listasProveedores($request,$idAdmin){
        try {
            $idDesencriptado=Crypt::decrypt($idAdmin);
            $esAdmin=Proveedor::where("id",$idDesencriptado)
                                ->where("estado",1)
                                ->where("tipo_usuario",'Admin')
                                ->first();
            if(!$esAdmin){
                return response()->json(["sms"=>'El usuario '.$esAdmin.' no tiene permisos para visualizar los proveedores',"Siglas"=>"NTP",'res'=>null]);
            }
            if($request['buscar']==""){
                $productos=Proveedor::where("estado",1)->orderBy('id', 'DESC')->paginate(10);
                return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$productos]);
            }

            $productos=Proveedor::where("estado",1)
                ->where('apodo','LIKE','%'.$request['buscar'].'%')
                ->orderBy('id', 'DESC')->paginate(40);
            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$productos]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
    public static function listarProvedoresPublico(){
        try {
            $proveedor=Proveedor::where('estado',1)->orderBy('apodo', 'ASC')->get();
            $auxProveedor=[];
            foreach ($proveedor as $key => $value) {
                $auxProveedor[$key]['id']=$value['id'];
                $auxProveedor[$key]['apodo']=$value['apodo'];
                $auxProveedor[$key]['seo']=Str::lower(str_replace(" ","-",$value['apodo']));
            }
            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$auxProveedor]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ONE",'res'=>null]);
        }
    }
    public static function obtenerRemixerPublico($request){
        try {
            $generoProductos=Proveedor::join("productos","productos.id_proveedor","proveedor.id")->
                            join("genero","genero.id","productos.id_genero")->
                            where("productos.id_proveedor",$request['idProveedor'])->
                            where("productos.estado",1)->
                            where("proveedor.estado",1)->
                            select("precio","url_directorio",
                                    "genero",
                                    "productos.id",
                                    "proveedor.apodo",
                                    "productos.caratula",
                                    "proveedor.img",
                                    "productos.created_at")->
                            orderBy('productos.id', 'DESC')->
                            paginate(40);

            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$generoProductos]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ONE",'res'=>null]);
        }
    }
}
