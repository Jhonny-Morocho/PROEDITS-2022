<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Foundation\Auth\User as Authenticatable;
//roles
use Spatie\Permission\Traits\HasRoles;
//jwt
//use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
//use Illuminate\Notifications\Notifiable; 

class Proveedor extends Model
{
    use HasRoles;

    protected $guarded=[
        'id',
        'created_at',
        'updated_at'
    ];
    protected $hidden = ['password'];
    /* public function guardName(){
        return "api";
    } */
    //jwt

       /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
/*     public function getJWTIdentifier()
    {
        return $this->getKey();
    } */

      /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
  /*   public function getJWTCustomClaims()
    {
        return [];
    } */
    
}
