<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;



class ProductoController extends Controller{

    public  function subirDemo(Request $request,$idProveedor){
        try {
            $this->validate($request, [
                'fileDemo' => 'required|mimes:mp3|max:1500',
            ]);
            return Productos\CrearProducto::subirDemo($request,$idProveedor);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }

    public  function subirRemix(Request $request,$idProveedor){
        return Productos\CrearProducto::subirRemix($request,$idProveedor);
    }
    //el proveedor puede descargar los productos para vertificar si esta bien los archivos
    public function descargarProductoProveedor(Request $request,$idProveedor){
        return Productos\DescargarProducto::descargarProductoProveedor($request,$idProveedor);
    }
    public function editarProductoProveedor(Request $request,$idProveedor){
        try {
            $this->validate($request, [
                'id' => 'required|numeric',
                'id_genero' => 'required|numeric',
                'tipo_archivo' => 'required|numeric',
                'precio' => 'required|numeric',
                'url_descarga' => 'string',
                'url_directorio' => 'string'
            ]);
            return Productos\EditarProducto::editarProductoProveedor($request,$idProveedor);
       } catch (\Throwable $th) {
           return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
       }
    }

    public function eliminarArchivoUrlDescarga(Request $request,$idProveedor){
        try {
            $this->validate($request, [
                'id' => 'required|numeric'
            ]);
            return Productos\EliminarProducto::eliminarArchivoUrlDescarga($request,$idProveedor);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR"]);
        }
    }

    //utilizo para actulizar la caratula del producto
    public  function eliminarArchivoUrlDirectorio(Request $request,$idProveedor){
        try {
            $this->validate($request, [
                'id' => 'required|numeric'
            ]);
            return Productos\EliminarProducto::eliminarArchivoUrlDirectorio($request,$idProveedor);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR"]);
        }
    }

    //elimino todo aqui// todo el producto
    public function eliminarProductoLogicamente(Request $request,$idProveedor) {
       try {
            $this->validate($request,[
                'id' => 'required|numeric',
                'estado' => 'required|numeric'
            ]);
            return Productos\EliminarProducto::eliminarProductoLogicamente($request,$idProveedor);
       } catch (\Throwable $th) {
           return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
       }
    }

    public  function subirCaratula(Request $request,$idProveedor){
        return Productos\CrearProducto::subirCaratula($request,$idProveedor);
    }
    public function agregarProducto(Request $request,$idProveedor){
        try {
            $this->validate($request, [
                'id_genero' => 'required|numeric',
                'precio' => 'required|numeric',
                'url_descarga' => 'required|string',
                'url_directorio' => 'required|string',
                'estado' => 'required|numeric',
                'caratula' => 'string',
                'tipo_archivo' => 'required|numeric'
            ]);
            return Productos\CrearProducto::crearProducto($request,$idProveedor);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$request->json()->all()]);
        }
    }

    //listar productos en la tienda
    public function listasProductos(Request $request){
        return Productos\ListarProductos::listasProductosTienda($request);
    }

    public function listarTodoProductosPanel(Request $request,$idProveedor){
        return Productos\ListarProductos::listarTodoProductosPanel($request,$idProveedor);
    }
    public function obtenerDemoProducto(Request $request){
        try {
            $this->validate($request, [
                'id' => 'required|numeric',
            ]);
            return Productos\ListarProductos::obtenerDemoProducto($request);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$request->json()->all()]);
        }
    }

}
