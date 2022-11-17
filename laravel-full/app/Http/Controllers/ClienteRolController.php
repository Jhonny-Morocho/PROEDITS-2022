<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
class ClienteRolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clienteRoles=null;
        try {
            //https://stackoverflow.com/questions/67355363/using-laravel-8-with-spatie-package-try-to-get-user-list-with-role-issue-call-to
            //$all_users_with_all_direct_permissions = User::with('permissions')->get();
            $clienteRoles = Cliente::with('roles')->get(); 
            return response()->json(["message"=>'success','data'=>$clienteRoles],200);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$clienteRoles],400);
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
        $cliente=null;
        try {
    
            $cliente=Cliente::where('correo',$request['correo'])->first();
            if($cliente==null){
                return response()->json(["message"=>"No existe el usuario"],400);
            }
            //borrar los roles 
            $cliente->syncRoles([]); 
            
            //asignar lores
            foreach ($request->roles as $key => $value) {
                $cliente->assignRole($value['name']);
            }
            return response()->json(["message"=>'success','data'=>$cliente],201);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$cliente],400);
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
