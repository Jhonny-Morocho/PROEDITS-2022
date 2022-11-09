<?php

namespace App\Http\Controllers\Cupon;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Cupon;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class ListarCupon {


    public static  function listasCuponPanel(){
        try {

            $cupon=Cupon::where("estado",1)->orderBy('id', 'ASC')->first();
            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$cupon]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
}
