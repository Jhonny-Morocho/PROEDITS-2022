<?php

namespace App\Models;

//use Illuminate\Foundation\Auth\User as Authenticatable;
//roles
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Proveedor extends Authenticatable implements JWTSubject
{
    use HasRoles;

    protected $guarded=[
        'id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = ['password'];
    //
   /*  public function guardName(){
        return "api";
    } */
    //roles
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }


    public function producto(){
        return $this->hasMany(Producto::class,'fk_producto');
    }
}
