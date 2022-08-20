<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class ClienteController extends Controller{

    public function listasClientesPanel($idAdmin){
        return Clientes\ListarClientes::listasClientesPanel($idAdmin);
    }



/**
 * @OA\Get(
 *     path="/api/assets/getall",
 *     operationId="getAssets",
 *     tags={"Assets"},
 *     summary="Get all Assets",
 *     description="Fetches all the Asset records",

 *     @OA\Response(
 *          ref="success",
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(ref="#/components/schemas/standardResponse"),
 *      ),
 * )
 */
    public function login(Request $request){
        try {
            $this->validate($request, [
                'correo' => 'required|email|max:255',
                'password' => 'required|string|max:50|min:3'
            ]);
            return Clientes\LoginCliente::login($request);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR"]);
        }
    }






    public function loginSocial(Request $request){
        try {
            $this->validate($request, [
                'correo' => 'required|email|max:255'
            ]);
            return Clientes\LoginCliente::loginSocial($request);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR"]);
        }
    }






    public function recuperarPassword(Request $request){
        try {

            $this->validate($request, [
                'correo' => 'required|email|max:255',
            ]);
            return Clientes\EditarCliente::recuperarPassword($request);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR"]);
        }
    }




    /**
     * @OA\Post(
     *     path="/pets",
     *     operationId="addPet3",
     *     description="Creates a new pet in the store.  Duplicates are allowed",
     *     tags={"store"},
     *     @OA\RequestBody(
     *         description="Pet to add to the store",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/NewPet")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="pet response",
     *         @OA\JsonContent(ref="#/components/schemas/Pet")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorModel")
     *     )
     * )
     */
    public function registrarCliente(Request $request){
        try {
            $this->validate($request, [
               'nombre' => 'required|string|max:50|min:3',
               'apellido' => 'required|string|max:50|min:3',
               'correo' => 'required|email|max:255',
               'estado' => 'required|numeric|min:0',
               'proveedor' => 'required|string|max:100',
               'password' => 'required|string|max:50|min:3'
           ]);
           return Clientes\CrearCliente::registrarCliente($request);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }

    }

    public function editarClientePerfil(Request $request,$idCliente){

        try {
            $this->validate($request, [
               'nombre' => 'required|string|max:50|min:3',
               'apellido' => 'required|string|max:50|min:3',
               'password' => ''
           ]);
           return Clientes\EditarCliente::actualizarClientePerfil($request,$idCliente);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }

    }

    public function editarClientePanel(Request $request,$idAdmin){
        try {
            $this->validate($request, [
               'id' => 'required|string',
               'nombre' => 'required|string|max:50|min:3',
               'apellido' => 'required|string|max:50|min:3',
               'estado' => 'required|numeric|min:0',
               'password' => '',
               'saldo' => 'required|numeric|min:0'
           ]);
           return Clientes\EditarCliente::editarClientePanel($request,$idAdmin);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }

    }

    public function saldoActualCliente($idCliente){
        try {

           return Clientes\ListarClientes::saldoActualCliente($idCliente);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }

    }
}
