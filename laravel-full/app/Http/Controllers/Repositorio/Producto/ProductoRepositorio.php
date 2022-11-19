<?php

namespace App\Http\Controllers\Repositorio\Producto;

use App\Models\Producto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


class ProductoRepositorio {
    
    public static function crarProducto($request){
        $createProducto=null;
        try {
            $createProducto=Producto::create([
                'fk_proveedor' => $request['fk_proveedor'],
                'fk_genero' => $request['fk_genero'],
                'precio' => $request['precio'],
                'archivo_descarga' => $request['archivo_descarga'],
                'archivo_demo' => $request['archivo_demo'],
                'nombre' => $request['nombre'],
                'estado' => $request['estado'],
                'es_archivo' => $request['es_archivo'],
                'fecha' => Carbon::now()
            ]);
            return response()->json(["message"=>'success','data'=>$createProducto],201);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$createProducto],400);
        }
    }

    public static function obtenerTodosProductos($request){
        $productos=null;
        try {
            $productos=Producto::where('estado',1)
                        ->where('fk_genero',$request->genero)
                        ->where('fk_proveedor',$request->genero)
                        ->where('nombre', 'LIKE', "%$request->buscar%")
                        ->whereBetween('precio',[$request->precioInicio,$request->precioFin])
                        ->orWhereBetween('fecha',[$request->fechaInicio,$request->fechaFin])
                        ->paginate($request->numElementos);
            return response()->json(["message"=>'success','data'=>$productos],200);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$productos],400);
        }
    }


}
