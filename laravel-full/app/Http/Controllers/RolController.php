<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $roles=null;
        try {
            $roles=Role::get();
            return response()->json(["message"=>'success','data'=>$roles],200);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$roles],400);
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
        $rol=null;
        try {
            $rol=Role::where('name',$request['name'])->first();
            if($rol!=null){
                return response()->json(["message"=>"Ya existe un rol con el mismo nombre"],400);
            }
            $createRol=Role::create([
                'name' => $request['name']
            ]);
            return response()->json(["message"=>'success','data'=>$createRol],201);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$rol],400);
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
        $rol=null;
        try {
            $rol=Role::where('id',$id)->first();
            if($rol!=null){
                return response()->json(["message"=>'success','data'=>$rol],200);
            }
            return response()->json(["message"=>"Rol no encontrado"],400);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$rol],400);
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
        $rol=null;
        try {
            $rolExiste=Role::where('id',$id)->first();
            if($rolExiste==null){
                return response()->json(["message"=>"Rol no encontrado"],400);
            }
            //actualizo el rol
            $rol=Role::where('id',$id)->update(
                array('name'=>$request['name'])
            );
            if($rol==null){
                return response()->json(["message"=>"No se puede actulizar el rol"],400);
            }
            return response()->json(["message"=>'success','data'=>$rol],200);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$rol],400);
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
        $role=null;
        try {
            $roleExiste=Role::where('id',$id)->first();
            if($roleExiste==null){
                return response()->json(["message"=>"Genero no encontrado"],400);
            }
            //actualizo el genero
            $role=Role::where('id',$id)->delete();
            if($role==null){
                return response()->json(["message"=>"No se puede eliminar el rol"],400);
            }
            return response()->json(["message"=>'success','data'=>$role],200);
        } catch (\Throwable $th) {
            return response()->json(["message"=>$th->getMessage(),'data'=>$role],400);
        }
    }
}
