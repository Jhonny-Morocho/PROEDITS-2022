<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proveedor;
class ProveedorRolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $proveedoresRoles=null;
        try {
            //https://stackoverflow.com/questions/67355363/using-laravel-8-with-spatie-package-try-to-get-user-list-with-role-issue-call-to
            //$all_users_with_all_direct_permissions = User::with('permissions')->get();
            $proveedoresRoles = Proveedor::with('roles')->get();
            return response()->json(["message"=>'success','data'=>$proveedoresRoles],200);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$proveedoresRoles],400);
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
        $proveedor=null;
        try {

            $proveedor=Proveedor::where('correo',$request['correo'])->where('estado','1')->first();
            if($proveedor==null){
                return response()->json(["message"=>"No existe el usuario"],400);
            }
            //borrar los roles
            $proveedor->syncRoles([]);

            //asignar lores
            foreach ($request->roles as $key => $value) {
                $proveedor->assignRole($value['name']);
            }
            return response()->json(["message"=>'success','data'=>$proveedor],201);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$proveedor],400);
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

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
 /*    public function update(Request $request, $id)
    {
        $proveedor=null;
        try {

            $proveedor=Proveedor::where('id',$id)->where('estado','1')->first();
            if($proveedor==null){
                return response()->json(["message"=>"No existe el usuario"],400);
            }
            //remover todos los roles
            $proveedor->syncRoles([]);

            //reasignar roles
            foreach ($request->roles as $key => $value) {
                $proveedor->assignRole($value['name']);
            }
            return response()->json(["message"=>'success','data'=>$proveedor],200);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$proveedor],400);
        }
    } */

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
