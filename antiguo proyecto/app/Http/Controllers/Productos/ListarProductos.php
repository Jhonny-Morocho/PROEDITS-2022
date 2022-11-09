<?php

namespace App\Http\Controllers\Productos;

use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Crypt;
class ListarProductos {

  //listar productos en la tienda
    public static function listasProductosTienda($request){
    try {
        if($request['buscar']==""){
            $productos=Proveedor::join("productos","productos.id_proveedor","proveedor.id")->
                       join("genero","genero.id","productos.id_genero")->
                       where("productos.estado",1)->
                       where("proveedor.estado",1)->
                       select('url_directorio',
                       'genero',
                       'caratula',
                       'img',
                       'precio',
                       "productos.created_at",
                       'productos.id')->
                     orderBy('id', 'DESC')->
                     paginate(30);
            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$productos]);
        }

        $productos=Proveedor::join("productos","productos.id_proveedor","proveedor.id")->
            join("genero","genero.id","productos.id_genero")->
            where('productos.estado',1)->
            where("proveedor.estado",1)->
            where('productos.url_directorio','LIKE','%'.$request['buscar'].'%')

            ->select('url_directorio',
                    'genero',
                    'precio',
                    'productos.caratula',
                    "proveedor.img",
                    "proveedor.apodo",
                    "productos.created_at",
                    'productos.id')->
            orderBy('productos.id', 'DESC')->paginate(30);
        return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$productos]);

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }


    public static function listarTodoProductosPanel($request,$idProveedor){
        try {
            $idDesencriptado=Crypt::decrypt($idProveedor);
            $esProveedor=Proveedor::where("id",$idDesencriptado)->where("estado",1)->first();
            if(!$esProveedor){
                return response()->json(["sms"=>'El usuario'.$idProveedor.' no tiene permisos',"Siglas"=>"OE",'res'=>null]);
            }
            // si es Admin que mueste todo los resultados
            if($esProveedor->tipo_usuario=='Admin'){
                if($request['buscar']==""){
                    $productos=Proveedor::join("productos","productos.id_proveedor","proveedor.id")
                                ->join("genero","genero.id","productos.id_genero")
                                ->where("productos.estado",1)
                                ->where("proveedor.estado",1)
                                ->select('url_directorio',
                                'precio',
                                "productos.created_at",
                                "productos.updated_at",
                                "proveedor.img",
                                "tipo_archivo",
                                "productos.id_genero",
                                "apodo",
                                "genero",
                                "caratula",
                                "url_descarga",
                                'productos.id')->
                                orderBy('id', 'DESC')->
                                paginate(20);
                    return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$productos]);
                }

                $productos=Proveedor::join("productos","productos.id_proveedor","proveedor.id")
                    ->join("genero","genero.id","productos.id_genero")
                    ->where('productos.estado',1)
                    ->where("proveedor.estado",1)
                    ->where('productos.url_directorio','LIKE','%'.$request['buscar'].'%')
                    ->select('url_directorio',
                    'genero',
                    'precio',
                    "productos.created_at",
                    "productos.updated_at",
                    "productos.id_genero",
                    "tipo_archivo",
                    "proveedor.img",
                    "apodo",
                    "genero",
                    "caratula",
                    "url_descarga",
                    'productos.id')->
                    orderBy('productos.id', 'DESC')->paginate(20);
                return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$productos]);
            }
            //es proveedor
            if($esProveedor->tipo_usuario=='Proveedor'){
                if($request['buscar']==""){
                    $productos=Proveedor::join("productos","productos.id_proveedor","proveedor.id")
                                ->join("genero","genero.id","productos.id_genero")
                                ->where("productos.estado",1)
                                ->where("proveedor.estado",1)
                                ->where("proveedor.id",$idDesencriptado)
                                ->select('url_directorio',
                                'genero',
                                'precio',
                                "productos.created_at",
                                "productos.updated_at",
                                "productos.id_genero",
                                "proveedor.img",
                                "tipo_archivo",
                                "apodo",
                                "genero",
                                "caratula",
                                "url_descarga",
                                'productos.id')->
                                orderBy('id', 'DESC')->
                                paginate(20);
                    return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$productos]);
                }

                $productos=Proveedor::join("productos","productos.id_proveedor","proveedor.id")
                    ->join("genero","genero.id","productos.id_genero")
                    ->where('productos.estado',1)
                    ->where("proveedor.estado",1)
                    ->where("proveedor.id",$idDesencriptado)
                    ->where('productos.url_directorio','LIKE','%'.$request['buscar'].'%')
                    ->select('url_directorio',
                    'genero',
                    'precio',
                    "productos.created_at",
                    "productos.updated_at",
                    "productos.id_genero",
                    "tipo_archivo",
                    "proveedor.img",
                    "apodo",
                    "genero",
                    "caratula",
                    "url_descarga",
                    'productos.id')->
                    orderBy('productos.id', 'DESC')->paginate(20);
                return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$productos]);
            }

        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ONE",'res'=>null]);
        }
    }
    public static function obtenerDemoProducto($request){

        try {
            $productoDemo=Producto::join('proveedor',"proveedor.id","productos.id_proveedor")

                        ->select("productos.id","productos.precio",
                        "productos.caratula",
                        "productos.url_directorio",
                        "proveedor.img")
                        ->where("productos.estado",1)
                        ->where('productos.id',$request['id'])
                        ->first();
            return response()->json(["sms"=>'Operación exitosa',"Siglas"=>"OE",'res'=>$productoDemo]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>$request->json()->all()]);
        }
    }
}
