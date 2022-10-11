<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;
class Cliente extends Model implements AuthenticatableContract,AuthorizableContract,JWTSubject{
    use Authenticatable, Authorizable;
    //nombre de la tabla
    protected $table="cliente";
    //para saber si en la tabla usamos created_at y update_at
    public $timestamp=true;
    //lista blanca cmapos publicos
    protected $fillable=[
        "nombre",
        "apellido",
        "correo",
        "password",
        "tipo_usuario",
        "saldo",
        "proveedor",
        "estado",
        "created_at",
        "updated_at",
    ];
    public function pedido(){
        return $this->hasMany('App\Models\Pedido','id_cliente');
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
        /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}
