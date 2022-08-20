<?php

namespace App\Http\Controllers\Productos;

use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class EditarProducto {


  public static function editarProductoProveedor($request,$idProveedor){
    try {
        $datos=$request->json()->all();
        //puede editar el proveedor o el admin
        $idDesencriptado=Crypt::decrypt($idProveedor);
        //verificar si es proveedor
        $esProveedor=Proveedor::where("id",$idDesencriptado)
        ->where("estado",1)
        ->first();
        $editarProducto=FALSE;

        if(!$esProveedor){
            return response()->json(["sms"=>'El usuario '.$idProveedor.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
        }

        //si edita solo el demo archivo
        if($datos['url_directorio']!=='' && $datos['url_descarga']==''){
            $editarProducto=Producto::where("id",$datos['id'])
            ->update(array
                        ('id_genero'=>$datos['id_genero'],
                          'precio'=>$datos['precio'],
                          'url_directorio'=>$datos['url_directorio'],
                          'tipo_archivo'=>$datos['tipo_archivo']
                        )
            );
        }
        // si edita solo el remix archivo
        if($datos['url_descarga']!=='' && $datos['url_directorio']==''){
            $editarProducto=Producto::where("id",$datos['id'])
            ->update(array
                        ('id_genero'=>$datos['id_genero'],
                          'precio'=>$datos['precio'],
                          'url_descarga'=>$datos['url_descarga'],
                          'tipo_archivo'=>$datos['tipo_archivo']
                        )
            );

        }
        // si editar los dos //remix//demo//ambos archivos
        if($datos['url_descarga']!=='' && $datos['url_directorio']!==''){
            $editarProducto=Producto::where("id",$datos['id'])
            ->update(array
                        ('id_genero'=>$datos['id_genero'],
                          'precio'=>$datos['precio'],
                          'url_descarga'=>$datos['url_descarga'],
                          'url_directorio'=>$datos['url_directorio'],
                          'tipo_archivo'=>$datos['tipo_archivo']
                        )
            );

        }
        // editar solo texto
        if($datos['url_descarga']=='' && $datos['url_directorio']==''){
            $editarProducto=Producto::where("id",$datos['id'])
            ->update(array
                        ('id_genero'=>$datos['id_genero'],
                          'precio'=>$datos['precio']
                        )
            );

        }

        if(!$editarProducto){
            return response()->json(["sms"=>"Producto no editado",
                                    "Siglas"=>"ONE",
                                    'res'=>$editarProducto]);
        }
        return response()->json(["sms"=>"Operación exitosa",
                                 "Siglas"=>"OE",
                                 'res'=>$editarProducto]);
    } catch (\Throwable $th) {
        return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
    }

  }
}
