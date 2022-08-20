<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CuponController extends Controller{
    public function listarCuponPanel(){
        try {
            return Cupon\ListarCupon::listasCuponPanel();
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
    public function editarCuponPanel(Request $request,$idAdmin){
        try {
            $this->validate($request, [
                'id' => 'required|numeric',
                'descuento' => 'required|numeric',
                'monto' => 'required|numeric',
                'sms_promocion' => 'required|string',
                'inicio' => 'required|string',
                'expira' => 'required|string',
                'estado' => 'required|numeric'
            ]);
            return Cupon\EditarCupon::editarCuponPanel($request,$idAdmin);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
}
