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
use App\Models\Producto;
class Proveedor extends Model
{
    use HasRoles;

    protected $guarded=[
        'id',
        'created_at',
        'updated_at'
    ];
    protected $hidden = ['password'];
    //
    public function guardName(){
        return "web";
    }
    //roles
    public function producto()
    {
        return $this->belongTo(Producto::class);
    } 
 /*    public function producto(){
        return $this->hasMany('App\Models\Producto','fk_producto');
    } */
}
