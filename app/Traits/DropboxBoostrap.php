<?php
namespace App\Traits;

use Kunnu\Dropbox\DropboxApp;
use App\Models\DropboxToken;
use App\Http\Controllers\DropboxTokenController;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
trait DropboxBoostrap {

    public static function configDropbox(){
        //Configure Dropbox service
        $dropboxKey="yarkoxjzanivxo2";
        $dropboxSecret="t6udrfbt565l2ag";
        //el codigo uso para generar un nuevo token de refresh
        $code="vGZr-pSIgbAAAAAAAAAHXL2xJGc7AZ72ISkI5_bh3Qg";
        //$dropboxToken="sl.BNJwc_RCpT0wBa9wGRcsJ8t4iO31DtEozuKzGbdfrnTJzKS0JuqMpE0Co-T3ef_3lfNqnNqjGAQ8bz--cr5Wv8lViYGyEdPL14NGITRX3hJWhE96aEwGxKAxgVr6iLZGRCzgosm2MJbw";
        $refreshToken="Iv6DW8v0ISkAAAAAAAAAAclI201UON9PtQEVcMXi-2KlM6p3NepRlbNw5IRIqGoz";

        try {
          $nombreLLave='token_cache';
          //********* EXISTE TOKEN EN CACHCE ***************//
          //********* EXISTE TOKEN EN CACHCE ***************//
          //********* EXISTE TOKEN EN CACHCE ***************//
          //Cache::pull($nombreLLave);//eliminar token de cachce para probar
          $cacheToken=Cache::get($nombreLLave);

          if($cacheToken){
            return $app = new DropboxApp($dropboxKey, $dropboxSecret,$cacheToken);
          }
          //********* OBTENER TOKEN EN BASE DE DATOS ***************//
          //********* OBTENER TOKEN EN BASE DE DATOS ***************//
          //********* OBTENER TOKEN EN BASE DE DATOS ***************//
          $existeToken=DropboxToken::where('created_at',"<=",Carbon::now())->orderBy('id', 'desc')->first();
          if($existeToken!==null){
            $FechaTokenCaducidad = Carbon::parse($existeToken->created_at, 'UTC')->addSeconds($existeToken->expires_in);
            
            //debemos revisar si ese token caduco//en este caso no caduca
            if($FechaTokenCaducidad>=Carbon::now()){
              //crear token en cache
              $key=$nombreLLave;
              $expireDate=$FechaTokenCaducidad;
              $access_token=$existeToken->access_token;
              //guardo en cache el token
              Cache::put($key,$access_token,$expireDate);
              return $app = new DropboxApp($dropboxKey, $dropboxSecret,$existeToken->access_token);
            }
            //tengo q pedir un nuevo token a la api de dropbox
            if($FechaTokenCaducidad<= Carbon::now()){
              $responseToken=DropboxTokenController::registrarDropboxToken($dropboxKey,$dropboxSecret,$refreshToken);
              
              $key=$nombreLLave;
              $expireDate=(Carbon::now()->addSeconds($responseToken->expires_in));
              $access_token=$responseToken->access_token;
              
              Cache::put($key,$access_token,$expireDate);
              $app = new DropboxApp($dropboxKey, $dropboxSecret,$existeToken->access_token);
              return $app;
            }
            return;
          }
          //********* NO EXISTE  TOKEN EN CACHCE TAMPOCO EN LA BD CREAR UNO NUEVO DESDE CERO SI LA BD ESTA VACIA ***************//
          //********* NO EXISTE  TOKEN EN CACHCE TAMPOCO EN LA BD CREAR UNO NUEVO DESDE CERO SI LA BD ESTA VACIA ***************//
          //********* NO EXISTE  TOKEN EN CACHCE TAMPOCO EN LA BD CREAR UNO NUEVO DESDE CERO SI LA BD ESTA VACIA ***************//
          $responseToken=DropboxTokenController::registrarDropboxToken($dropboxKey,$dropboxSecret,$refreshToken);
          if($responseToken->access_token){
            $key=$nombreLLave;
            $expireDate=(Carbon::now()->addSeconds($responseToken->expires_in));
            $access_token=$responseToken->access_token;
            
            //guardo en cachce el token
            Cache::put($key,$access_token,$expireDate);
            $app = new DropboxApp($dropboxKey, $dropboxSecret,$responseToken->access_token);
            return $app;
          }
        } catch (\Throwable $th) {
          return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR"]);
        }
  
    }

    
}
