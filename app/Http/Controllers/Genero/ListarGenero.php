<?php

namespace App\Http\Controllers\Genero;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Genero;
use Illuminate\Support\Facades\Crypt;
class ListarGenero {

    public static function listarGenero(){
        try {
            $genero=Genero::where('estado',1)->orderBy('genero', 'ASC')->get();
            $auxGenero=[];
            foreach ($genero as $key => $value) {
                $auxGenero[$key]['id']=$value['id'];
                $auxGenero[$key]['genero']=$value['genero'];
                $auxGenero[$key]['seo']=Str::lower(str_replace(" ","-",$value['genero']));
            }
            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$auxGenero]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ONE",'res'=>null]);
        }
    }
    public static function obtenerGenero( $request){
        try {
            $generoProductos=Proveedor::join("productos","productos.id_proveedor","proveedor.id")->
                            join("genero","genero.id","productos.id_genero")->
                            where("productos.id_genero",$request['idGenero'])->
                            where("productos.estado",1)->
                            where("proveedor.estado",1)->
                            select("precio","url_directorio",
                                    "genero",
                                    "productos.id",
                                    "productos.caratula",
                                    "productos.id",
                                    "proveedor.img",
                                    "productos.created_at")->
                            orderBy('productos.id', 'DESC')->
                            paginate(40);

            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$generoProductos]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ONE",'res'=>null]);
        }

    }
    public static function listasGeneroPanel($request,$idAdmin){
        try {
            $idDesencriptado=Crypt::decrypt($idAdmin);
            $esAdmin=Proveedor::where("id",$idDesencriptado)
                                ->where("estado",1)
                                ->where("tipo_usuario",'Admin')
                                ->first();
            if(!$esAdmin){
                return response()->json(["sms"=>'El usuario '.$esAdmin.' no tiene permisos para visualizar los proveedores',"Siglas"=>"NTP",'res'=>null]);
            }
            $genero=Genero::where("estado",1)->orderBy('genero', 'ASC')->get();
            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$genero]);



        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
}
