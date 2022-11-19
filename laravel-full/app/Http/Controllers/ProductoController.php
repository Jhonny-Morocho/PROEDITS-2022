<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data = $request->only('precioInicio', 
                                    'precioFin', 
                                    'genero',
                                    'proveedor',
                                    'numElementos',
                                    'fechaIncio',
                                    'fechaFin',
                                    'buscar',
                                    'page',
                                    'caratula');
            $validator = Validator::make($data, [
                'precioInicio' => 'required|numeric',
                'precioFin' => 'required|numeric',
                'genero' => 'required|numeric',
                'proveedor'=>'required|string',
                'page'=>'required|numeric',
                'buscar'=>'string|max:100|nullable',
                'numElementos' => 'required|numeric',
                'fechaIncio' => 'required|string|date',
                'fechaFin' => 'required|string|date',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['message' => "Error en validación de información",'data'=>$validator->messages()], 401);
            }else{
                return Repositorio\Producto\ProductoRepositorio::obtenerTodosProductos($request);
            }
         }
         catch (\Throwable $th) {
             return response()->json(["message"=>$th->getMessage(),'data'=>null],400);
         }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->only('fk_genero', 
                                    'fk_proveedor', 
                                    'precio',
                                    'archivo_descarga',
                                    'archivo_demo',
                                    'estado',
                                    'es_archivo',
                                    'caratula',
                                    'nombre');
                                    
            $validator = Validator::make($data, [
                'fk_genero' => 'required|numeric|min:1',
                'fk_proveedor' => 'required|numeric|min:1',
                'precio' => 'required|numeric|min:1',
                'archivo_descarga'=>'required|string',
                'archivo_demo' => 'required|string',
                'nombre' => 'required|string',
                'estado' => 'required|numeric|min:1',
                'caratula'=>'string|max:100|nullable',
                'es_archivo' => 'required|numeric|min:1'
            ]);
            
            if ($validator->fails()) {
                return response()->json(['message' => "Error en validación de información",'data'=>$validator->messages()], 401);
            }else{
                return Repositorio\Producto\ProductoRepositorio::crarProducto($request);
            }
         }
         catch (\Throwable $th) {
             return response()->json(["message"=>$th->getMessage(),'data'=>null],400);
         }
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
