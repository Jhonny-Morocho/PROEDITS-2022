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
    
    
}
