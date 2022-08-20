<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarruselController extends Controller{
    /**
     * @OA\Get(
     *     path="/sample/{category}/things",
     *     operationId="/sample/category/things",
     *     tags={"yourtag"},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         description="The category parameter in path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="criteria",
     *         in="query",
     *         description="Some optional other parameter",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Returns some sample category things",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */

    public  function subirImgCarrusel(Request $request,$idProveedor){
        return Carrusel\CrearCarrusel::subirImgCarrusel($request,$idProveedor);
    }
    public  function editarImgCarrusel(Request $request,$idAdmin){
        return Carrusel\EditarCarrusel::editarImgCarrusel($request,$idAdmin);
    }
    public  function listarCarrusel(){
        return Carrusel\ListarCarrusel::listarImgCarrusel();
    }
    public function eliminarImgCarrusel(Request $request,$idAdmin){
        try {
            $this->validate($request, [
                'id' => 'required|numeric'
            ]);
            return Carrusel\EliminarCarrusel::eliminarImgCarrusel($request,$idAdmin);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
}
