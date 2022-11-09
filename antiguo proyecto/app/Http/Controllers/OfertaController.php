<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Oferta;


class OfertaController extends Controller
{

    public function aplicarOferta(){
        try {
            //code...
            die(json_encode(Oferta::get()));
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ONE",'res'=>null]);
        }

    }
}
