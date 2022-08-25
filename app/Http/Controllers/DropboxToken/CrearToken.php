<?php

namespace App\Http\Controllers\DropboxToken;

use App\Models\DropboxToken;
use Carbon\Carbon;
class CrearToken {

    public static function registrarDropboxToken($dropboxKey,$dropboxSecret,$refreshToken){
        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->request("POST", "https://{$dropboxKey}:{$dropboxSecret}@api.dropbox.com/oauth2/token", [
                'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                ]
            ]);
            $ObjDrobox=new DropboxToken();
            if($res->getStatusCode() == 200) {
              $resDropbox= json_decode($res->getBody());
              $ObjDrobox->token_type=$resDropbox->token_type;
              $ObjDrobox->access_token=$resDropbox->access_token;
              $ObjDrobox->expires_in=$resDropbox->expires_in;
              $ObjDrobox->created_at=Carbon::now();
              $ObjDrobox->updated_at=Carbon::now();
              $ObjDrobox->save();
              return $ObjDrobox;
            } else {
              return $ObjDrobox;
            }
        }
        catch (\Throwable $th) {
          return response()->json(["sms"=>$th->getMessage(),"Siglas"=>"ERROR",'res'=>null]);
        }
    }
}
