<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Fields\File;


use ProtoneMedia\LaravelFFMpeg\Exporters\EncodingException;

//use FFMpeg;

class ArchivoDemoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            $data = $request->only('archivo_demo','metodo','id');
            $validator= Validator::make($data,[
                'archivo_demo' => 'required|max:1500|mimes:mp3',
                'metodo'=>'required|string',
                'id'=>'required|numeric'
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => "Error en validación de información",'data'=>$validator->messages()], 401);
            }else{
                if($request->metodo=='POST'){
                    return Repositorio\ArchivoDemo\ArchivoDemoRepositorio::subirArchivoDemoSevidorProedit($request);
                }
                if($request->metodo=='PUT'){
                    return Repositorio\ArchivoDemo\ArchivoDemoRepositorio::actualizarArchivoDemoSevidorProedit($request);
                }
                
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
    public function update(Request $request,$id)
    {
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     
    }
}
