<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Genero;
class GeneroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $generos=null;
        try {
            $generos=Genero::where('estado',1)->get();
            return response()->json(["message"=>'success','data'=>$generos],201);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$generos],400);
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
        $genero=null;
        try {
            $genero=Genero::where('genero',$request['genero'])->where('estado',1)->first();
            if($genero!=null){
                return response()->json(["message"=>"Ya existe un genero con el mismo nombre"],400);
            }
            $createGenero=Genero::create([
                'genero' => $request['genero'],
                'estado' => $request['estado'],
                'fecha' => Carbon::now()
            ]);
            return response()->json(["message"=>'success','data'=>$createGenero],201);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$genero],400);
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
        $genero=null;
        try {
            $genero=Genero::where('id',$id)->where('estado',1)->first();
            if($genero!=null){
                return response()->json(["message"=>'success','data'=>$genero],201);
                
            }
            return response()->json(["message"=>"Genero no encontrado"],400);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$genero],400);
        }
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
        $genero=null;
        try {
            $generoExiste=Genero::where('id',$id)->where('estado',1)->first();
            if($generoExiste==null){
                return response()->json(["message"=>"Genero no encontrado"],400);
            }
            //actualizo el genero
            $genero=Genero::where('id',$id)->update(
                array('genero'=>$request['genero'],'fecha' => Carbon::now())
            );
            if($genero==null){
                return response()->json(["message"=>"No se puede actulizar el genero"],400);
            }
            return response()->json(["message"=>'success','data'=>$genero],200);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$genero],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $genero=null;
        try {
            $generoExiste=Genero::where('id',$id)->where('estado',1)->first();
            if($generoExiste==null){
                return response()->json(["message"=>"Genero no encontrado"],400);
            }
            //actualizo el genero
            $genero=Genero::where('id',$id)->update(
                array('estado'=>0,'fecha' => Carbon::now())
            );
            if($genero==null){
                return response()->json(["message"=>"No se puede eliminar el genero"],400);
            }
            return response()->json(["message"=>'success','data'=>$genero],200);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$genero],400);
        }
    }
}
