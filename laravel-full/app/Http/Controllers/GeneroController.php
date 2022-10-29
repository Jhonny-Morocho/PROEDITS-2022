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
        $genero=null;
        try {
            $genero=Genero::where('genero',$request['genero'])->where('estado',1)->first();
            if($genero!=null){
                return response()->json(["message"=>"Ya existe un genero con el mismo nombre"],400);
            }
            $createGenero=Genero::create([
                'genero' => $request['genero'],
                'estado' => 1,
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
