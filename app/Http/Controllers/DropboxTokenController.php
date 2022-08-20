<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DropboxTokenController extends Controller{

    public static function registrarDropboxToken($dropboxKey,$dropboxSecret,$refreshToken){
        try {
            return DropboxToken\CrearToken::registrarDropboxToken($dropboxKey,$dropboxSecret,$refreshToken);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }

}
