<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarruselController extends Controller{


    public  function subirImgCarrusel(Request $request,$idProveedor){
        return Carrusel\CrearCarrusel::subirImgCarrusel($request,$idProveedor);
    }
    public  function editarImgCarrusel(Request $request,$idAdmin){
        return Carrusel\EditarCarrusel::editarImgCarrusel($request,$idAdmin);
    }
    public  function listarCarrusel(){
        return Carrusel\ListarCarrusel::listarImgCarrusel();
    }
    public function eliminarImgCarrusel(Request $request,$idAdmin){
        try {
            $this->validate($request, [
                'id' => 'required|numeric'
            ]);
            return Carrusel\EliminarCarrusel::eliminarImgCarrusel($request,$idAdmin);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
}
