<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return User::get();
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
            if($proveedor!=null){
                return response()->json(["message"=>"Ya existe un usuario con el mismo correo"],400);
            }
            $createProveedor=Proveedor::create([
                'nombre' => $request['nombre'],
                'apellido' => $request['apellido'],
                'apodo' => $request['apodo'],
                'correo' => $request['correo'],
                'password' => Hash::make($request['password']),
                'img' => $request['img'],
                'estado' => $request['estado'],
                'fecha' => Carbon::now()
            ]);
            return response()->json(["message"=>'success','data'=>$createProveedor],201);
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
