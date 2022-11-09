<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class GeneroController extends Controller{


    public function listarGenero(){
        return Genero\ListarGenero::listarGenero();
    }
    //obtener un genero en especifico
    public function obtenerGenero(Request $request){
        return Genero\ListarGenero::obtenerGenero($request);
    }
    public function listasGeneroPanel(Request $request,$idAdmin){
        return Genero\ListarGenero::listasGeneroPanel($request,$idAdmin);
    }
    public function editarGeneroPanel(Request $request,$idAdmin){
        try {
            $this->validate($request, [
                'id' => 'required|numeric',
                'genero' => 'required|string'
            ]);
            return Genero\EditarGenero::editarGeneroPanel($request,$idAdmin);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
    public function eliminarGeneroPanel(Request $request,$idAdmin){
        try {
            $this->validate($request, [
                'id' => 'required|numeric',
                'estado' => 'required|numeric'
            ]);
            return Genero\EliminarGenero::eliminarGeneroPanel($request,$idAdmin);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
    public function registrarGeneroPanel(Request $request,$idAdmin){
        try {
            $this->validate($request, [
                'estado' => 'required|numeric',
                'genero' => 'required|string',
            ]);
            return Genero\CrearGenero::registrarGeneroPanel($request,$idAdmin);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
}
