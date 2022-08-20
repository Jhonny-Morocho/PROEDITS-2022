<?php

namespace App\Http\Controllers\Carrusel;

use App\Models\Carrusel;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;

class ListarCarrusel {

    public static function listarImgCarrusel(){
        try {

            $carrusel=Carrusel::where('estado',1)->orderBy('id', 'ASC')->get();
            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$carrusel]);

        } catch (\Throwable $th) {
            return response()->json(["res"=>null,"sms"=>$th->getMessage(),"Siglas"=>"ERROR"]);
        }

    }

}
