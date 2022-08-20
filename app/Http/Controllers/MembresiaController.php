<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MembresiaController extends Controller{


    public function editarMembresia(Request $request,$idAdmin){

        try {
            $this->validate($request, [
                'id' => 'required|numeric',
                'precio' => 'required|numeric',
                'num_dias' => 'required|numeric',
                'estado' => 'required|numeric',
                'nombre' => 'required|string'
            ]);
            return Membresia\EditarMembresia::editarMembresia( $request,$idAdmin);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }

    }
    public function listarMembresiaPanel(Request $request,$idAdmin){
        return Membresia\ListarMembresias::listarMembresiaPanel($request,$idAdmin);
    }
    public function listarMembresiaPublico(){
        return Membresia\ListarMembresias::listarMembresiaPublico();
    }
}
