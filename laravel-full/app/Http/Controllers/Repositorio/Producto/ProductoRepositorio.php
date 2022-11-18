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
                'fk_genero ' => $request['fk_genero'],
                'fk_proveedor ' => $request['fk_proveedor'],
                'precio' => $request['precio'],
                'url_descarga' => $request['url_descarga'],
                'url_directorio' => $request['url_directorio'],
                'estado' => $request['estado'],
                'es_archivo' => $request['es_archivo'],
                'caratula' => $request['caratula'],
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
                        ->where('precio','>=',$request->precioFin)
                        ->where('fk_genero',$request->genero)
                        ->where('fk_proveedor',$request->genero)
                        ->whereBetween('precio',[$request->precioInicio,$request->precioFin])
                        ->whereBetween('created_at',[$request->fechaInicio,$request->fechaFin])
                        ->paginate($request->numElementos);
            return response()->json(["message"=>'success','data'=>$productos],200);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$productos],400);
        }
    }


}
