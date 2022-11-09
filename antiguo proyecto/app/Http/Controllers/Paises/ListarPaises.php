<?php

namespace App\Http\Controllers\Paises;

use App\Models\Paises;

class ListarPaises {

    public static function listarPaises(){
        try {
            $paises=Paises::orderBy('nombre', 'ASC')->get();
            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$paises]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ONE",'res'=>null]);
        }
    }
}
