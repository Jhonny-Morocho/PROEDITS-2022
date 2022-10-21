<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class ClienteController extends Controller{

     /**
     * @OA\Get(
     *      path="/api/clientes/listasClientesPanel",
     *     @OA\Response(
     *          response="200", 
     *          description="Es una lista de clientes solo para panel de administacíon")
     * ),
     * @OA\Response(
     *          response="default",
     *          description="an ""unexpected"" error"
     * )
     */
    public function listasClientesPanel($idAdmin){
        return Clientes\ListarClientes::listasClientesPanel($idAdmin);
    }

/**
 * @OA\Post(
 *   path="/v1/media/upload",
 *   summary="Upload document",
 *   description="",
 *   tags={"Media"},
 *   @OA\RequestBody(
 *     required=true,
 *     @OA\MediaType(
 *       mediaType="application/octet-stream",
 *       @OA\Schema(
 *         required={"content"},
 *         @OA\Property(
 *           description="Binary content of file",
 *           property="content",
 *           type="string",
 *           format="binary"
 *         )
 *       )
 *     )
 *   ),
 *   @OA\Response(
 *     response=200, description="Success",
 *     @OA\Schema(type="string")
 *   ),
 *   @OA\Response(
 *     response=400, description="Bad Request"
 *   )
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




/**
 * @OA\Post(
 *   path="/v1/user/update",
 *   summary="Form post",
 *   @OA\RequestBody(
 *     @OA\MediaType(
 *       mediaType="multipart/form-data",
 *       @OA\Schema(
 *         @OA\Property(property="name"),
 *         @OA\Property(
 *           description="file to upload",
 *           property="avatar",
 *           type="string",
 *           format="binary",
 *         ),
 *       )
 *     )
 *   ),
 *   @OA\Response(response=200, description="Success")
 * )
 */

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



    public function registrarCliente(Request $request){
        $data=null;
        try {
            $data=$this->validate($request, [
               'nombre' => 'required|string|max:50|min:3',
               'apellido' => 'required|string|max:50|min:3',
               'correo' => 'required|email|max:255',
               'estado' => 'required|numeric|min:0',
               'proveedor' => 'required|string|max:100',
               'password' => 'required|string|max:50|min:3'
           ]);
           return Clientes\CrearCliente::registrarCliente($request);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(["sms"=>$e->response->original,"Siglas"=>"ERROR",'res'=>$data]);
        }
        catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$data]);
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
