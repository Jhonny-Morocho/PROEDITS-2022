<?php

namespace App\Http\Controllers;

//permite traer la data del apirest
use Illuminate\Http\Request;


class ProveedorController extends Controller
{
    //Registrar Usuario
    //correo de le emplesa
    public  function subirCaratula(Request $request,$idAdmin){
        return Proveedor\CrearProveedor::subirCaratula($request,$idAdmin);
    }
    public  function editarCaratula(Request $request,$idAdmin){
        return Proveedor\EditarProveedor::editarCaratula($request,$idAdmin);
    }
    public function eliminarArchivo($nombreArchivo){
        return Proveedor\EliminarProveedor::eliminarArchivo($nombreArchivo);
    }

    public function registrarProveedor(Request $request,$idAdmin){
        try {
            $this->validate($request, [
               'nombre' => 'required|string',
               'apellido' => 'required|string',
               'apodo' => 'required|string',
               'correo' => 'required|string',
               'password' => 'required|string',
               'img' => 'required|string',
               'estado' => 'required|numeric',
           ]);
           return Proveedor\CrearProveedor::registrarProveedor($request,$idAdmin);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }

    public function editarProveedor(Request $request,$idAdmin,$idProveedor){

        try {
           $this->validate($request, [
               'nombre' => 'required|string',
               'apellido' => 'required|string',
               'apodo' => 'required|string',
               'password' => 'string',
               'img' => 'string'
           ]);
           return Proveedor\EditarProveedor::editarProveedor($request,$idAdmin,$idProveedor);
       } catch (\Throwable $th) {
           return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
       }
    }
    public function eliminarProveedor(Request $request,$idAdmin,$idProveedor){
        try {
            $this->validate($request, ['estado' => 'required|numeric']);
            return Proveedor\EliminarProveedor::eliminarProveedor($request,$idAdmin,$idProveedor);
        } catch (\Throwable $th) {
                return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ONE",'res'=>null]);
        }

    }

    public function listasProveedores(Request $request,$idAdmin){
        return Proveedor\ListarProveedor::listasProveedores($request,$idAdmin);
    }
    public function listarProvedoresPublico(){
        return Proveedor\ListarProveedor::listarProvedoresPublico();
    }
    //obtener un genero en especifico
    public function obtenerRemixerPublico(Request $request){
        try {
            $this->validate($request, [
                'idProveedor' => 'required|numeric',
            ]);
            return Proveedor\ListarProveedor::obtenerRemixerPublico($request);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ONE",'res'=>null]);
        }

    }

    public function login(Request $request){
        try {
            $this->validate($request, [
                'correo' => 'required|email',
                'password' => 'required|string'
            ]);
            return Proveedor\LoginProveedor::login($request);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR"]);
        }
    }

}
