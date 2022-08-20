<?php

namespace App\Http\Controllers\Clientes;

use App\Models\Cliente;
use App\Models\Genero;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Traits\TemplateCorreo;
class EditarCliente {
    use TemplateCorreo;
    public static function editarClientePanel($request,$idAdmin){
        try {

            $datos=$request->json()->all();
            $idAdminDesencriptado=Crypt::decrypt($idAdmin);
            $idCliente=Crypt::decrypt($request['id']);
            $esAdmin=Proveedor::where("id",$idAdminDesencriptado)
                                ->where("estado",1)
                                ->where("tipo_usuario",'Admin')
                                ->first();
            if(!$esAdmin){
                return response()->json(["sms"=>'El usuario '.$esAdmin.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
            }
              //actuliza texto plano sin contraseña
              if($datos["password"]){
                $opciones=array('cost'=>12);
                $passwordProveedor=$datos["password"];
                $password_hashed=password_hash($passwordProveedor,PASSWORD_BCRYPT,$opciones);
                $editarClienteNoPassword=Cliente::where("id",$idCliente)
                ->update(array
                            ('nombre'=>$datos['nombre'],
                              'apellido'=>$datos['apellido'],
                              'estado'=>$datos['estado'],
                              'saldo'=>$datos['saldo'],
                              'password'=>$password_hashed
                            )
                );
                if($editarClienteNoPassword){
                    return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$editarClienteNoPassword]);
                }
                return response()->json(["sms"=>"No se puedo actualizar el password","Siglas"=>"ONE",'res'=>$editarClienteNoPassword]);
           }else{
               $editarClienteNoPassword=Cliente::where("id",$idCliente)
               ->update(array
                           ('nombre'=>$datos['nombre'],
                             'apellido'=>$datos['apellido'],
                             'estado'=>$datos['estado'],
                              'saldo'=>$datos['saldo'],
                           )
               );
               if(!$editarClienteNoPassword){
                   return response()->json(["sms"=>"No se puedo actualizar los datos","Siglas"=>"ONE",'res'=>$editarClienteNoPassword]);
                }
                return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$editarClienteNoPassword]);
           }
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }

    public static function recuperarPassword($request){
        try {
            if(!($request->json())){
                return response()->json(["sms"=>"Los datos no tienene el formato deseado","Siglas"=>"DNF"]);
            }

            $existeCliente=Cliente::where("correo",$request['correo'])->first();
            if(!$existeCliente){
                return response()->json(["sms"=>"El usuario con el correo".$request['correo']." no existe en la base de datos","Siglas"=>"UNE","res"=>null]);
            }

            $nuevaContraseña = (Str::random(10));
            $opciones=array('cost'=>12);
            $password_hashed=password_hash($nuevaContraseña,PASSWORD_BCRYPT,$opciones);
            $passwordEditado=Cliente::where("id",$existeCliente->id)
            ->update(array('password'=>$password_hashed));
            if(!$passwordEditado){
                return response()->json(["sms"=>"El password no se ha podido actulizar","Siglas"=>"PNA","res"=>$existeCliente]);
            }
            $template=TemplateCorreo::templateRecuperarPassword($request['correo'],$nuevaContraseña);
            $enviarCorreo=TemplateCorreo::enviarCorreo($template,$request['correo'],getenv('TITULO_RECUPERAR_PASSWORD'));
            return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE","res"=>$existeCliente]);
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR","res"=>null]);
        }
    }
    public static function actualizarClientePerfil($request,$idCliente){
        try {

            $datos=$request->json()->all();
            $idClienteDesencriptado=Crypt::decrypt($idCliente);
            $esCliente=Cliente::where("id",$idClienteDesencriptado)
                                ->first();
            if(!$esCliente){
                return response()->json(["sms"=>'El usuario '.$idCliente.' no tiene permisos',"Siglas"=>"NTP",'res'=>null]);
            }
            //actuliza texto plano sin contraseña
            if($datos["password"]){
                $opciones=array('cost'=>12);
                $passwordCliente=$datos["password"];
                $password_hashed=password_hash($passwordCliente,PASSWORD_BCRYPT,$opciones);
                $editarClienteNoPassword=Cliente::where("id",$idClienteDesencriptado)
                ->update(array('nombre'=>$datos['nombre'],'apellido'=>$datos['apellido'],'password'=>$password_hashed));
            if($editarClienteNoPassword){
                return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$editarClienteNoPassword]);
            }
            return response()->json(["sms"=>"No se puedo actualizar el password","Siglas"=>"ONE",'res'=>$editarClienteNoPassword]);
           }else{
               $editarClienteNoPassword=Cliente::where("id",$idClienteDesencriptado)
               ->update(array('nombre'=>$datos['nombre'],'apellido'=>$datos['apellido']));
               if(!$editarClienteNoPassword){
                   return response()->json(["sms"=>"No se puedo actualizar los datos","Siglas"=>"ONE",'res'=>$editarClienteNoPassword]);
                }
                return response()->json(["sms"=>"Operación exitosa","Siglas"=>"OE",'res'=>$editarClienteNoPassword]);
           }
        } catch (\Throwable $th) {
            return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
}
